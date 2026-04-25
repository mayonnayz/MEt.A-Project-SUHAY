<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\login_controller;
use App\Http\Controllers\category_controller;
use App\Http\Controllers\event_controller;
use App\Http\Controllers\service_management_controller;
use App\Http\Controllers\program_controller;
use App\Http\Controllers\track_activity_controller;
use App\Http\Controllers\ngo_controller;
use App\Http\Controllers\volunteer_controller;// ======================
// LANDING & AUTH
// ======================
Route::get('/', function () {
    return view('landing');
});

Route::get('/about', function () {
    return view('about_us');
});

Route::get('/login-page', function () {
    return view('login');
});


Route::get('/ngos', [ngo_controller::class, 'ngosPage']);
Route::post('/login', [login_controller::class, 'login']);
Route::get('/sm-logout', [login_controller::class, 'logout']);


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
// ======================
// VOLUNTEER / EVENTS PAGE
// ======================
Route::get('/volunteer-page', [event_controller::class, 'volunteerPage']);


// ======================
// SERVICE MANAGEMENT
// ======================
Route::get('/service-management', [service_management_controller::class, 'volunteers']);
Route::get('/applications', [service_management_controller::class, 'applications']);

Route::patch('/applications/approve/{id}', [service_management_controller::class, 'approveApplication']);
Route::patch('/applications/reject/{id}', [service_management_controller::class, 'rejectApplication']);
Route::patch('/applications/restore/{id}', [service_management_controller::class, 'restoreApplication']);
Route::patch('/applications/archive/{id}', [service_management_controller::class, 'archiveApplication']);

Route::get('/volunteers', [service_management_controller::class, 'volunteers']);
Route::post('/assign-volunteer', [service_management_controller::class, 'store']);
Route::patch('/volunteers/deactivate/{id}', [service_management_controller::class, 'deactivate']);


// ======================
// EVENTS (UPDATED SECTION)
// ======================
Route::get('/events', [event_controller::class, 'index']);
Route::post('/events', [event_controller::class, 'store']);
Route::put('/events/{id}', [event_controller::class, 'update']);
Route::put('/events/{id}/archive', [event_controller::class, 'archive']);
Route::put('/events/{id}/reactivate', [event_controller::class, 'reactivate']);
Route::delete('/activities/{id}', [event_controller::class, 'deleteActivity']);


// 🔥 THIS IS WHAT YOUR MODAL USES
Route::get('/events/{id}/activities', [event_controller::class, 'getActivities']);


// ======================
// ASSIGNMENTS (FIXED)
// ======================
Route::get('/assignments', [event_controller::class, 'assignments']);
Route::get('/api/volunteers', [service_management_controller::class, 'getVolunteers']);

// ======================
// PROGRAM CONTROLLER
// ======================
Route::post('/assign-volunteer', [service_management_controller::class, 'store']);

// ======================
// OTHER PAGES
// ======================
Route::get('/volunteer-application-form', function () {
    return view('volunteer-application-form');
});


Route::get('/volunteer-manager/dashboard', function () {
    return view('VolunteerManager.dashboard');
});


//TRACK ACTIVITY
Route::get('/track-activity', [track_activity_controller::class, 'index']);


// NGO MANAGEMENT
Route::get('/sm-ngos', [ngo_controller::class, 'profile']);
Route::post('/update-ngo', [ngo_controller::class, 'update']);
Route::get('/ngo-members', function () {
    return view('ngo_members');
});