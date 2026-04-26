<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\login_controller;
use App\Http\Controllers\category_controller;
use App\Http\Controllers\event_controller;
use App\Http\Controllers\service_management_controller;
use App\Http\Controllers\program_controller;
use App\Http\Controllers\ngo_controller;
use App\Http\Controllers\volunteer_controller;
use App\Http\Controllers\volunteer_application_controller;


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

Route::prefix('volunteer')->group(function () {

    Route::get('/dashboard', [volunteer_controller::class, 'dashboard']);

    // Route::get('/applications', function () {
    //     return view('Volunteers.applications');
    // })->name('volunteer.applications');

    Route::get('/applications', [volunteer_controller::class, 'applications'])
    ->name('volunteer.applications');

    Route::post('/update-account', [volunteer_controller::class, 'updateAccount']);
    Route::get('/events', [volunteer_controller::class, 'activeEvents']);

});


// LOGIN (Supabase)
Route::post('/login', [login_controller::class, 'login']);

// Service Management
Route::get('/service-management', [service_management_controller::class, 'volunteers']);
Route::get('/applications', [service_management_controller::class, 'applications']);

Route::get('/sm-ngos', [ngo_controller::class, 'profile']);
Route::post('/update-ngo', [ngo_controller::class, 'update']);
Route::get('/ngo-members', function () {
    return view('ngo_members');
});

// Assignments
Route::get('/assignments', function () {
    return view('assignments');
});


// ======================
// VOLUNTEER APPLICATION
// ======================

Route::get('/volunteer-application-form', function () {
    return view('volunteer_application_form');
});

// show form
Route::get('/volunteer-application-form', [volunteer_application_controller::class, 'showForm']);

// submit
Route::post('/submit-application', [volunteer_application_controller::class, 'submit_application']);


// ======================
// EVENTS (UPDATED SECTION)
// ======================
Route::get('/events', [event_controller::class, 'index']);
Route::post('/events', [event_controller::class, 'store']);
Route::put('/events/{id}', [event_controller::class, 'update']);
Route::put('/events/{id}/archive', [event_controller::class, 'archive']);
Route::put('/events/{id}/reactivate', [event_controller::class, 'reactivate']);
Route::delete('/activities/{id}', [event_controller::class, 'deleteActivity']);


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