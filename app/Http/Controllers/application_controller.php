<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class application_controller extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SUPABASE REQUEST
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | GET APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function applications()
    {
        $data = $this->supabaseRequest('volunteer_applications', [

            'select' =>
                'id,volunteer_event_id,account_id,application_date,skills,remarks,status,' .
                'accounts!inner(first_name,last_name,email,address,contact_number,birth_date)',

            // Only active applications
            'status' => 'eq.1',

            // Only active accounts
            'accounts.status' => 'eq.1',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CONVERT DATA
        |--------------------------------------------------------------------------
        */

        $applications = collect($data)
            ->map(function ($item) {

                // Make sure item is an array
                if (!is_array($item)) {
                    return null;
                }

                $account = $item['accounts'] ?? [];

                if (!is_array($account)) {
                    $account = [];
                }

                return [
                    // Application information
                    'id' =>
                        $item['id'] ?? null,

                    'volunteer_event_id' =>
                        $item['volunteer_event_id'] ?? null,

                    'account_id' =>
                        $item['account_id'] ?? null,

                    'application_date' =>
                        $item['application_date'] ?? '',

                    'skills' =>
                        $item['skills'] ?? '',

                    'remarks' =>
                        $item['remarks'] ?? '',

                    'status' =>
                        $item['status'] ?? 0,


                    // Account information
                    'first_name' =>
                        $account['first_name'] ?? '',

                    'last_name' =>
                        $account['last_name'] ?? '',

                    'email' =>
                        $account['email'] ?? '',

                    'address' =>
                        $account['address'] ?? '',

                    'contact_number' =>
                        $account['contact_number'] ?? '',

                    'birth_date' =>
                        $account['birth_date'] ?? '',
                ];
            })
            ->filter()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | UNIQUE SKILLS
        |--------------------------------------------------------------------------
        */

        $skills = $applications
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


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        // If you have a search parameter
        // such as ?search=Maria

        request()->whenHas('search', function () use (&$applications) {

            $search = strtolower(
                trim(request('search'))
            );

            $applications = $applications->filter(
                function ($item) use ($search) {

                    $firstName = strtolower(
                        $item['first_name'] ?? ''
                    );

                    $lastName = strtolower(
                        $item['last_name'] ?? ''
                    );

                    $email = strtolower(
                        $item['email'] ?? ''
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
        });


        /*
        |--------------------------------------------------------------------------
        | SKILL FILTER
        |--------------------------------------------------------------------------
        */

        if (request()->filled('search_skill')) {

            $skillFilter = strtolower(
                trim(request('search_skill'))
            );

            $applications = $applications->filter(
                function ($item) use ($skillFilter) {

                    $skillsString = strtolower(
                        $item['skills'] ?? ''
                    );

                    $skillArray = array_map(
                        'trim',
                        explode(',', $skillsString)
                    );

                    return in_array(
                        $skillFilter,
                        $skillArray
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SORT BY NAME
        |--------------------------------------------------------------------------
        */

        $applications = $applications
            ->sortBy(function ($item) {

                return strtolower(
                    ($item['first_name'] ?? '') .
                    ' ' .
                    ($item['last_name'] ?? '')
                );

            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'applications',
            compact(
                'applications',
                'skills'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE APPLICATION
    |--------------------------------------------------------------------------
    */

    public function approveApplication($id)
    {
        return $this->updateStatus($id, 1);
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT APPLICATION
    |--------------------------------------------------------------------------
    */

    public function rejectApplication($id)
    {
        return $this->updateStatus($id, 2);
    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE APPLICATION
    |--------------------------------------------------------------------------
    */

    public function restoreApplication($id)
    {
        return $this->updateStatus($id, 0);
    }


    /*
    |--------------------------------------------------------------------------
    | ARCHIVE APPLICATION
    |--------------------------------------------------------------------------
    */

    public function archiveApplication($id)
    {
        return $this->updateStatus($id, 3);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    private function updateStatus($id, $status)
    {
        $response = Http::withHeaders([

            'apikey' =>
                env('SUPABASE_SERVICE_KEY'),

            'Authorization' =>
                'Bearer ' . env('SUPABASE_SERVICE_KEY'),

            'Content-Type' =>
                'application/json',

        ])->patch(

            env('SUPABASE_URL') .
            "/rest/v1/volunteer_applications?id=eq.$id",

            [
                'status' => $status
            ]

        );


        return response()->json([

            'success' =>
                $response->successful(),

            'data' =>
                $response->json()

        ]);
    }
}