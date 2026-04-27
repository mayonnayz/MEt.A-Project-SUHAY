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
        // Replace 'proponents' with your Supabase table name
        $data = $this->supabase->getTable('proponents');

        return view('db_conn', ['proponents' => $data]);
    }

    // // INSERT new row
    // public function addProponent()
    // {
    //     $data = [
    //         'name' => 'Jane Smith',
    //         'role' => 'Designer'
    //     ];

    //     $this->supabase->insertRow('proponents', $data);

    //     return redirect('/')->with('success', 'Proponent added!');
    // }

    // // UPDATE row
    // public function editProponent($id)
    // {
    //     $data = [
    //         'role' => 'Lead Designer'
    //     ];

    //     $this->supabase->updateRow('proponents', $id, $data);

    //     return redirect('/')->with('success', 'Proponent updated!');
    // }

    // // DELETE row
    // public function deleteProponent($id)
    // {
    //     $this->supabase->deleteRow('proponents', $id);

    //     return redirect('/')->with('success', 'Proponent deleted!');
    // }
}