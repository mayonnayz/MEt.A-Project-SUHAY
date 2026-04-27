<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class volunteer_application_controller extends Controller
{
    
    public function showForm(Request $request)
    {
        $accountId = session('user_id');

        if (!$accountId) {
            return redirect('/login-page');
        }

        $eventId = $request->input('event_id');

        // Fetch user info from accounts table
        $url = env('SUPABASE_URL') . '/rest/v1/accounts?id=eq.' . $accountId;

        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json'
        ])->get($url);

        $data = $response->json();
        $user = $data[0] ?? null;

        return view('volunteer_application_form', compact('user', 'eventId'));
    }


    public function submit_application(Request $request)
    {
        $request->validate([
            'event_id' => 'required',
            'skills' => 'nullable|array',
            'interests' => 'nullable|array',
            'has_experience' => 'required',
            'remarks' => 'required'
        ]);    
    
        $accountId = session('user_id');
        $eventId = $request->input('event_id');

        $skills = $request->skills ? implode(', ', $request->skills) : null;
        $interests = $request->interests ? implode(', ', $request->interests) : null;

        $hasExperience = $request->has_experience === 'Yes' ? 1 : 0;

        $url = env('SUPABASE_URL') . '/rest/v1/volunteer_applications';

        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ])->post($url, [
            'account_id' => $accountId,
            'volunteer_event_id' => $eventId,
            'application_date' => now()->toDateString(),
            'availability' => $request->availability,
            'skills' => $skills,
            'interests' => $interests,
            'has_experience' => $hasExperience,
            'experience_details' => $request->experience_details,
            'remarks' => $request->remarks,
            'status' => 2
        ]);

        if ($response->failed()) {
            dd($response->body());
        }
        
        if ($response->failed()) {
            return back()->with('error', 'Failed to submit application.');
        }

        return redirect('/volunteer/events')
            ->with('success', 'Application submitted!');
    }
    
}