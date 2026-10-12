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

    
public function quickApply(Request $request)
{
    $validated = $request->validate([
        'event_id' => ['required', 'integer', 'min:1'],
    ]);

    $accountId = session('user_id');

    if (!$accountId || !is_numeric($accountId)) {
        return response()->json([
            'success' => false,
            'message' => 'Please log in before applying.',
        ], 401);
    }

    $accountId = (int) $accountId;
    $eventId = (int) $validated['event_id'];

    $supabase = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        'Content-Type' => 'application/json',
    ])->baseUrl(rtrim(env('SUPABASE_URL'), '/') . '/rest/v1/');

    /*
    |--------------------------------------------------------------------------
    | 1. Verify the event and retrieve its NGO
    |--------------------------------------------------------------------------
    */

    $eventResponse = $supabase->get('volunteer_events', [
        'select' => 'id,name,ngo_id',
        'id' => 'eq.' . $eventId,
        'limit' => 1,
    ]);

    if (!$eventResponse->successful()) {
        \Illuminate\Support\Facades\Log::error(
            'Quick apply: event lookup failed.',
            ['response' => $eventResponse->body()]
        );

        return response()->json([
            'success' => false,
            'message' => 'Unable to verify the event.',
        ], 500);
    }

    $event = $eventResponse->json()[0] ?? null;

    if (!$event) {
        return response()->json([
            'success' => false,
            'message' => 'Event not found.',
        ], 404);
    }

    $ngoId = $event['ngo_id'] ?? null;

    if ($ngoId === null) {
        \Illuminate\Support\Facades\Log::error(
            'Quick apply: event has no NGO ID.',
            ['event_id' => $eventId]
        );

        return response()->json([
            'success' => false,
            'message' => 'The event is not linked to an organization.',
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Prevent duplicate applications
    |--------------------------------------------------------------------------
    */

    $existingResponse = $supabase->get('volunteer_applications', [
        'select' => 'id',
        'account_id' => 'eq.' . $accountId,
        'volunteer_event_id' => 'eq.' . $eventId,
        'limit' => 1,
    ]);

    if (!$existingResponse->successful()) {
        \Illuminate\Support\Facades\Log::error(
            'Quick apply: duplicate check failed.',
            ['response' => $existingResponse->body()]
        );

        return response()->json([
            'success' => false,
            'message' => 'Unable to check your existing application.',
        ], 500);
    }

    if (!empty($existingResponse->json())) {
        return response()->json([
            'success' => true,
            'already_applied' => true,
            'message' => 'You have already applied to this event.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Save the volunteer application
    |--------------------------------------------------------------------------
    */

    $applicationResponse = $supabase
        ->withHeaders(['Prefer' => 'return=representation'])
        ->post('volunteer_applications', [[
            'account_id' => $accountId,
            'volunteer_event_id' => $eventId,
            'application_date' => now()->toDateString(),
            'skills' => '',
            'remarks' => '',
            'status' => 0,
        ]]);

    if (!$applicationResponse->successful()) {
        \Illuminate\Support\Facades\Log::error(
            'Quick apply: application insert failed.',
            [
                'account_id' => $accountId,
                'event_id' => $eventId,
                'status' => $applicationResponse->status(),
                'response' => $applicationResponse->body(),
            ]
        );

        return response()->json([
            'success' => false,
            'message' => 'Unable to submit your application.',
        ], 500);
    }

    $application = $applicationResponse->json()[0] ?? null;
    $applicationId = $application['id'] ?? null;

    if (!$applicationId) {
        \Illuminate\Support\Facades\Log::error(
            'Quick apply: application was inserted but its ID was not returned.',
            ['response' => $applicationResponse->body()]
        );

        return response()->json([
            'success' => false,
            'application_saved' => true,
            'message' => 'Your application was saved, but its ID could not be retrieved. Please contact the administrator.',
        ], 500);
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Create an NGO notification
    |--------------------------------------------------------------------------
    |
    | reference_id = volunteer_applications.id
    | related_id   = the NGO that owns the event
    |
    */

    $eventName = $event['name'] ?? 'a volunteer event';

    $notificationResponse = $supabase->post('notifications', [[
        'title' => 'New Volunteer Application',
        'message' => 'A volunteer has applied for the event: '
            . $eventName . '.',
        'type' => 'Volunteer Application',
        'reference_id' => (int) $applicationId,
        'created_at' => now()->toIso8601String(),
        'related_id' => (int) $ngoId,
    ]]);

    if (!$notificationResponse->successful()) {
        \Illuminate\Support\Facades\Log::error(
            'Quick apply: application saved but notification insert failed.',
            [
                'application_id' => $applicationId,
                'event_id' => $eventId,
                'ngo_id' => $ngoId,
                'status' => $notificationResponse->status(),
                'response' => $notificationResponse->body(),
            ]
        );

        // Do not ask the volunteer to submit again:
        // the application has already been saved.
        return response()->json([
            'success' => true,
            'already_applied' => false,
            'application_saved' => true,
            'notification_saved' => false,
            'message' => 'Your application was submitted, but the organization notification could not be created.',
            'event_id' => $eventId,
            'application_id' => (int) $applicationId,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 5. Return success
    |--------------------------------------------------------------------------
    */

    return response()->json([
        'success' => true,
        'already_applied' => false,
        'application_saved' => true,
        'notification_saved' => true,
        'message' => 'Your application has been submitted successfully!',
        'event_id' => $eventId,
        'application_id' => (int) $applicationId,
    ]);
}


    
}