<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class service_management_controller extends Controller
{
    // 🔹 Reusable Supabase API request
    private function supabaseRequest($endpoint, $params = [])
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->get(env('SUPABASE_URL') . '/rest/v1/' . $endpoint, $params);

        return $response->json();
    }

    // 🔹 Volunteers list (status = 2)
  public function volunteers()
    {
        $data = $this->supabaseRequest('volunteer_applications', [
            'select' => 'accounts(first_name,last_name,email,roles)',
            'status' => 'eq.1'
        ]);

        $volunteers = collect($data)
            ->pluck('accounts')
            ->filter()
            ->map(fn ($item) => (object) $item);

        return view('service_management', compact('volunteers'));
    }

  public function applications()
    {
        $data = $this->supabaseRequest('volunteer_applications', [
            'select' => 'id,status,accounts(first_name,last_name,email,roles,address,contact_number,birth_date)',
            'status' => 'eq.0'
        ]);

        $applications = collect($data)->map(function ($item) {
            return (object) [
                'id' => $item['id'],
                'status' => $item['status'],
                'first_name' => $item['accounts']['first_name'] ?? '',
                'last_name' => $item['accounts']['last_name'] ?? '',
                'email' => $item['accounts']['email'] ?? '',
                'roles' => $item['accounts']['roles'] ?? '',
                'address' => $item['accounts']['address'] ?? '',
                'contact_number' => $item['accounts']['contact_number'] ?? '',
                'birth_date' => $item['accounts']['birth_date'] ?? '',
            ];
        });

        return view('applications', compact('applications'));
    }
    // 🔹 Dashboard stats
   public function dashboard()
    {
        $approved = $this->supabaseRequest('volunteer_applications', [
            'select' => 'id',
            'status' => 'eq.1'
        ]);

        $pending = $this->supabaseRequest('volunteer_applications', [
            'select' => 'id',
            'status' => 'eq.0'
        ]);

        return view('service-management.dashboard', [
            'totalVolunteers' => count($approved),
            'pendingApplications' => count($pending),
            'activeVolunteers' => count($approved)
        ]);
    }
}