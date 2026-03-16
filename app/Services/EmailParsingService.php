<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Str;

class EmailParsingService
{
    /**
     * Fetch unread emails from all connected IMAP accounts for the current team.
     */
    public function scanInbox(): array
    {
        $teamId = auth()->user()->current_team_id;
        $accounts = \App\Models\EmailAccount::where('team_id', $teamId)->where('is_active', true)->get();
        
        $liveEmailService = new LiveEmailService();
        $allEmails = [];

        foreach ($accounts as $account) {
            $emails = $liveEmailService->fetchUnread($account);
            $allEmails = array_merge($allEmails, $emails);
        }

        return $allEmails;
    }

    /**
     * Extracts potential contact data from a raw email message.
     */
    public function extractContacts(array $emails): array
    {
        $extracted = [];

        foreach ($emails as $email) {
            $company = $this->extractCompanyFromDomain($email['sender_email']);
            
            $extracted[] = [
                'name' => $email['sender_name'],
                'email' => $email['sender_email'],
                'company' => $company,
                'source' => 'Email Inbox Scan',
                'status' => 'new',
            ];
        }

        return $extracted;
    }

    /**
     * Checks extracted contacts against the database to separate new vs existing leads.
     */
    public function identifyNewLeads(array $extractedContacts, int $userId): array
    {
        $newLeads = [];
        $existingLeads = [];

        $existingEmails = Contact::where('user_id', $userId)
            ->whereNotNull('email')
            ->pluck('email')
            ->toArray();

        foreach ($extractedContacts as $contact) {
            if (in_array(strtolower($contact['email']), array_map('strtolower', $existingEmails))) {
                $existingLeads[] = $contact;
            } else {
                $newLeads[] = $contact;
            }
        }

        return [
            'new' => $newLeads,
            'existing' => $existingLeads
        ];
    }

    /**
     * Simple heuristic to create a company name from an email domain.
     */
    private function extractCompanyFromDomain(string $email): string
    {
        $domain = substr(strrchr($email, "@"), 1);
        
        // Exclude common free domains
        if (in_array(strtolower($domain), ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'aol.com'])) {
            return 'Unknown (Free Email)';
        }

        // Remove the TLD
        $parts = explode('.', $domain);
        $companyName = Str::title($parts[0]);

        return $companyName;
    }
}
