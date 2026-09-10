<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class service_management_controller extends Controller
{
    private function supabaseRequest($endpoint, $params = [])
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(
            env('SUPABASE_URL') . '/rest/v1/' . $endpoint,
            $params
        );

        return $response->json();
    }

    public function volunteers(Request $request)
{
    // =========================================================
    // GET ACTIVE VOLUNTEERS
    // =========================================================

    $data = $this->supabaseRequest('volunteer_applications', [
        'select' =>
            'id,volunteer_event_id,account_id,application_date,skills,remarks,status,' .
            'accounts!inner(first_name,last_name,email,roles,contact_number,address,birth_date,status),' .
            'volunteer_events!inner(id,name,date)',

        'status' => 'eq.1',
        'accounts.status' => 'eq.1'
    ]);

    $volunteers = collect($data);


    // =========================================================
    // GET EVENT HISTORY
    // =========================================================

    $historyData = $this->supabaseRequest('volunteer_applications', [
        'select' =>
            'id,volunteer_event_id,account_id,application_date,status,' .
            'accounts!inner(status),' .
            'volunteer_events!inner(id,name,date)',

        'accounts.status' => 'eq.1'
    ]);


    // =========================================================
    // GROUP EVENT HISTORY BY ACCOUNT ID
    // =========================================================

    $eventHistory = collect($historyData)
        ->filter(function ($item) {

            return is_array($item)
                && !empty($item['account_id']);

        })

        ->groupBy(function ($item) {

            return (string) $item['account_id'];

        })

        ->map(function ($applications) {

            return $applications

                ->map(function ($item) {

                    $event = $item['volunteer_events'] ?? [];

                    if (!is_array($event)) {
                        $event = [];
                    }

                    return [

                        'id' =>
                            $item['id'] ?? null,

                        'account_id' =>
                            $item['account_id'] ?? null,

                        'event_name' =>
                            $event['name'] ?? '---',

                        'event_date' =>
                            $event['date'] ?? '---',

                        'application_date' =>
                            $item['application_date'] ?? '---',

                        'status' =>
                            $item['status'] ?? 0,
                    ];
                })

                ->sortByDesc(function ($item) {

                    return $item['application_date'] ?? '';

                })

                ->values()

                ->toArray();
        });


    // =========================================================
    // GET UNIQUE SKILLS
    // =========================================================

    $skills = $volunteers

        ->pluck('skills')

        ->filter()

        ->flatMap(function ($item) {

            return explode(',', $item);

        })

        ->map(function ($skill) {

            return trim($skill);

        })

        ->filter()

        ->unique()

        ->sort()

        ->values();


    // =========================================================
    // SEARCH BY NAME OR EMAIL
    // =========================================================

    if ($request->filled('search')) {

        $search = strtolower(
            trim($request->search)
        );

        $volunteers = $volunteers->filter(
            function ($item) use ($search) {

                if (!is_array($item)) {
                    return false;
                }

                $account =
                    $item['accounts'] ?? [];

                if (!is_array($account)) {
                    return false;
                }

                $firstName =
                    strtolower(
                        $account['first_name'] ?? ''
                    );

                $lastName =
                    strtolower(
                        $account['last_name'] ?? ''
                    );

                $email =
                    strtolower(
                        $account['email'] ?? ''
                    );

                return str_contains(
                    $firstName,
                    $search
                )
                ||
                str_contains(
                    $lastName,
                    $search
                )
                ||
                str_contains(
                    $email,
                    $search
                );
            }
        );
    }


    // =========================================================
    // FILTER BY SKILL
    // =========================================================

    $skillFilter =
        strtolower(
            trim(
                $request->search_skill ?? ''
            )
        );

    if ($skillFilter) {

        $volunteers = $volunteers->filter(
            function ($item) use ($skillFilter) {

                if (!is_array($item)) {
                    return false;
                }

                $skillsString =
                    strtolower(
                        $item['skills'] ?? ''
                    );

                $skillArray =
                    array_map(
                        'trim',
                        explode(
                            ',',
                            $skillsString
                        )
                    );

                return in_array(
                    $skillFilter,
                    $skillArray
                );
            }
        );
    }


    // =========================================================
    // GROUP VOLUNTEERS BY ACCOUNT
    // =========================================================

    $volunteers = $volunteers

        ->groupBy(function ($item) {

            return (string) (
                $item['account_id'] ?? ''
            );

        })

        ->map(function ($applications) use ($eventHistory) {

            // Get the first application
            // for displaying the volunteer card.

            $item =
                $applications->first();


            // =================================================
            // ACCOUNT
            // =================================================

            $account =
                $item['accounts'] ?? [];

            if (!is_array($account)) {
                $account = [];
            }


            // =================================================
            // ACCOUNT ID
            // =================================================

            $accountId =
                $item['account_id'] ?? null;


            // =================================================
            // CURRENT EVENT
            // =================================================

            $event =
                $item['volunteer_events'] ?? [];

            if (!is_array($event)) {
                $event = [];
            }


            // =================================================
            // GET ONLY THIS VOLUNTEER'S EVENT HISTORY
            // =================================================

            $history =
                $eventHistory->get(
                    (string) $accountId,
                    []
                );


            // =================================================
            // RETURN VOLUNTEER OBJECT
            // =================================================

            return (object) [

                'application_id' =>
                    $item['id'] ?? null,

                'volunteer_event_id' =>
                    $item['volunteer_event_id'] ?? null,

                'account_id' =>
                    $accountId,

                'application_date' =>
                    $item['application_date'] ?? '',

                'event_name' =>
                    $event['name'] ?? '',

                'event_date' =>
                    $event['date'] ?? '',

                'skills' =>
                    $item['skills'] ?? '',

                'remarks' =>
                    $item['remarks'] ?? '',

                'first_name' =>
                    $account['first_name'] ?? '',

                'last_name' =>
                    $account['last_name'] ?? '',

                'email' =>
                    $account['email'] ?? '',

                'roles' =>
                    $account['roles'] ?? '',

                'contact_number' =>
                    $account['contact_number'] ?? 'N/A',

                'address' =>
                    $account['address'] ?? '',

                'birth_date' =>
                    $account['birth_date'] ?? '',

                'event_history' =>
                    $history,
            ];
        })

        ->values();


    // =========================================================
    // RETURN VIEW
    // =========================================================

    return view(
        'service_management',
        compact(
            'volunteers',
            'skills'
        )
    );
}

    public function deactivate($id)
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ])->patch(
            env('SUPABASE_URL') . "/rest/v1/accounts?id=eq.$id",
            [
                'status' => 0
            ]
        );

        return response()->json([
            'success' => $response->successful(),
            'data' => $response->json()
        ]);
    }

    public function store(Request $request)
{
    try {

        $activityId = $request->activity_id;
        $accountId = $request->account_id;
        $eventId = $request->event_id;

        if (!$activityId || !$accountId || !$eventId) {
            return response()->json([
                'message' => 'Activity, volunteer, and event are required.'
            ], 400);
        }


        // =====================================================
        // VERIFY THAT THE VOLUNTEER APPLIED AND WAS APPROVED
        // FOR THIS EVENT
        // =====================================================

        $application = $this->supabaseRequest('volunteer_applications', [
            'select' => 'id,account_id,volunteer_event_id,status',
            'account_id' => 'eq.' . $accountId,
            'volunteer_event_id' => 'eq.' . $eventId,
            'status' => 'eq.1'
        ]);

        if (empty($application)) {
            return response()->json([
                'message' => 'This volunteer is not an approved applicant for this event.'
            ], 403);
        }


        // =====================================================
        // CHECK IF ALREADY ASSIGNED TO THIS ACTIVITY
        // =====================================================

        $existing = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(
            env('SUPABASE_URL') . '/rest/v1/volunteer_assignments',
            [
                'select' => 'id',
                'activity_id' => 'eq.' . $activityId,
                'account_id' => 'eq.' . $accountId
            ]
        )->json();

        if (!empty($existing)) {
            return response()->json([
                'message' => 'This volunteer is already assigned to this activity.'
            ], 409);
        }


        // =====================================================
        // GET EVENT DATE
        // =====================================================

        $event = $this->supabaseRequest('volunteer_events', [
            'select' => 'id,date',
            'id' => 'eq.' . $eventId
        ]);

        $eventDate = null;

        if (!empty($event)) {
            $eventDate = $event[0]['date'] ?? null;
        }


        // =====================================================
        // CREATE ASSIGNMENT
        // =====================================================

        $insert = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ])->post(
            env('SUPABASE_URL') . '/rest/v1/volunteer_assignments',
            [
                'account_id' => $accountId,
                'activity_id' => $activityId,
                'date' => $eventDate,
                'status' => 1
            ]
        );


        if ($insert->failed()) {
            return response()->json([
                'message' => 'Failed to assign volunteer.',
                'error' => $insert->json(),
            ], $insert->status());
        }


        return response()->json([
            'success' => true,
            'message' => 'Volunteer assigned successfully.',
            'data' => $insert->json()
        ]);


    } catch (\Exception $e) {

        return response()->json([
            'message' => 'Assignment failed.',
            'error' => $e->getMessage()
        ], 500);
    }
}
  public function getVolunteers(Request $request)
{
    $eventId = $request->query('event_id');

    if (!$eventId) {
        return response()->json([
            'message' => 'Event ID is required.'
        ], 400);
    }

    $data = $this->supabaseRequest('volunteer_applications', [
        'select' =>
            'id,volunteer_event_id,account_id,status,' .
            'accounts!inner(first_name,last_name,email,status)',

        // ONLY applications for the selected event
        'volunteer_event_id' => 'eq.' . $eventId,

        // ONLY approved applications
        'status' => 'eq.1',

        // ONLY active accounts
        'accounts.status' => 'eq.1'
    ]);

    $volunteers = collect($data)
        ->map(function ($item) {

            $account = $item['accounts'] ?? [];

            return [
                'id' => $item['account_id'] ?? null,

                'name' =>
                    ($account['first_name'] ?? '') .
                    ' ' .
                    ($account['last_name'] ?? ''),

                'email' =>
                    $account['email'] ?? '',
            ];
        })
        ->filter(function ($volunteer) {
            return !empty($volunteer['id']);
        })
        ->unique('id')
        ->values();

    return response()->json($volunteers);
}

    public function trackActivity()
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(
            env('SUPABASE_URL') . '/rest/v1/volunteer_assignments',
            [
                'select' =>
                    '*,accounts(first_name,last_name),activities(name)',
            ]
        );

        $assignments = $response->json();

        return view(
            'track-activity',
            compact('assignments')
        );
    }

    public function index(Request $request)
    {
        $query = Volunteer::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'first_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'last_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $volunteers = $query->get();

        return view(
            'service-management',
            compact('volunteers')
        );
    }

    public function destroy($id)
    {
        $url =
            env('SUPABASE_URL') .
            "/rest/v1/volunteer_assignments?id=eq.$id";

        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ])->delete($url);

        if ($response->failed()) {

            return response()->json([
                'message' => 'Supabase delete failed',
                'status' => $response->status(),
                'error' => $response->json(),
                'url_used' => $url
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}