<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class event_controller extends Controller
{

    private function getNgoIdOrNull()
    {
        return auth()->check()
            ? auth()->user()->ngo_id
            : session('ngo_id');
    }

    private function supabase()
    {
        return Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ]);
    }


   private function getNgoId()
    {
        $ngoId = auth()->check()
            ? auth()->user()->ngo_id
            : session('ngo_id');

        if (!$ngoId) {
            abort(403, 'NGO ID missing. Please re-login.');
        }

        return (int) $ngoId;
    }


    public function index(Request $request)
    {
        $ngoId = $this->getNgoIdOrNull();

        $query = [
            'select' => 'id,name,description,status,date,ngo_id',
            'order' => 'date.desc',
        ];

        // ONLY filter by NGO if logged in / session exists
        if ($ngoId) {
            $query['ngo_id'] = "eq.$ngoId";
        }

        // status filtering (keep this for both guest + admin)
        if ($request->filter === 'active') {
            $query['status'] = 'eq.1';
        } elseif ($request->filter === 'archived') {
            $query['status'] = 'eq.0';
        }

        $response = $this->supabase()
            ->get(env('SUPABASE_URL') . '/rest/v1/volunteer_events', $query);

        if ($response->failed()) {
            dd($response->body()); // better debugging than dumping service key
        }

        $events = collect($response->json());

        $activitiesResponse = $this->supabase()
            ->get(env('SUPABASE_URL') . '/rest/v1/volunteer_activities?select=*');

        $activities = collect($activitiesResponse->json());

        // attach activities to each event
        $events = $events->map(function ($event) use ($activities) {

            $event['activities'] = $activities
                ->where('volunteer_event_id', $event['id'])
                ->values()
                ->all();

            return $event;
        });

        return view('events', compact('events'));

        
    }

    public function deleteActivity($id)
{
    $this->supabase()->delete(
        env('SUPABASE_URL') . "/rest/v1/volunteer_activities?id=eq.$id"
    );

    return back()->with('success', 'Activity deleted.');
}

    public function store(Request $request)
{
    $ngoId = $this->getNgoId();

    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'date' => 'required|date',
        'activities' => 'required|array|min:1',
        'activities.*.name' => 'required',
        'activities.*.remarks' => 'required',
    ]);

        // BLOCK PAST DATES. pls don't remove this comment.
    if (Carbon::parse($request->date)->isPast()) {
        return back()->with('error', 'Event date cannot be in the past.');
    }

    // 1. CREATE EVENT
    $eventResponse = $this->supabase()->post(
        env('SUPABASE_URL') . '/rest/v1/volunteer_events',
        [
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'status' => 1,
            'ngo_id' => $ngoId,
        ]
    );

    // get created event
    $event = $eventResponse->json()[0] ?? null;

    if (!$event) {
        return back()->with('error', 'Failed to create event');
    }

    $eventId = $event['id'];

    // 2. INSERT ACTIVITIES
    foreach ($request->activities as $activity) {
        $this->supabase()->post(
            env('SUPABASE_URL') . '/rest/v1/volunteer_activities',
            [
                'volunteer_event_id' => $eventId,
                'name' => $activity['name'],
                'remarks' => $activity['remarks'],
            ]
        );
    }

    return back()->with('success', 'Event created successfully.');
}


