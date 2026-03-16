<?php

namespace App\Services;

use App\Models\Contact;

class AiLeadService
{
    /**
     * Generate "AI-Powered" insights for a lead.
     */
    public function getSmartInsights(Contact $contact): array
    {
        $insights = [];
        
        // Simulated AI Logic
        if ($contact->budget > 10000) {
            $insights[] = "🚀 High Growth Potential: Large budget relative to industry average.";
        }
        
        if (str_contains(strtolower($contact->position), 'ceo') || str_contains(strtolower($contact->position), 'founder')) {
            $insights[] = "🎯 Decision Maker Identified: Direct outreach recommended.";
        }

        if ($contact->industry === 'Real Estate') {
            $insights[] = "🏠 Industry Trend: Market volatility suggests immediate follow-up on pain points.";
        }

        if (empty($insights)) {
            $insights[] = "💡 Strategy: Standard qualification required to unlock deeper insights.";
        }

        return $insights;
    }
}
