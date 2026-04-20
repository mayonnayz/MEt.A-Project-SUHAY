<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\login_controller;
use App\Http\Controllers\category_controller;
use App\Http\Controllers\event_controller;
use App\Http\Controllers\service_management_controller;
use App\Http\Controllers\program_controller;

// Landing Page
Route::get('/', function () {
    return view('landing');
});

// Volunteer / Events Page
Route::get('/volunteer-page', [event_controller::class, 'volunteerPage']);

// Login Page
Route::get('/login-page', function () {
    return view('login');
});

// LOGIN (Supabase)
Route::post('/login', [login_controller::class, 'login']);

// Service Management
Route::get('/service-management', [service_management_controller::class, 'volunteers']);
Route::get('/applications', [service_management_controller::class, 'applications']);

// Assignments
Route::get('/assignments', function () {
    return view('assignments');
});

Route::get('/volunteer-application-form', function () {
    return view('volunteer-application-form');
});

// ======================
// EVENTS (UPDATED SECTION)
// ======================
Route::get('/events', [event_controller::class, 'index']);
Route::post('/events', [event_controller::class, 'store']);
Route::put('/events/{id}', [event_controller::class, 'update']);
Route::put('/events/{id}/archive', [event_controller::class, 'archive']);


// Track Activity
Route::get('/track-activity', function () {
    return view('track_activity');
});

// Dashboard
Route::get('/volunteer-manager/dashboard', function () {
    return view('VolunteerManager.dashboard');
});

// Program Controller
Route::get('/assign-volunteer/{id}', [program_controller::class, 'assignVolunteer']);
Route::post('/assign', [program_controller::class, 'assign']);
Route::post('/remove', [program_controller::class, 'remove']);

// Logout
Route::get('/sm-logout', [login_controller::class, 'logout']);