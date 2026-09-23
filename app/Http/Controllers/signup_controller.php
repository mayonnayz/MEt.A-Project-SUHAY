<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class signup_controller extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date_format:Y-m-d',
            'contact_number' => 'required|string|max:20',
        ]);

        try {

            $existingUser = Http::withHeaders([
                'apikey' => env('SUPABASE_SERVICE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'Content-Type' => 'application/json'
            ])->get(env('SUPABASE_URL') . '/rest/v1/accounts', [
                'email' => 'eq.' . $request->email
            ]);

            if ($existingUser->successful() && count($existingUser->json()) > 0) {
                return back()
                    ->withInput()
                    ->with('error', 'An account with this email already exists.');
            }

            $response = Http::withHeaders([
                'apikey' => env('SUPABASE_SERVICE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=minimal'
            ])->post(env('SUPABASE_URL') . '/rest/v1/accounts', [

                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $request->password,

                'roles' => 3,
                'status' => 1,
                'ngo_id' => null,

                'address' => $request->address,
                'birth_date' => $request->birthdate,
                'contact_number' => $request->contact_number,
            ]);

            if (!$response->successful()) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Supabase error: ' . $response->body()
                    );
            }

            return redirect('/login-page')
                ->with('success', 'Account created successfully. You can now log in.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }
}