<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class track_activity_controller extends Controller
{
    public function index()
    {
        // Assignments
        $assignmentsResponse = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/volunteer_assignments', [
            'select' => '*,accounts(first_name,last_name),activity_id'
        ]);

        // Activities
        $activitiesResponse = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/volunteer_activities', [
            'select' => 'id,name,volunteer_event_id'
        ]);

        // Events
        $eventsResponse = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/volunteer_events', [
            'select' => 'id,name'
        ]);

        $assignments = collect($assignmentsResponse->json());
        $activities = collect($activitiesResponse->json())->keyBy('id');
        $events = collect($eventsResponse->json())->keyBy('id');

        // 🔥 ATTACH RELATIONSHIP PROPERLY
        $assignments = $assignments->map(function ($item) use ($activities, $events) {

            $activity = $activities[$item['activity_id']] ?? null;

            $event = $activity
                ? ($events[$activity['volunteer_event_id']] ?? null)
                : null;

            $item['activity'] = $activity;
            $item['event'] = $event;

            return $item;
        });

        return view('track_activity', [
            'assignments' => $assignments,
            'events' => $eventsResponse->json()
        ]);
    }
}