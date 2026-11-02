<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class track_activity_controller extends Controller
{
    public function index()
    {
        $headers = [
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ];

        $baseUrl = env('SUPABASE_URL');

        $assignmentsResponse = Http::withHeaders($headers)
            ->get($baseUrl . '/rest/v1/volunteer_assignments', [
                'select' => '*,accounts(id,first_name,last_name)',
            ]);


        $activitiesResponse = Http::withHeaders($headers)
            ->get($baseUrl . '/rest/v1/volunteer_activities', [
                'select' => 'id,name,volunteer_event_id',
            ]);

        $eventsResponse = Http::withHeaders($headers)
            ->get($baseUrl . '/rest/v1/volunteer_events', [
                'select' => 'id,name,date',
            ]);


        if ($assignmentsResponse->failed()) {
            dd('Assignments Error:', $assignmentsResponse->json());
        }

        if ($activitiesResponse->failed()) {
            dd('Activities Error:', $activitiesResponse->json());
        }

        if ($eventsResponse->failed()) {
            dd('Events Error:', $eventsResponse->json());
        }

        $assignments = collect(
            $assignmentsResponse->json() ?? []
        );

        $activities = collect(
            $activitiesResponse->json() ?? []
        )->keyBy('id');

        $events = collect(
            $eventsResponse->json() ?? []
        )->keyBy('id');

        $assignments = $assignments->map(function ($item) use (
            $activities,
            $events
        ) {

            $activity = $activities->get(
                $item['activity_id'] ?? null
            );

            $event = null;

            if ($activity) {

                $event = $events->get(
                    $activity['volunteer_event_id'] ?? null
                );

            }

            $item['activity'] = $activity;

            $item['event'] = $event;

            return $item;

        });

        $volunteers = $assignments
            ->pluck('accounts')
            ->filter()
            ->unique('id')
            ->values()
            ->all();


        return view('track_activity', [

            'assignments' => $assignments,

            'events' => $events
                ->values()
                ->all(),

            'activities' => $activities
                ->values()
                ->all(),

            'volunteers' => $volunteers,

        ]);
    }

    private function calculateTotalHours($timeIn, $timeOut)
    {
        if (!$timeIn || !$timeOut) {
            return null;
        }

        $timeInTimestamp = strtotime($timeIn);
        $timeOutTimestamp = strtotime($timeOut);

        if ($timeInTimestamp === false || $timeOutTimestamp === false) {
            return null;
        }

        
        if ($timeOutTimestamp < $timeInTimestamp) {
            $timeOutTimestamp += 24 * 60 * 60;
        }

        $seconds = $timeOutTimestamp - $timeInTimestamp;

    
        return round($seconds / 3600, 2);
    }

    public function store(Request $request)
    {
        $headers = [
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation',
        ];

        $baseUrl = env('SUPABASE_URL');

        $timeIn = $request->time_in ?: null;
        $timeOut = $request->time_out ?: null;


        $totalHours = $this->calculateTotalHours(
            $timeIn,
            $timeOut
        );


        if ($timeOut) {
            $status = 1; 
        } else {
            $status = 0; 
        }


        $response = Http::withHeaders($headers)
            ->post(
                $baseUrl . '/rest/v1/volunteer_assignments',
                [
                    'account_id' => $request->account_id,
                    'activity_id' => $request->activity_id,

                    'time_in' => $timeIn,
                    'time_out' => $timeOut,

                    'total_hours' => $totalHours,

                    'status' => $status,
                ]
            );


        if ($response->failed()) {
            dd('Store Activity Error:', $response->json());
        }


        return redirect('/track-activity');
    }


    public function update(Request $request)
    {
        $headers = [
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation',
        ];

        $baseUrl = env('SUPABASE_URL');

        $assignmentId = $request->assignment_id;


        $timeIn = $request->time_in ?: null;
        $timeOut = $request->time_out ?: null;


        $totalHours = $this->calculateTotalHours(
            $timeIn,
            $timeOut
        );


        if ($timeOut) {
            $status = 1; // Completed
        } else {
            $status = 0; // On Going
        }



        $response = Http::withHeaders($headers)
            ->patch(
                $baseUrl .
                '/rest/v1/volunteer_assignments?id=eq.' .
                $assignmentId,

                [
                    'account_id' => $request->account_id,
                    'activity_id' => $request->activity_id,

                    'time_in' => $timeIn,
                    'time_out' => $timeOut,

                    'total_hours' => $totalHours,

                    'status' => $status,
                ]
            );


        if ($response->failed()) {
            dd('Update Activity Error:', $response->json());
        }


        return redirect('/track-activity');
    }
}