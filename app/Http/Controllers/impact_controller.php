<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        $postsRes = Http::withHeaders($headers)->get("$base/update_posts", [
            'select' => '*,ngo_profile(*)',
            'status' => 'eq.1',
            'order'  => 'id.desc',
        ]);

        $ngosRes = Http::withHeaders($headers)->get("$base/ngo_profile", [
            'select' => '*',
        ]);

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

        $posts = $postsRes->successful() ? $postsRes->json() : [];
        $ngos  = $ngosRes->successful()  ? $ngosRes->json()  : [];
        $media = collect($mediaRes->successful() ? $mediaRes->json() : []);
        $postImages = $media->where('type', 'POST')
            ->groupBy('key_id')
            ->map(fn ($items) => $items->pluck('path')->values()->all());
        $ngoLogos = $media->where('type', 'NGO_LOGO')
            ->groupBy('key_id')
            ->map(fn ($items) => $items->last()['path']);
        $posts = collect($posts)->map(function ($post) use ($postImages, $ngoLogos) {
            $images = $postImages->get($post['id'], []);

            $post['images']    = $images;
            $post['image_url'] = $images[0] ?? null;
            $post['ngo_logo']  = $ngoLogos->get($post['ngo_id']);

            return $post;
        })->all();
        
        $ngos = collect($ngos)->map(function ($ngo) use ($ngoLogos) {
            $ngo['logo'] = $ngoLogos->get($ngo['id']);
            return $ngo;
        })->all();

        return view('impact', compact('posts', 'ngos'));
    }
}