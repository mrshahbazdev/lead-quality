<?php

namespace App\Services;

use App\Models\EmailAccount;
use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Log;

class LiveEmailService
{
    /**
     * Fetch unread emails for a specific account.
     */
    public function fetchUnread(EmailAccount $account)
    {
        $cm = new ClientManager();
        $client = $cm->make([
            'host'          => $account->imap_host,
            'port'          => $account->imap_port,
            'encryption'    => $account->imap_encryption,
            'validate_cert' => false,
            'username'      => $account->username,
            'password'      => $account->password,
            'protocol'      => 'imap'
        ]);

        try {
            $client->connect();
        } catch (\Exception $e) {
            Log::error("IMAP Connection failed for account {$account->email_address}: " . $e->getMessage());
            throw new \Exception("Could not connect to {$account->email_address}. Please check your credentials and IMAP settings. (" . $e->getMessage() . ")");
        }

        $folder = $client->getFolder('INBOX');
        $messages = $folder->query()->unseen()->get();

        $results = [];

        /** @var \Webklex\PHPIMAP\Message $message */
        foreach($messages as $message) {
            $from = $message->getFrom()[0];
            $results[] = [
                'sender_name' => $from->personal ?? $from->mail,
                'sender_email' => $from->mail,
                'subject' => $message->getSubject(),
                'body' => clone $message->getTextBody() ?? $message->getHTMLBody(),
                'date' => clone $message->getDate()
            ];

            // Mark as read after fetching (uncomment for production)
            // $message->setFlag(['Seen']);
        }

        return $results;
    }
}
