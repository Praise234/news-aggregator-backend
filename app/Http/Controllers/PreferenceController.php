<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preferences;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    /**
     * Save or update user preferences.
     */
    public function store(Request $request)
    {
        // Validate inputs
        $data = $request->validate([
            'categories' => 'nullable|array',
            'sources' => 'nullable|array',
            'authors' => 'nullable|array',
        ]);

        // Encode arrays as JSON for storage
        $data['categories'] = json_encode($data['categories']);
        $data['sources'] = json_encode($data['sources']);
        $data['authors'] = json_encode($data['authors']);

        // Save or update preferences for the authenticated user
        $preference = Preferences::updateOrCreate(
            ['user_id' => Auth::id()], // Search criteria
            $data // Data to update
        );

        return response()->json([
            'message' => 'Preferences saved successfully.',
            'preferences' => $preference,
        ]);
    }

    public function show()
    {
        // Retrieve preferences for the authenticated user
        $preference = Preferences::where('user_id', Auth::id())->first();

        return response()->json([
            'preferences' => $preference,
        ]);
    }

}
