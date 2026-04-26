<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class volunteer_application_controller extends Controller
{
    
    public function showForm(Request $request)
    {
        $accountId = session('user_id'); 
        $eventId = $request->event_id;

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
        $accountId = session('user_id'); 
        $eventId = $request->event_id;

        $url = env('SUPABASE_URL') . '/rest/v1/volunteer_applications';

        Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json'
        ])->post($url, [
            'account_id' => $accountId,
            'event_id' => $eventId,

            'availability' => $request->availability,
            'skills' => $request->skills,
            'interests' => $request->interests,
            'has_experience' => $request->has_experience,
            'experience_details' => $request->experience_details,
            'remarks' => $request->remarks,

            'status' => 'pending' // default
        ]);

        return redirect('/volunteer/dashboard')
            ->with('success', 'Application submitted successfully!');
    }
}