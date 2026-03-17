<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\LeadScoreEngine;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $scoreEngine;

    public function __construct(LeadScoreEngine $scoreEngine)
    {
        $this->scoreEngine = $scoreEngine;
    }

    public function index()
    {
        $teamId = auth()->user()->current_team_id;
        if (!$teamId) {
            return view('contacts.index', ['contacts' => collect(), 'noWorkspace' => true]);
        }

        $contacts = Contact::where('team_id', $teamId)->latest()->get();
        
        $contacts->each(function ($contact) {
            $contact->analysis = $this->scoreEngine->calculateScore($contact);
        });

        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        if (!auth()->user()->current_team_id) {
            return redirect()->route('contacts.index')->with('error', __('Please create or select a Workspace first.'));
        }
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $teamId = auth()->user()->current_team_id;
        if (!$teamId) {
            return redirect()->back()->with('error', __('Please create or select a Workspace first.'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
        ]);

        $contact = Contact::create(array_merge($validated, [
            'user_id' => auth()->id(),
            'team_id' => auth()->user()->current_team_id,
            'source' => 'Manual Entry'
        ]));

        return redirect()->route('contacts.index')->with('success', __('Contact created successfully!'));
    }

    public function show(Contact $contact, \App\Services\TemplateService $templateService, \App\Services\AiLeadService $aiService)
    {
        $contact->analysis = $this->scoreEngine->calculateScore($contact);
        $contact->load('activities');

        $templates = \App\Models\OutreachTemplate::where('team_id', auth()->user()->current_team_id)
            ->orWhereNull('team_id')
            ->get()
            ->map(function ($template) use ($contact, $templateService) {
            $template->merged_content = $templateService->merge($template->content, $contact);
            return $template;
        });

        $aiInsights = session('ai_insights', []);
        if (empty($aiInsights)) {
            $aiInsights = $aiService->getSmartInsights($contact);
        }
        
        $sequences = \App\Models\Sequence::where('team_id', auth()->user()->current_team_id)
            ->where('is_active', true)
            ->get();
        
        return view('contacts.show', compact('contact', 'templates', 'aiInsights', 'sequences'));
    }

    public function analyzeAi(Contact $contact, \App\Services\AiLeadScoringService $aiScoringService)
    {
        $analysis = $aiScoringService->analyze($contact);
        
        $contact->update([
            'ai_high_probability' => $analysis['high_probability']
        ]);

        return redirect()->route('contacts.show', $contact)
            ->with('success', __('AI Analysis Complete!'))
            ->with('ai_insights', $analysis['insights']);
    }

    public function import(Request $request, \App\Services\CsvImportService $importService)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $results = $importService->import(
            $path, 
            auth()->id(), 
            auth()->user()->current_team_id
        );

        if (!empty($results['errors'])) {
            return redirect()->route('contacts.index')
                ->with('error', __('Import partially failed: ') . implode(', ', array_slice($results['errors'], 0, 3)));
        }

        return redirect()->route('contacts.index')->with('success', __('Imported :count contacts successfully!', ['count' => $results['success']]));
    }
}