public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'date' => 'required|date',
    ]);

    if (empty($request->activities) && empty($request->activities_delete)) {
    return back()->with('error', 'At least one activity is required.');
}

    // 1. Update event
    $this->supabase()->patch(
        env('SUPABASE_URL') . "/rest/v1/volunteer_events?id=eq.$id",
        [
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
        ]
    );

    // 2. DELETE removed activities
    if ($request->activities_delete) {
        foreach ($request->activities_delete as $activityId) {
            $this->supabase()->delete(
                env('SUPABASE_URL') . "/rest/v1/volunteer_activities?id=eq.$activityId"
            );
        }
    }

    // 3. UPDATE + INSERT activities
    if ($request->activities) {

        foreach ($request->activities as $activity) {

            // existing activity → update
            if (!empty($activity['id'])) {
                $this->supabase()->patch(
                    env('SUPABASE_URL') . "/rest/v1/volunteer_activities?id=eq." . $activity['id'],
                    [
                        'name' => $activity['name'],
                        'remarks' => $activity['remarks'],
                    ]
                );

            } 
            // new activity → insert
            else {
                $this->supabase()->post(
                    env('SUPABASE_URL') . "/rest/v1/volunteer_activities",
                    [
                        'volunteer_event_id' => $id,
                        'name' => $activity['name'],
                        'remarks' => $activity['remarks'],
                    ]
                );
            }
        }
    }

    return back()->with('success', 'Event updated successfully!');
}

    public function archive($id)
    {
        $this->supabase()->patch(
            env('SUPABASE_URL') . "/rest/v1/volunteer_events?id=eq.$id",
            [
                'status' => 0
            ]
        );

        return back()->with('success', 'Event archived.');
    }
   public function reactivate($id)
    {
        $this->supabase()->patch(
            env('SUPABASE_URL') . "/rest/v1/volunteer_events?id=eq.$id",
            [
                'status' => 1
            ]
        );

        return back()->with('success', 'Event reactivated.');
    }

   public function volunteerPage()
    {
        $ngoId = $this->getNgoIdOrNull();

        $url = env('SUPABASE_URL') . "/rest/v1/volunteer_events?status=eq.1";

        // only filter if logged in NGO exists
        if ($ngoId) {
            $url .= "&ngo_id=eq.$ngoId";
        }

        $events = collect(
            $this->supabase()->get($url)->json()
        );

        return view('volunteer_page', compact('events'));
    }

    public function assignments(Request $request)
    {
        $ngoId = $this->getNgoIdOrNull();

        // 1. Get events
        $events = collect(
            $this->supabase()->get(
                env('SUPABASE_URL') . '/rest/v1/volunteer_events',
                [
                    'select' => 'id,name,description,status,date',
                    'ngo_id' => $ngoId ? "eq.$ngoId" : null,
                    'status' => 'eq.1'
                ]
            )->json()
        );

        $assignments = collect(
            $this->supabase()->get(
                env('SUPABASE_URL') . "/rest/v1/volunteer_assignments",
                [
                    'select' => 'account_id, volunteer_activities(volunteer_event_id)'
                ]
            )->json()
        );

        $events = $events->map(function ($event) use ($assignments) {

            $uniqueCount = $assignments
                ->filter(function ($a) use ($event) {
                    return isset($a['volunteer_activities']['volunteer_event_id'])
                        && $a['volunteer_activities']['volunteer_event_id'] == $event['id'];
                })
                ->pluck('account_id')
                ->unique()
                ->count();

            $event['volunteer_count'] = $uniqueCount;

            return $event;
        });

        return view('assignments', compact('events'));
    }

    public function getActivities($eventId)
    {
        // 1. Get activities
        $activities = collect(
            $this->supabase()->get(
                env('SUPABASE_URL') . "/rest/v1/volunteer_activities",
                [
                    'select' => 'id,name,remarks',
                    'volunteer_event_id' => 'eq.' . $eventId
                ]
            )->json()
        );

        // 2. Get assignments WITH account info
        $assignments = collect(
            $this->supabase()->get(
                env('SUPABASE_URL') . "/rest/v1/volunteer_assignments",
                [
                    'select' => 'activity_id, accounts(first_name,last_name,email)'
                ]
            )->json()
        );

        // 3. Attach volunteers to each activity
        $activities = $activities->map(function ($act) use ($assignments) {

            $act['volunteer_assignments'] = $assignments
                ->filter(function ($a) use ($act) {
                    return $a['activity_id'] == $act['id'];
                })
                ->values();

            return $act;
        });

        return response()->json($activities);
    }

   
   
}