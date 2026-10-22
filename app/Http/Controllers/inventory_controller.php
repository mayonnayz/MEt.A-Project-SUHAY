<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class inventory_controller extends Controller
{
    public function index(Request $request)
    {
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY');


        $inventoryResponse = Http::withHeaders([
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ])->get($supabaseUrl . '/rest/v1/inventory', [
            'select' => '*'
        ]);

        $inventory = collect($inventoryResponse->json());

        $movementResponse = Http::withHeaders([
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ])->get($supabaseUrl . '/rest/v1/inventory_movement_items', [
            'select' => 'inventory_item_id, inventory_movement(date_updated)'
        ]);

        $movements = collect($movementResponse->json());

        $lastMovementMap = [];

        foreach ($movements as $move) {

            $itemId = $move['inventory_item_id'] ?? null;

            $date = $move['inventory_movement']['date_updated'] ?? null;

            if (!$itemId || !$date) {
                continue;
            }

            if (
                !isset($lastMovementMap[$itemId]) ||
                $date > $lastMovementMap[$itemId]
            ) {
                $lastMovementMap[$itemId] = $date;
            }
        }

        $inventory = $inventory->map(function ($item) use ($lastMovementMap) {

            $item = (object) $item;

            $item->last_movement_date =
                $lastMovementMap[$item->id] ?? null;


            if ($item->current_quantity == 0) {

                $item->stock_status = 'No Stock';

            } elseif (
                $item->current_quantity <= $item->minimum_threshold
            ) {

                $item->stock_status = 'Low Stock';

            } else {

                $item->stock_status = 'In Stock';
            }


            return $item;
        });


        // 0 = No Stock
        // 1 = Low Stock
        // 2 = In Stock
        $inventory = $inventory
            ->sortBy([
                function ($item) {

                    if ($item->current_quantity == 0) {
                        return 0;
                    }

                    if (
                        $item->current_quantity <=
                        $item->minimum_threshold
                    ) {
                        return 1;
                    }

                    return 2;
                },

                function ($item) {

                    return strtolower($item->name);
                }
            ])
            ->values();


        $categories = $inventory
            ->pluck('category')
            ->filter()
            ->unique()
            ->sort()
            ->values();


        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 30;

        $pagedData = $inventory
            ->slice(
                ($currentPage - 1) * $perPage,
                $perPage
            )
            ->values();


        $inventoryPaginated = new LengthAwarePaginator(
            $pagedData,
            $inventory->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url()
            ]
        );



        return view('inventory-master-list', [
            'inventory' => $inventoryPaginated,
            'categories' => $categories
        ]);
    }


    public function update(Request $request, $id)
    {
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY');

        $data = $request->json()->all();

        $response = Http::withHeaders([
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
            'Content-Type' => 'application/json',
        ])->patch(
            $supabaseUrl . '/rest/v1/inventory?id=eq.' . $id,
            [
                'name' => $data['item_name'] ?? null,
                'category' => $data['category'] ?? null,
                'current_quantity' => $data['quantity'] ?? null,
                'unit' => $data['unit'] ?? null,
                'minimum_threshold' => $data['threshold'] ?? null,
            ]
        );


        return response()->json([
            'message' => 'Inventory updated successfully',
            'data' => $response->json()
        ]);
    }
}