<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            // call Supabase API
            $response = Http::withHeaders([
                'apikey' => env('SUPABASE_SERVICE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'Content-Type' => 'application/json'
            ])->get(env('SUPABASE_URL') . '/rest/v1/accounts', [
                'email' => 'eq.' . $request->email
            ]);

            // check if request failed
            if ($response->failed()) {
                return back()->with('error', 'Something went wrong. Please try again.');
            }

            $users = $response->json();

            // ❗ combine email + password check
            if (empty($users) || $users[0]['password'] !== $request->password) {
                return back()->with('error', 'Wrong credentials. Please try again.');
            }

            $user = $users[0];

            // store session
            session([
                'user_id' => $user['id'],
                'user_name' => $user['first_name'],
                'user_email' => $user['email'],
                'role' => $user['roles']
            ]);

            // redirect based on role
            if (strtolower($user['roles']) === 'admin') {
                return redirect('/service-management');
            } else {
                return redirect('/user-dashboard');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login-page');
    }
}