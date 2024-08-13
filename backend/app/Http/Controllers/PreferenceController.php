<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    public function setPreferences(Request $request)
    {
        $user = \auth()->user();
        //dd($user);
        $preferences = Preference::updateOrCreate(
            ['user_id' => $user->id],
            $request->only('diet', 'intolerances', 'allergies')
        );
        #dd($preferences);

        return response()->json($preferences);
    }

    public function getPreferences()
    {
        $user = auth()->user();
        $preferences = $user->preferences;

        return response()->json($preferences);
    }
}
