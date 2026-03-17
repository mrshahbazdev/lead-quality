<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\Log;

class CsvImportService
{
    protected $scoreEngine;

    public function __construct(LeadScoreEngine $scoreEngine)
    {
        $this->scoreEngine = $scoreEngine;
    }

    /**
     * Import contacts from a CSV file.
     *
     * @param string $filePath
     * @param int $userId
     * @param int $teamId
     * @return array
     */
    public function import(string $filePath, int $userId, int $teamId): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        if (!file_exists($filePath) || !is_readable($filePath)) {
            $results['errors'][] = "File not found or not readable: $filePath";
            return $results;
        }

        $header = null;
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = array_map('strtolower', $row);
                    // Check for required "name" column
                    if (!in_array('name', $header)) {
                        $results['errors'][] = "Missing 'name' column in CSV header.";
                        fclose($handle);
                        return $results;
                    }
                    continue;
                }

                // Handle rows with different column counts than header
                if (count($header) !== count($row)) {
                    $results['failed']++;
                    $results['errors'][] = "Skipped row due to column count mismatch.";
                    continue;
                }

                $data = array_combine($header, $row);

                try {
                    $contact = Contact::create([
                        'user_id'  => $userId,
                        'team_id'  => $teamId,
                        'name'     => $data['name'] ?? 'Unknown',
                        'company'  => $data['company'] ?? null,
                        'position' => $data['position'] ?? null,
                        'industry' => $data['industry'] ?? null,
                        'role'     => $data['role'] ?? null,
                        'source'   => $data['source'] ?? 'CSV Import',
                        'budget'   => isset($data['budget']) && is_numeric($data['budget']) ? (float)$data['budget'] : null,
                        'status'   => 'new',
                        'notes'    => $data['notes'] ?? null,
                        'tags'     => isset($data['tags']) ? explode(',', $data['tags']) : [],
                    ]);

                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Row error: " . $e->getMessage();
                    Log::error("CSV Import Error: " . $e->getMessage());
                }
            }
            fclose($handle);
        }

        return $results;
    }
}
