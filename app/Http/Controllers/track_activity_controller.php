<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class track_activity_controller extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/volunteer_assignments', [
            'select' => '*,accounts(first_name,last_name),activities(name)',
        ]);

        $assignments = $response->json();

        return view('track_activity', compact('assignments'));
    }
}