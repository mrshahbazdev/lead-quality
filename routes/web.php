<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\IcpProfileController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ProfileController;

// Auth routes (provided by Breeze)
require __DIR__.'/auth.php';

Route::get('lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Protected application routes
Route::middleware('auth')->group(function () {
    Route::get('/docs', function () {
        return view('docs');
    })->name('docs');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('contacts', ContactController::class);
    Route::post('contacts/import', [ContactController::class, 'import'])->name('contacts.import');
    Route::post('contacts/{contact}/activities', [ActivityController::class, 'store'])->name('contacts.activities.store');
    Route::post('contacts/{contact}/ai-analyze', [ContactController::class, 'analyzeAi'])->name('contacts.ai-analyze');

    Route::get('icp', [IcpProfileController::class, 'index'])->name('icp.index');
    Route::post('icp', [IcpProfileController::class, 'store'])->name('icp.store');

    Route::get('diagnostic', [\App\Http\Controllers\DiagnosticController::class, 'index'])->name('diagnostic.index');
    Route::post('diagnostic', [\App\Http\Controllers\DiagnosticController::class, 'store'])->name('diagnostic.store');

    Route::get('email-scanner', [\App\Http\Controllers\EmailDetectionController::class, 'index'])->name('email-scanner.index');
    Route::post('email-scanner/import', [\App\Http\Controllers\EmailDetectionController::class, 'store'])->name('email-scanner.store');

    Route::get('pipeline', [\App\Http\Controllers\PipelineController::class, 'index'])->name('pipeline.index');
    Route::post('pipeline/update-stage', [\App\Http\Controllers\PipelineController::class, 'updateStage'])->name('pipeline.update-stage');

    Route::get('teams', [\App\Http\Controllers\TeamController::class, 'index'])->name('teams.index');
    Route::post('teams', [\App\Http\Controllers\TeamController::class, 'store'])->name('teams.store');
    Route::post('teams/{team}/switch', [\App\Http\Controllers\TeamController::class, 'switchTeam'])->name('teams.switch');
    Route::post('teams/invite', [\App\Http\Controllers\TeamController::class, 'invite'])->name('teams.invite');
    Route::post('teams/update-openai-key', [\App\Http\Controllers\TeamController::class, 'updateOpenAIKey'])->name('teams.update-openai-key');
    Route::post('teams/update-groq-keys', [\App\Http\Controllers\TeamController::class, 'updateGroqKeys'])->name('teams.update-groq-keys');
    Route::post('teams/test-groq-keys', [\App\Http\Controllers\TeamController::class, 'testGroqKeys'])->name('teams.test-groq-keys');

    Route::post('email-accounts', [\App\Http\Controllers\EmailAccountController::class, 'store'])->name('email-accounts.store');
    Route::delete('email-accounts/{emailAccount}', [\App\Http\Controllers\EmailAccountController::class, 'destroy'])->name('email-accounts.destroy');

    Route::get('sequences', [\App\Http\Controllers\SequenceController::class, 'index'])->name('sequences.index');
    Route::post('sequences', [\App\Http\Controllers\SequenceController::class, 'store'])->name('sequences.store');
    Route::get('sequences/{sequence}', [\App\Http\Controllers\SequenceController::class, 'show'])->name('sequences.show');
    Route::post('sequences/{sequence}/steps', [\App\Http\Controllers\SequenceController::class, 'storeStep'])->name('sequences.steps.store');
    Route::post('sequences/enroll', [\App\Http\Controllers\SequenceController::class, 'enroll'])->name('sequences.enroll');

    Route::get('analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::post('user/api-token', function (\Illuminate\Http\Request $request) {
        // Delete old token if they just want one main token
        $request->user()->tokens()->where('name', 'chrome-extension')->delete();
        $token = $request->user()->createToken('chrome-extension')->plainTextToken;
        return redirect()->back()->with('success', 'Your API Token: ' . $token . ' (Copy this now, it won\'t be shown again)');
    })->name('user.api-token');
});
