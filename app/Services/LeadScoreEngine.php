<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\IcpProfile;

class LeadScoreEngine
{
    /**
     * Calculate the fit score for a contact based on an ICP profile.
     *
     * @param Contact $contact
     * @param IcpProfile|null $profile
     * @return array
     */
    public function calculateScore(Contact $contact, IcpProfile $profile = null): array
    {
        if (!$profile) {
            $profile = IcpProfile::first();
        }

        if (!$profile) {
            return [
                'total_score' => 0,
                'metrics' => [],
                'status' => '🔴 Bad Lead',
                'issues' => ['No ICP profile defined'],
            ];
        }

        $scores = [
            'industry' => 0,
            'company_size' => 0,
            'budget' => 0,
            'role' => 0,
            'problem_fit' => 10, // Default base score for relevance
        ];

        $issues = [];

        // 1. Industry Match (25 points)
        if ($contact->industry && $profile->industry) {
            if (stripos($profile->industry, $contact->industry) !== false || stripos($contact->industry, $profile->industry) !== false) {
                $scores['industry'] = 25;
            } else {
                $issues[] = 'Industry mismatch';
            }
        }

        // 2. Company Size Match (20 points)
        if ($contact->employee_count_range && $profile->employee_count_range) {
            if ($contact->employee_count_range === $profile->employee_count_range) {
                $scores['company_size'] = 20;
            } else {
                $issues[] = 'Company size outside target range';
            }
        }

        // 3. Budget Match (25 points)
        if ($contact->budget && ($profile->budget_min || $profile->budget_max)) {
            $min = $profile->budget_min ?? 0;
            $max = $profile->budget_max ?? PHP_INT_MAX;

            if ($contact->budget >= $min && $contact->budget <= $max) {
                $scores['budget'] = 25;
            } else {
                $issues[] = 'Budget outside target range';
            }
        }

        // 4. Decision Maker (20 points)
        if ($contact->role && $profile->role) {
            $roles = array_map('trim', explode(',', strtolower($profile->role)));
            if (in_array(strtolower($contact->role), $roles)) {
                $scores['role'] = 20;
            } else {
                $issues[] = 'Non-decision maker role';
            }
        }

        $totalScore = array_sum($scores);

        $status = __('🔴 Bad Lead');
        if ($totalScore >= 70) {
            $status = __('🟢 Good Lead');
        } elseif ($totalScore >= 40) {
            $status = __('🟡 Average Lead');
        }

        return [
            'total_score' => $totalScore,
            'metrics' => $scores,
            'status' => $status,
            'issues' => array_map(function($issue) { return __($issue); }, $issues),
        ];
    }
}
