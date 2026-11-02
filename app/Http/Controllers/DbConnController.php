<?php

namespace App\Http\Controllers;

use App\Services\SupabaseService;

class DbConnController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase; 
    }

    public function index()
    {
        
        $data = $this->supabase->getTable('proponents');

        return view('db_conn', ['proponents' => $data]);
    }


}