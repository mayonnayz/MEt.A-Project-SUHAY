<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.header', function ($view) {

            $userId = session('user_id');

            $notifications = collect();
            $unreadNotifications = 0;

            if ($userId) {

                $supabaseUrl = env('SUPABASE_URL');
                $supabaseKey = env('SUPABASE_SERVICE_KEY');

                try {

                    $response = Http::withHeaders([
                        'apikey' => $supabaseKey,
                        'Authorization' => 'Bearer ' . $supabaseKey,
                        'Accept' => 'application/json',
                    ])->get(
                        $supabaseUrl . '/rest/v1/notifications',
                        [
                            'user_id' => 'eq.' . $userId,
                            'order' => 'created_at.desc',
                            'limit' => 10,
                        ]
                    );

                    if ($response->successful()) {

                        $notifications = collect($response->json());

                        $unreadNotifications = $notifications
                            ->where('is_read', false)
                            ->count();
                    }

                } catch (\Exception $e) {

                    // Prevent the header from crashing
                    // if Supabase is temporarily unavailable.

                    $notifications = collect();
                    $unreadNotifications = 0;
                }
            }

            $view->with([
                'notifications' => $notifications,
                'unreadNotifications' => $unreadNotifications,
            ]);
        });
    }
}