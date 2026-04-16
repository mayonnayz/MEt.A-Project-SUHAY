<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    private function supabase()
    {
        return Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ]);
    }

    public function index(Request $request)
    {
        $filter = $request->filter;

        // Get all categories
        $allCategories = $this->supabase()
            ->get(env('SUPABASE_URL') . '/rest/v1/volunteer_categories')
            ->json();

        $query = env('SUPABASE_URL') . '/rest/v1/volunteer_categories';

        if ($filter === 'active') {
            $query .= '?status=eq.1';
        } elseif ($filter === 'archived') {
            $query .= '?status=eq.0';
        }

        $categories = $this->supabase()
            ->get($query)
            ->json();

        return view('categories', [
            'categories' => $categories,
            'allCategories' => $allCategories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        // Check if exists
        $exists = $this->supabase()
            ->get(env('SUPABASE_URL') . '/rest/v1/volunteer_categories?name=eq.' . $request->name)
            ->json();

        if (!empty($exists)) {
            return back()->with('error', 'This category already exists.');
        }

        // Insert
        $this->supabase()->post(
            env('SUPABASE_URL') . '/rest/v1/volunteer_categories',
            [
                'name' => $request->name,
                'description' => $request->description,
                'status' => 1,
            ]
        );

        return back()->with('success', 'Category added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        // Check duplicate
        $exists = $this->supabase()
            ->get(env('SUPABASE_URL') . "/rest/v1/volunteer_categories?name=eq.{$request->name}&id=neq.$id")
            ->json();

        if (!empty($exists)) {
            return back()->with('error', 'Category name already exists!');
        }

        // Update
        $this->supabase()->patch(
            env('SUPABASE_URL') . "/rest/v1/volunteer_categories?id=eq.$id",
            [
                'name' => $request->name,
                'description' => $request->description,
            ]
        );

        return back()->with('success', 'Category updated successfully!');
    }

    public function archive($id)
    {
        $this->supabase()->patch(
            env('SUPABASE_URL') . "/rest/v1/volunteer_categories?id=eq.$id",
            [
                'status' => 0
            ]
        );

        return back()->with('success', 'Category archived.');
    }

    public function volunteerPage()
    {
        $categories = $this->supabase()
            ->get(env('SUPABASE_URL') . '/rest/v1/volunteer_categories?status=eq.1')
            ->json();

        return view('volunteer-page', compact('categories'));
    }
}