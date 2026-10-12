<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class application_controller extends Controller
{
    private function supabaseRequest($endpoint, $params = [])
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ])->get(
            env('SUPABASE_URL') . '/rest/v1/' . $endpoint,
            $params
        );

        if ($response->failed()) {
            dd([
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response->json();
    }

   
public function applications()
{
    $data = $this->supabaseRequest('volunteer_applications', [
        'select' =>
            'id,volunteer_event_id,account_id,application_date,skills,remarks,status,' .
            'accounts!inner(first_name,last_name,email,address,contact_number,birth_date),' .
            'volunteer_events!inner(id,name,date)',
    ]);

    $applications = collect($data)
        ->map(function ($item) {
            $account = $item['accounts'] ?? [];
            $event = $item['volunteer_events'] ?? [];

            return [
                'id' => $item['id'] ?? null,
                'volunteer_event_id' => $item['volunteer_event_id'] ?? null,
                'event_name' => $event['name'] ?? '',
                'event_date' => $event['date'] ?? '',
                'account_id' => $item['account_id'] ?? null,
                'application_date' => $item['application_date'] ?? '',
                'skills' => $item['skills'] ?? '',
                'remarks' => $item['remarks'] ?? '',
                'status' => $item['status'] ?? 0,
                'first_name' => $account['first_name'] ?? '',
                'last_name' => $account['last_name'] ?? '',
                'email' => $account['email'] ?? '',
                'address' => $account['address'] ?? '',
                'contact_number' => $account['contact_number'] ?? '',
                'birth_date' => $account['birth_date'] ?? '',
            ];
        });

    $skills = $applications
        ->pluck('skills')
        ->filter()
        ->flatMap(fn ($item) => explode(',', $item))
        ->map(fn ($skill) => trim($skill))
        ->filter()
        ->unique()
        ->sort()
        ->values();

    $events = $applications
        ->map(fn ($item) => [
            'id' => $item['volunteer_event_id'],
            'name' => $item['event_name'],
            'event_date' => $item['event_date'],
        ])
        ->filter(fn ($event) => !empty($event['name']))
        ->unique('id')
        ->sortBy('name')
        ->values();

    $applications = $applications
        ->sortBy(fn ($item) => strtolower(
            ($item['first_name'] ?? '') . ' ' .
            ($item['last_name'] ?? '')
        ))
        ->values();

    return view('applications', compact(
        'applications',
        'skills',
        'events'
    ));
}


    public function approveApplication($id)
    {
        return $this->updateStatus($id, 1);
    }

    public function rejectApplication($id)
    {
        return $this->updateStatus($id, 2);
    }

    public function restoreApplication($id)
    {
        return $this->updateStatus($id, 0);
    }

    public function archiveApplication($id)
    {
        return $this->updateStatus($id, 3);
    }

    

private function updateStatus($id, $status)
{
    $baseUrl = rtrim(env('SUPABASE_URL'), '/') . '/rest/v1/';
    $serviceKey = env('SUPABASE_SERVICE_KEY');

    if (empty($baseUrl) || empty($serviceKey)) {
        Log::error('Supabase configuration is missing.');

        return response()->json([
            'success' => false,
            'message' => 'Supabase configuration is missing.',
        ], 500);
    }

    $supabase = Http::withHeaders([
        'apikey' => $serviceKey,
        'Authorization' => 'Bearer ' . $serviceKey,
        'Content-Type' => 'application/json',
    ]);

    // 1. Retrieve the application and applicant.
    $applicationResponse = $supabase->get(
        $baseUrl . 'volunteer_applications',
        [
            'select' => 'id,account_id,volunteer_events(name)',
            'id' => 'eq.' . (int) $id,
            'limit' => 1,
        ]
    );

    if (!$applicationResponse->successful()) {
        Log::error('Application lookup failed.', [
            'application_id' => $id,
            'status' => $applicationResponse->status(),
            'body' => $applicationResponse->body(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Unable to retrieve the application.',
        ], 500);
    }

    $application = $applicationResponse->json()[0] ?? null;

    if (!$application) {
        return response()->json([
            'success' => false,
            'message' => 'Application not found.',
        ], 404);
    }

    $accountId = $application['account_id'] ?? null;

    if (!$accountId) {
        return response()->json([
            'success' => false,
            'message' => 'Applicant account not found.',
        ], 422);
    }

    // 2. Update the application status.
    $updateResponse = $supabase->patch(
        $baseUrl . 'volunteer_applications?id=eq.' . (int) $id,
        ['status' => (int) $status]
    );

    if (!$updateResponse->successful()) {
        Log::error('Application status update failed.', [
            'application_id' => $id,
            'status' => $updateResponse->status(),
            'body' => $updateResponse->body(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to update application status.',
        ], 500);
    }

    // Pending = 0, Approved = 1, Rejected = 2, Archived = 3.
    // Only approval and rejection create applicant notifications.
    if (!in_array((int) $status, [1, 2], true)) {
        return response()->json([
            'success' => true,
            'notification_sent' => false,
            'message' => 'Application status updated successfully.',
        ]);
    }

    $eventName = $application['volunteer_events']['name']
        ?? 'the event';

    if ((int) $status === 1) {
        $title = 'Volunteer Application Approved';
        $message = "Your application for {$eventName} has been approved.";
    } else {
        $title = 'Volunteer Application Rejected';
        $message = "Your application for {$eventName} has been rejected.";
    }

    // 3. Create the notification.
    $notificationResponse = $supabase
    ->withHeaders([
        'Prefer' => 'return=representation',
    ])
    ->post($baseUrl . 'notifications', [
        'title' => $title,
        'message' => $message,
        'type' => 'Volunteer Application',
        'reference_id' => (int) $id,
        'created_at' => now()->toIso8601String(),
        'related_id' => null,
    ]);
    
    if (!$notificationResponse->successful()) {
        Log::error('Failed to insert application notification.', [
            'application_id' => $id,
            'account_id' => $accountId,
            'payload' => [
                'title' => $title,
                'message' => $message,
                'type' => 'Volunteer Application',
                'reference_id' => (int) $id,
                'related_id' => null,
            ],
            'http_status' => $notificationResponse->status(),
            'response_body' => $notificationResponse->body(),
        ]);

        return response()->json([
            'success' => false,
            'application_updated' => true,
            'notification_sent' => false,
            'message' => 'Application status updated, but notification creation failed.',
        ], 500);
    }

    $notificationData = $notificationResponse->json();
    $notificationId = $notificationData[0]['id']
        ?? $notificationData['id']
        ?? null;

    if (!$notificationId) {
        Log::error('Notification ID missing from Supabase response.', [
            'application_id' => $id,
            'response_body' => $notificationResponse->body(),
        ]);

        return response()->json([
            'success' => false,
            'application_updated' => true,
            'notification_sent' => false,
            'message' => 'Notification creation could not be confirmed.',
        ], 500);
    }

    // 4. Link the notification to the applicant.
    $statusResponse = $supabase
        ->withHeaders([
            'Prefer' => 'return=representation',
        ])
        ->post($baseUrl . 'notification_status', [
            'user_id' => (int) $accountId,
            'notification_id' => (int) $notificationId,
            'is_read' => 0,
        ]);

    if (!$statusResponse->successful()) {
        Log::error('Failed to associate notification with applicant.', [
            'notification_id' => $notificationId,
            'account_id' => $accountId,
            'http_status' => $statusResponse->status(),
            'response_body' => $statusResponse->body(),
        ]);

        return response()->json([
            'success' => false,
            'application_updated' => true,
            'notification_created' => true,
            'notification_sent' => false,
            'message' => 'Notification was created, but linking it to the applicant failed.',
        ], 500);
    }

    // 5. Both inserts succeeded.
    Log::info('Application notification created successfully.', [
        'application_id' => $id,
        'account_id' => $accountId,
        'notification_id' => $notificationId,
        'decision' => (int) $status,
    ]);

    return response()->json([
        'success' => true,
        'notification_sent' => true,
        'notification_id' => (int) $notificationId,
        'message' => 'Application status updated and applicant notified.',
    ]);
}



}