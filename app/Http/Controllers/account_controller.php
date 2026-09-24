<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class account_controller extends Controller
{

    private const ROLE_LABELS = [
        1 => 'Volunteer Manager',
        2 => 'Donation Manager',
    ];


    private function getNgoIdOrNull()
    {
        return auth()->check()
            ? auth()->user()->ngo_id
            : session('ngo_id');
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


    private function supabase()
    {
        return Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ]);
    }

    public function index(Request $request)
    {
        $ngoId = $this->getNgoIdOrNull();

        $query = [
            'select' => 'id,first_name,last_name,email,password,roles,status,ngo_id,birth_date,contact_number',
            'status' => 'eq.1',
            'order'  => 'last_name.asc',
        ];


        // Only show accounts belonging to the logged-in user's NGO
        if ($ngoId) {
            $query['ngo_id'] = "eq.$ngoId";
        }


        // Only Volunteer Manager and Donation Manager
        if ($request->filled('role') &&
            in_array((int) $request->role, [1, 2], true)) {

            $query['roles'] = 'eq.' . (int) $request->role;

        } else {

            $query['roles'] = 'in.(1,2)';

        }


        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query['or'] =
                "(first_name.ilike.*{$search}*,last_name.ilike.*{$search}*,email.ilike.*{$search}*)";
        }


        $response = $this->supabase()
            ->get(
                env('SUPABASE_URL') . '/rest/v1/accounts',
                $query
            );


        if ($response->failed()) {
            dd($response->body());
        }


        $accounts = collect($response->json())
            ->map(function ($row) {

                $row['role_label'] =
                    self::ROLE_LABELS[(int) $row['roles']]
                    ?? 'Unknown';

                return (object) $row;
            });


        $roles = self::ROLE_LABELS;


        return view(
            'ngo_accounts',
            compact('accounts', 'roles')
        );
    }

    public function create()
    {
        $roles = self::ROLE_LABELS;

        return view(
            'ngo-accounts-create',
            compact('roles')
        );
    }

    public function store(Request $request)
{
    $ngoId = $this->getNgoId();

    $data = $request->validate([
        'first_name'     => 'required|string|max:255',
        'last_name'      => 'required|string|max:255',
        'email' => [
                        'required',
                        'string',
                        'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/',
                    ],
        'roles'          => 'required|integer|in:1,2',
        'address'        => 'nullable|string|max:255',
        'contact_number' => 'nullable|integer',
        'birth_date'     => 'nullable|date',
    ]);

    $defaultPassword = strtolower(
        preg_replace('/\s+/', '', $data['first_name'] . $data['last_name'])
    );

    $data['password'] = $defaultPassword;
    $data['status']   = 1;
    $data['ngo_id']   = $ngoId;

    $response = $this->supabase()->post(
        env('SUPABASE_URL') . '/rest/v1/accounts',
        $data
    );

    if ($response->failed()) {
    $error = $response->json();

    if (($error['code'] ?? null) === '23505') {
        return back()
            ->withInput()
            ->withErrors([
                'email' => 'This email address is already registered.'
            ]);
    }

    return back()
        ->withInput()
        ->withErrors([
            'general' => 'Unable to create the account. Please try again.'
        ]);
}

    return redirect()
        ->route('ngo-accounts')
        ->with('success', 'Account added.');
}


    public function import(Request $request)
{
    $request->validate([
        'csv' => 'required|file|extensions:csv',
    ]);

    $ngoId = $this->getNgoId();

    $handle = fopen($request->file('csv')->getRealPath(), 'r');

    if (!$handle) {
        return redirect()
            ->route('ngo-accounts')
            ->with('import_error', 'Unable to read the CSV file.');
    }

    $expectedHeaders = [
        'first_name',
        'last_name',
        'email',
        'roles',
        'birth_date',
        'address',
        'contact_number',
    ];

    $header = fgetcsv($handle);

    if (!$header) {
        fclose($handle);

        return redirect()
            ->route('ngo-accounts')
            ->with('import_error', 'The CSV file is empty.');
    }

    // Remove Excel BOM if present
    $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

    // Remove extra spaces from headers
    $header = array_map('trim', $header);

    /*
    |--------------------------------------------------------------------------
    | Validate CSV header
    |--------------------------------------------------------------------------
    */

    if ($header !== $expectedHeaders) {
        fclose($handle);

        return redirect()
            ->route('ngo-accounts')
            ->with(
                'import_error',
                'Invalid CSV format. Please use the required column order: first_name,last_name,email,roles,birth_date,address,contact_number'
            );
    }

    $importedCount = 0;
    $skippedRows = [];

    $rowNumber = 1;

    /*
    |--------------------------------------------------------------------------
    | Process CSV rows
    |--------------------------------------------------------------------------
    */

    while (($row = fgetcsv($handle)) !== false) {

        $rowNumber++;

        /*
        |--------------------------------------------------------------------------
        | Skip completely empty rows
        |--------------------------------------------------------------------------
        */

        if (
            count(array_filter(
                $row,
                fn ($value) => trim((string) $value) !== ''
            )) === 0
        ) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Check column count
        |--------------------------------------------------------------------------
        */

        if (count($row) !== count($expectedHeaders)) {

            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'Incorrect number of columns.',
            ];

            continue;
        }

        $data = array_combine($header, $row);

        /*
        |--------------------------------------------------------------------------
        | Required fields
        |--------------------------------------------------------------------------
        */

        $firstName = trim($data['first_name'] ?? '');
        $lastName = trim($data['last_name'] ?? '');
        $email = trim($data['email'] ?? '');
        $role = trim($data['roles'] ?? '');

        if ($firstName === '') {
            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'First name is required.',
            ];

            continue;
        }

        if ($lastName === '') {
            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'Last name is required.',
            ];

            continue;
        }

        if ($email === '') {
            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'Email is required.',
            ];

            continue;
        }

        if ($role === '') {
            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'Role is required.',
            ];

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Email validation
        |--------------------------------------------------------------------------
        */

        if (!preg_match('/^[^@\s]+@[^@\s]+\.[^@\s]+$/', $email)) {

            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'Please enter a valid email address.',
            ];

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Role validation
        |--------------------------------------------------------------------------
        */

        $roleLower = strtolower($role);

        if ($roleLower === 'volunteer manager') {

            $roleId = 1;

        } elseif ($roleLower === 'donation manager') {

            $roleId = 2;

        } else {

            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'Role must be Volunteer Manager or Donation Manager.',
            ];

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Birthday validation
        |--------------------------------------------------------------------------
        */

        $birthDate = trim($data['birth_date'] ?? '');

        if ($birthDate !== '') {

            $date = \DateTime::createFromFormat('Y-m-d', $birthDate);

            if (
                !$date ||
                $date->format('Y-m-d') !== $birthDate
            ) {

                $skippedRows[] = [
                    'row' => $rowNumber,
                    'reason' => 'Birthday must use YYYY-MM-DD format.',
                ];

                continue;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        $address = trim($data['address'] ?? '');

        if (strlen($address) > 255) {

            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'Address must not exceed 255 characters.',
            ];

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Contact number validation
        |--------------------------------------------------------------------------
        */

        $contactNumber = trim($data['contact_number'] ?? '');

        if (
            $contactNumber !== '' &&
            !preg_match('/^[0-9]+$/', $contactNumber)
        ) {

            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'Contact number must contain numbers only.',
            ];

            continue;
        }

        if (strlen($contactNumber) > 15) {

            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'Contact number must not exceed 15 digits.',
            ];

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Automatically generate password
        |--------------------------------------------------------------------------
        */

        $password = strtolower(
            preg_replace(
                '/\s+/',
                '',
                $firstName . $lastName
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Prepare account
        |--------------------------------------------------------------------------
        */

        $accountData = [
            'first_name'     => $firstName,
            'last_name'      => $lastName,
            'email'          => $email,
            'password'       => $password,
            'roles'          => $roleId,
            'status'         => 1,
            'ngo_id'         => $ngoId,
            'birth_date'     => $birthDate !== ''
                ? $birthDate
                : null,
            'address'        => $address !== ''
                ? $address
                : null,
            'contact_number' => $contactNumber !== ''
                ? $contactNumber
                : null,
        ];

        /*
        |--------------------------------------------------------------------------
        | Insert into Supabase
        |--------------------------------------------------------------------------
        */

        $response = $this->supabase()->post(
            env('SUPABASE_URL') . '/rest/v1/accounts',
            $accountData
        );

        /*
        |--------------------------------------------------------------------------
        | Handle database validation errors
        |--------------------------------------------------------------------------
        */

        if ($response->failed()) {

            $error = $response->json();

            /*
            |--------------------------------------------------------------------------
            | Duplicate email
            |--------------------------------------------------------------------------
            */

            if (
                ($error['code'] ?? null) === '23505' &&
                str_contains(
                    strtolower($error['message'] ?? ''),
                    'email'
                )
            ) {

                $skippedRows[] = [
                    'row' => $rowNumber,
                    'reason' => 'This email address is already registered.',
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Duplicate contact number
            |--------------------------------------------------------------------------
            */

            if (
                ($error['code'] ?? null) === '23505' &&
                str_contains(
                    strtolower($error['message'] ?? ''),
                    'contact_number'
                )
            ) {

                $skippedRows[] = [
                    'row' => $rowNumber,
                    'reason' => 'This contact number is already registered.',
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Other database error
            |--------------------------------------------------------------------------
            */

            $skippedRows[] = [
                'row' => $rowNumber,
                'reason' => 'Unable to create this account.',
            ];

            continue;
        }

        $importedCount++;
    }

    fclose($handle);

    /*
    |--------------------------------------------------------------------------
    | Import completed
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('ngo-accounts')
        ->with('import_result', [
            'imported' => $importedCount,
            'skipped' => $skippedRows,
        ]);
}

    public function show($id)
    {
        $response = $this->supabase()->get(
            env('SUPABASE_URL') . '/rest/v1/accounts',
            [
                'id' => "eq.$id"
            ]
        );


        if ($response->failed()) {
            dd($response->body());
        }


        $row = collect(
            $response->json()
        )->first();


        abort_if(!$row, 404);


        $row['role_label'] =
            self::ROLE_LABELS[(int) $row['roles']]
            ?? 'Unknown';


        $account = (object) $row;


        return view(
            'ngo-accounts-show',
            compact('account')
        );
    }

    public function archive($id)
    {
        $ngoId = $this->getNgoId();


        $response = $this->supabase()->patch(
            env('SUPABASE_URL') .
            "/rest/v1/accounts?id=eq.$id&ngo_id=eq.$ngoId",
            [
                'status' => 0
            ]
        );


        if ($response->failed()) {
            dd($response->body());
        }


        return redirect()
            ->route('ngo-accounts')
            ->with('success', 'Account archived.');
    }

    public function archived(Request $request)
    {
        $ngoId = $this->getNgoIdOrNull();


        $query = [
            'select' =>
                'id,first_name,last_name,email,roles,status,ngo_id,birth_date,contact_number',

            'status' =>
                'eq.0',

            'roles' =>
                'in.(1,2)',

            'order' =>
                'last_name.asc',
        ];


        // Only show archived accounts belonging to this NGO
        if ($ngoId) {
            $query['ngo_id'] = "eq.$ngoId";
        }


        // Search archived accounts
        if ($request->filled('search')) {

            $search = $request->search;

            $query['or'] =
                "(first_name.ilike.*{$search}*,last_name.ilike.*{$search}*,email.ilike.*{$search}*)";
        }


        // Optional role filter
        if ($request->filled('role') &&
            in_array((int) $request->role, [1, 2], true)) {

            $query['roles'] =
                'eq.' . (int) $request->role;
        }


        $response = $this->supabase()->get(
            env('SUPABASE_URL') . '/rest/v1/accounts',
            $query
        );


        if ($response->failed()) {
            dd($response->body());
        }


        $accounts = collect(
            $response->json()
        )->map(function ($row) {

            $row['role_label'] =
                self::ROLE_LABELS[(int) $row['roles']]
                ?? 'Unknown';

            return (object) $row;
        });


        $roles = self::ROLE_LABELS;


        return view(
            'ngo_accounts_archived',
            compact('accounts', 'roles')
        );
    }

    public function restore($id)
    {
        $ngoId = $this->getNgoId();


        $response = $this->supabase()->patch(
            env('SUPABASE_URL') .
            "/rest/v1/accounts?id=eq.$id&ngo_id=eq.$ngoId",
            [
                'status' => 1
            ]
        );


        if ($response->failed()) {
            dd($response->body());
        }


        return redirect()
            ->route('ngo-accounts.archived')
            ->with('success', 'Account restored.');
    }
}