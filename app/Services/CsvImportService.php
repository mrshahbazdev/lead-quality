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
     * @return array
     */
    public function import(string $filePath): array
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
                    continue;
                }

                $data = array_combine($header, $row);

                try {
                    $contact = Contact::create([
                        'name'     => $data['name'] ?? 'Unknown',
                        'company'  => $data['company'] ?? null,
                        'position' => $data['position'] ?? null,
                        'industry' => $data['industry'] ?? null,
                        'role'     => $data['role'] ?? null,
                        'source'   => $data['source'] ?? 'CSV Import',
                        'budget'   => isset($data['budget']) ? (float)$data['budget'] : null,
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
