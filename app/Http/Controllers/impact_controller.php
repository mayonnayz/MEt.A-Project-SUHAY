<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class impact_controller extends Controller
{
    public function index()
    {
        $base = rtrim(env('SUPABASE_URL'), '/') . '/rest/v1';

        $key = env('SUPABASE_ANON_KEY')
            ?: env('SUPABASE_KEY')
            ?: env('SUPABASE_SERVICE_KEY')
            ?: env('SUPABASE_SERVICE_ROLE_KEY')
            ?: env('SUPABASE_API_KEY');

        $headers = [
            'apikey'        => $key,
            'Authorization' => 'Bearer ' . $key,
        ];

        // =========================================
        // FETCH PUBLISHED POSTS
        // =========================================

        $postsRes = Http::withHeaders($headers)->get("$base/update_posts", [
            'select' => '*,ngo_profile(*)',
            'status' => 'eq.1',
            'order'  => 'date_created.desc,id.desc',
        ]);

        // =========================================
        // FETCH NGO PROFILES
        // =========================================

        $ngosRes = Http::withHeaders($headers)->get("$base/ngo_profile", [
            'select' => '*',
        ]);

        // =========================================
        // FETCH POST IMAGES AND NGO LOGOS
        // =========================================

        $mediaRes = Http::withHeaders($headers)->get("$base/media_table", [
            'select' => 'id,key_id,type,path',
            'type'   => 'in.(POST,NGO_LOGO)',
            'order'  => 'id.asc',
        ]);

        foreach ([
            'update_posts' => $postsRes,
            'ngo_profile'  => $ngosRes,
            'media_table'  => $mediaRes,
        ] as $name => $res) {
            if ($res->failed()) {
                Log::error("Impact: failed to fetch $name", [
                    'status' => $res->status(),
                    'body'   => $res->body(),
                ]);
            }
        }

        $posts = $postsRes->successful()
            ? collect($postsRes->json())
            : collect();

        $ngos = $ngosRes->successful()
            ? collect($ngosRes->json())
            : collect();

        $media = collect(
            $mediaRes->successful() ? $mediaRes->json() : []
        );

        // =========================================
        // GROUP ALL POST IMAGES BY POST ID
        // =========================================

        $postImages = $media
            ->where('type', 'POST')
            ->filter(function ($item) {
                return !empty($item['key_id'])
                    && !empty($item['path']);
            })
            ->groupBy(function ($item) {
                return (string) $item['key_id'];
            })
            ->map(function ($items) {
                return $items
                    ->pluck('path')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();
            });

        // =========================================
        // GROUP NGO LOGOS BY NGO ID
        // =========================================

        $ngoLogos = $media
            ->where('type', 'NGO_LOGO')
            ->groupBy(function ($item) {
                return (string) $item['key_id'];
            })
            ->map(function ($items) {
                return $items->last()['path'] ?? null;
            });

        // =========================================
        // ATTACH IMAGES, DATE, AND NGO DETAILS
        // =========================================

        $posts = $posts->map(function ($post) use ($postImages, $ngoLogos) {
            $postId = (string) ($post['id'] ?? '');

            $ngoId = (string) ($post['ngo_id'] ?? '');

            $images = $postImages->get($postId, []);

            // All images for the gallery.
            $post['images'] = $images;

            // Keep the first image for compatibility.
            $post['image_url'] = $images[0] ?? null;

            // Use the actual date field from update_posts.
            $post['created_at'] = $post['date_created']
                ?? $post['created_at']
                ?? null;

            // Attach the NGO logo.
            $post['ngo_logo'] = $ngoLogos->get($ngoId);

            // Make the NGO name available to the Blade view.
            $post['ngo_name'] =
                data_get($post, 'ngo_profile.ngo_name')
                ?? data_get($post, 'ngo_profile.name')
                ?? null;

            return $post;
        })->values();

        // =========================================
        // ATTACH LOGOS TO NGO SIDEBAR
        // =========================================

        $ngos = $ngos->map(function ($ngo) use ($ngoLogos) {
            $ngo['logo'] = $ngoLogos->get(
                (string) ($ngo['id'] ?? '')
            );

            return $ngo;
        })->values();

        return view('impact', [
            'posts' => $posts,
            'ngos'  => $ngos,
        ]);
    }
}
