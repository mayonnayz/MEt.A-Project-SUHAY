<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SupabaseService
{
    private $url;
    private $key;

    public function __construct()
    {
        $this->url = rtrim(env('SUPABASE_URL'), '/');
        $this->key = env('SUPABASE_SERVICE_KEY');
    }

    // GET all rows
    public function getTable($table)
    {
        $response = Http::withHeaders([
            'apikey' => $this->key,
            'Authorization' => 'Bearer '.$this->key,
            'Content-Type' => 'application/json'
        ])->get("{$this->url}/rest/v1/{$table}?select=*");

        return $response->json();
    }

    // // CREATE new row
    // public function insertRow($table, array $data)
    // {
    //     $response = Http::withHeaders([
    //         'apikey' => $this->key,
    //         'Authorization' => 'Bearer ' . $this->key,
    //         'Content-Type' => 'application/json'
    //     ])->post("{$this->url}/rest/v1/{$table}", $data);

    //     return $response->json();
    // }

    // // UPDATE row by id (replace 'id' with your primary key column)
    // public function updateRow($table, $id, array $data)
    // {
    //     $response = Http::withHeaders([
    //         'apikey' => $this->key,
    //         'Authorization' => 'Bearer ' . $this->key,
    //         'Content-Type' => 'application/json'
    //     ])->patch("{$this->url}/rest/v1/{$table}?id=eq.{$id}", $data);

    //     return $response->json();
    // }

    // // DELETE row by id
    // public function deleteRow($table, $id)
    // {
    //     $response = Http::withHeaders([
    //         'apikey' => $this->key,
    //         'Authorization' => 'Bearer ' . $this->key,
    //         'Content-Type' => 'application/json'
    //     ])->delete("{$this->url}/rest/v1/{$table}?id=eq.{$id}");

    //     return $response->json();
    // }
}