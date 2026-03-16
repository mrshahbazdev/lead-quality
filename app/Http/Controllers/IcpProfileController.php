<?php

namespace App\Http\Controllers;

use App\Models\IcpProfile;
use Illuminate\Http\Request;

class IcpProfileController extends Controller
{
    public function index()
    {
        $profile = IcpProfile::where('user_id', auth()->id())->first() ?? new IcpProfile();
        return view('icp.index', compact('profile'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'industry' => 'nullable|string|max:255',
            'employee_count_range' => 'nullable|string|max:255',
            'budget_min' => 'nullable|numeric',
            'budget_max' => 'nullable|numeric',
            'role' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        IcpProfile::updateOrCreate(['user_id' => auth()->id()], $validated);

        return redirect()->route('icp.index')->with('success', __('ICP Profile updated!'));
    }
}
