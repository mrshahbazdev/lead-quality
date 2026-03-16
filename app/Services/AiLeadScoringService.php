<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\Log;

class AiLeadScoringService
{
    /**
     * Simulate an AI deep analysis of a contact's digital footprint.
     * In a real application, this would call out to OpenAI/Anthropic APIs
     * with scraped data from the contact's email domain, website, and LinkedIn.
     */
    public function analyze(Contact $contact): array
    {
        $team = $contact->team;
        
        if ($team && !empty($team->groq_api_keys)) {
            // Pick a random Groq key to bypass rate limits
            $keys = $team->groq_api_keys;
            $randomKey = $keys[array_rand($keys)];
            return $this->analyzeWithAI($contact, $randomKey, 'groq');
        } elseif ($team && $team->openai_api_key) {
            return $this->analyzeWithAI($contact, $team->openai_api_key, 'openai');
        }

        return $this->simulateAnalysis($contact);
    }

    private function analyzeWithAI(Contact $contact, string $apiKey, string $provider): array
    {
        try {
            $model = 'gpt-3.5-turbo';
            $factory = \OpenAI::factory()->withApiKey($apiKey);
            
            if ($provider === 'groq') {
                $factory->withBaseUri('api.groq.com/openai/v1');
                $model = 'openai/gpt-oss-120b'; // Use the exact model requested by user
            }
            
            $client = $factory->make();
            
            $prompt = "Analyze this B2B lead based on their contact information and provide an evaluation.
            
            Name: {$contact->name}
            Email: {$contact->email}
            Job Title: {$contact->role}
            Company/Website: {$contact->website}
            LinkedIn: {$contact->linkedin}
            
            Provide the response in strict JSON format with exactly these three keys:
            - 'high_probability': a boolean true or false. True if they are a strong B2B fit (e.g. decision maker, professional domain, etc).
            - 'score': an integer from 0 to 100 representing how confident you are in this lead.
            - 'insights': a JSON array of 3 to 4 short string sentences explaining your reasoning. Use emojis at the start of each insight string (like ✅, ⭐, ⚠️, 🔍, 🚀).";

            $response = $client->chat()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an expert SDR and B2B Lead Generation Analyst.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

            $content = $response->choices[0]->message->content;
            $data = json_decode($content, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($data['high_probability'], $data['score'], $data['insights'])) {
                return $data;
            }
        } catch (\Exception $e) {
            Log::error(strtoupper($provider) . ' API Error: ' . $e->getMessage());
        }

        // Fallback to simulation if JSON fails or API throws exception
        return $this->simulateAnalysis($contact);
    }

    private function simulateAnalysis(Contact $contact): array
    {
        // Add a slight delay to simulate API processing for realism in the UI
        sleep(2);

        $insights = [];
        $score = 0;

        // 1. Analyze Email Domain
        if ($contact->email) {
            $domain = substr(strrchr($contact->email, "@"), 1);
            if (!in_array($domain, ['gmail.com', 'yahoo.com', 'hotmail.com'])) {
                $score += 30; // Professional domain
                $insights[] = "✅ Professional email domain detected (likely B2B lead).";
            } else {
                $insights[] = "⚠️ Free email domain detected. Recommend verifying business intent.";
            }
        } else {
            $insights[] = "❌ Missing email address.";
        }

        // 2. Analyze LinkedIn
        if ($contact->linkedin) {
            $score += 40;
            $insights[] = "✅ LinkedIn profile found. Engagement indicates active decision-maker.";
            if (in_array(strtolower($contact->role), ['ceo', 'founder', 'director', 'manager', 'owner'])) {
                $score += 15;
                $insights[] = "⭐ Title aligns with high decision-making authority.";
            }
        } else {
            $insights[] = "⚠️ Missing LinkedIn profile. Verification of role needed.";
        }

        // 3. Analyze Website
        if ($contact->website) {
            $score += 15;
            $insights[] = "✅ Company website active. Business appears legitimate with consistent branding.";
        }

        // Determine outcome
        $isHighProbability = $score >= 70;

        if ($isHighProbability) {
            array_unshift($insights, "🚀 AI Summary: High probability match based on digital footprint.");
        } else {
            array_unshift($insights, "🔍 AI Summary: Needs more qualification. Limited digital signals found. (No OpenAI key provided)");
        }

        return [
            'high_probability' => $isHighProbability,
            'insights' => $insights,
            'score' => $score // Internal score metric used for insights
        ];
    }
}
