<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class ServiceManagementController extends Controller
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
            'select' => 'accounts(*)',
            'status' => 'eq.2'
        ]);

        // ✅ Convert array → object (fixes Blade error)
        $volunteers = collect($data)
            ->pluck('accounts')
            ->map(function ($item) {
                return (object) $item;
            });

        return view('ServiceManagement', compact('volunteers'));
    }

    // 🔹 Applications list (status = 1)
    public function applications()
    {
        $data = $this->supabaseRequest('volunteer_applications', [
            'select' => 'id,status,accounts(first_name,last_name,email,roles)',
            'status' => 'eq.1'
        ]);

        // Flatten + convert to object
        $applications = collect($data)->map(function ($item) {
            return (object) [
                'id' => $item['id'],
                'status' => $item['status'],
                'first_name' => $item['accounts']['first_name'] ?? '',
                'last_name' => $item['accounts']['last_name'] ?? '',
                'email' => $item['accounts']['email'] ?? '',
                'roles' => $item['accounts']['roles'] ?? '',
            ];
        });

        return view('applications', compact('applications'));
    }

    // 🔹 Dashboard stats
    public function dashboard()
    {
        // total volunteers
        $total = $this->supabaseRequest('accounts', [
            'select' => 'id',
            'roles' => 'eq.volunteer'
        ]);

        // active volunteers
        $active = $this->supabaseRequest('accounts', [
            'select' => 'id',
            'roles' => 'eq.volunteer',
            'status' => 'eq.2'
        ]);

        $totalVolunteers = count($total);
        $activeVolunteers = count($active);

        return view('service-management.dashboard', compact(
            'totalVolunteers',
            'activeVolunteers'
        ));
    }
}