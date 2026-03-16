<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    public function index()
    {
        return view('diagnostic.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'q1' => 'required|numeric',
            'q2' => 'required|numeric',
            'q3' => 'required|numeric',
            'q4' => 'required|numeric',
            'q5' => 'required|numeric',
            'q6' => 'required|numeric',
            'q7' => 'required|numeric',
            'q8' => 'required|numeric',
            'q9' => 'required|numeric',
            'q10' => 'required|numeric',
        ]);

        $score = array_sum($validated);
        // Score is out of 100 (10 questions x 10 points)
        
        $risk = __('Low');
        $riskClass = 'badge-good';
        if ($score < 50) {
            $risk = __('High Revenue Risk due to Lead Quality');
            $riskClass = 'badge-bad';
        } elseif ($score < 80) {
            $risk = __('Moderate Risk - Process Gaps Detected');
            $riskClass = 'badge-avg';
        }

        return view('diagnostic.result', compact('score', 'risk', 'riskClass'));
    }
}
