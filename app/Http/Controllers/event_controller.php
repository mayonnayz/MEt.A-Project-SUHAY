<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class event_controller extends Controller
{
    private function supabase()
    {
        return Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Get NGO ID safely (works even without Laravel auth)
     */
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
    $ngoId = $this->getNgoId();

    $query = [
        'select' => 'id,name,description,status,date,ngo_id',
        'ngo_id' => "eq.$ngoId",
    ];

    if ($request->filter === 'active') {
        $query['status'] = 'eq.1';
    } elseif ($request->filter === 'archived') {
        $query['status'] = 'eq.0';
    }

    $response = $this->supabase()
        ->get(env('SUPABASE_URL') . '/rest/v1/volunteer_events', $query);

    if ($response->failed()) {
      dd(env('SUPABASE_SERVICE_KEY'));
    }

    $events = collect($response->json());

    return view('events', compact('events'));
}


    public function store(Request $request)
    {
        $ngoId = $this->getNgoId();

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'date' => 'required|date',
        ]);

        $this->supabase()->post(
            env('SUPABASE_URL') . '/rest/v1/volunteer_events',
            [
                'name' => $request->name,
                'description' => $request->description,
                'date' => $request->date,
                'status' => 1,
                'ngo_id' => $ngoId,
            ]
        );

        return back()->with('success', 'Event created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'date' => 'required|date',
        ]);

        $this->supabase()->patch(
            env('SUPABASE_URL') . "/rest/v1/volunteer_events?id=eq.$id",
            [
                'name' => $request->name,
                'description' => $request->description,
                'date' => $request->date,
            ]
        );

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

    public function volunteerPage()
    {
        $ngoId = $this->getNgoId();

        if (!$ngoId) {
            abort(403, 'NGO ID not found.');
        }

        $events = collect(
            $this->supabase()
                ->get(env('SUPABASE_URL') . "/rest/v1/volunteer_events?status=eq.1&ngo_id=eq.$ngoId")
                ->json()
        );

        return view('volunteer-page', compact('events'));
    }
}