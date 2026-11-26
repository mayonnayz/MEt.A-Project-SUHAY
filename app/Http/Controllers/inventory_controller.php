<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class inventory_controller extends Controller
{
    private function supabaseHeaders(array $extra = [])
    {
        $key = env('SUPABASE_SERVICE_KEY');

        return array_merge([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
        ], $extra);
    }

    // lowercase + trim + collapse multiple spaces, for duplicate matching
    private function normalize($value)
    {
        return mb_strtolower(
            preg_replace('/\s+/', ' ', trim((string) $value))
        );
    }

    // Add quantity to an existing row and update its threshold
    private function mergeInto(array $row, array $validated, $ngoId)
    {
        $supabaseUrl = env('SUPABASE_URL');

        $addedQuantity = (int) $validated['quantity'];
        $newQuantity = (int) $row['current_quantity'] + $addedQuantity;
        $newThreshold = (int) $validated['threshold'];

        $response = Http::withHeaders($this->supabaseHeaders([
            'Content-Type' => 'application/json',
        ]))->timeout(15)->patch(
            $supabaseUrl . '/rest/v1/inventory?id=eq.' . $row['id']
                . '&ngo_id=eq.' . $ngoId,
            [
                'current_quantity' => $newQuantity,
                'minimum_threshold' => $newThreshold,
            ]
        );

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update the existing inventory item.',
                'error' => $response->body()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'merged' => true,
            'message' => 'Added ' . $addedQuantity . ' to "' . $row['name']
                . '" (' . $row['category'] . '). New quantity: '
                . $newQuantity . '. Threshold updated to ' . $newThreshold . '.',
        ]);
    }

    // Data sent to the browser so it can show the notice
    private function existingPayload(array $row)
    {
        return [
            'name' => $row['name'],
            'category' => $row['category'],
            'unit' => $row['unit'],
            'current_quantity' => $row['current_quantity'],
            'minimum_threshold' => $row['minimum_threshold'],
        ];
    }

    public function index(Request $request)
    {
        $supabaseUrl = env('SUPABASE_URL');

        $ngoId = session('ngo_id');

        if (!$ngoId) {
            return redirect()
                ->route('login.page')
                ->with('error', 'Your session has expired. Please log in again.');
        }

        $headers = $this->supabaseHeaders();

        // Run both queries AT THE SAME TIME (1 round trip instead of 2)
        $responses = Http::pool(fn (Pool $pool) => [

            // [0] This NGO's inventory
            $pool->withHeaders($headers)
                ->timeout(15)
                ->get($supabaseUrl . '/rest/v1/inventory', [
                    'select' => '*',
                    'ngo_id' => 'eq.' . $ngoId,
                ]),

            // [1] Movement dates, only for this NGO's movements
            $pool->withHeaders($headers)
                ->timeout(15)
                ->get($supabaseUrl . '/rest/v1/inventory_movement_items', [
                    'select' => 'inventory_item_id,inventory_movement!inner(date_updated,ngo_id)',
                    'inventory_movement.ngo_id' => 'eq.' . $ngoId,
                ]),
        ]);

        $inventoryResponse = $responses[0];
        $movementResponse = $responses[1];

        $inventory = collect(
            method_exists($inventoryResponse, 'successful') && $inventoryResponse->successful()
                ? $inventoryResponse->json()
                : []
        );

        // If the movement query fails, the page still loads
        // (only the "Last Updated" column will be empty)
        $movements = collect(
            method_exists($movementResponse, 'successful') && $movementResponse->successful()
                ? $movementResponse->json()
                : []
        );

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
            } elseif ($item->current_quantity <= $item->minimum_threshold) {
                $item->stock_status = 'Low Stock';
            } else {
                $item->stock_status = 'In Stock';
            }

            return $item;
        });

        // 0 = No Stock, 1 = Low Stock, 2 = In Stock
        $inventory = $inventory
            ->sortBy([
                function ($item) {
                    if ($item->current_quantity == 0) {
                        return 0;
                    }

                    if ($item->current_quantity <= $item->minimum_threshold) {
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
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $inventoryPaginated = new LengthAwarePaginator(
            $pagedData,
            $inventory->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        return view('inventory-master-list', [
            'inventory' => $inventoryPaginated,
            'categories' => $categories
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name'   => ['required', 'string', 'max:255'],
            'category'    => ['required', 'string', 'max:255'],
            'quantity'    => ['required', 'integer', 'min:0'],
            'unit'        => ['required', 'string', 'max:50'],
            'threshold'   => ['required', 'integer', 'min:0'],
            'on_conflict' => ['nullable', 'in:separate,merge'],
        ]);

        $supabaseUrl = env('SUPABASE_URL');

        $ngoId = session('ngo_id');

        if (!$ngoId) {
            return response()->json([
                'success' => false,
                'message' => 'NGO information could not be found. Please log in again.'
            ], 400);
        }

        // All active items of THIS NGO
        $existingResponse = Http::withHeaders($this->supabaseHeaders())
            ->timeout(15)
            ->get($supabaseUrl . '/rest/v1/inventory', [
                'select' => 'id,name,category,unit,current_quantity,minimum_threshold',
                'ngo_id' => 'eq.' . $ngoId,
                'status' => 'eq.1',
            ]);

        if (!$existingResponse->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to check existing inventory.',
                'error' => $existingResponse->body()
            ], 500);
        }

        $items = collect($existingResponse->json());

        $name = $this->normalize($validated['item_name']);
        $category = $this->normalize($validated['category']);

        $choice = $validated['on_conflict'] ?? null;

        // 1) Same name + same category -> ask, then merge
        $sameCategory = $items->first(function ($row) use ($name, $category) {
            return $this->normalize($row['name'] ?? '') === $name
                && $this->normalize($row['category'] ?? '') === $category;
        });

        if ($sameCategory) {

            if ($choice !== 'merge') {
                return response()->json([
                    'success' => false,
                    'needs_decision' => true,
                    'type' => 'same_category',
                    'message' => 'This item already exists in this category.',
                    'existing' => $this->existingPayload($sameCategory),
                ], 409);
            }

            return $this->mergeInto($sameCategory, $validated, $ngoId);
        }

        // 2) Same name but DIFFERENT category -> ask: separate or add to existing
        $otherCategory = $items->first(function ($row) use ($name) {
            return $this->normalize($row['name'] ?? '') === $name;
        });

        if ($otherCategory) {

            if (!$choice) {
                return response()->json([
                    'success' => false,
                    'needs_decision' => true,
                    'type' => 'other_category',
                    'message' => 'Same item name found under a different category.',
                    'existing' => $this->existingPayload($otherCategory),
                ], 409);
            }

            // "No, add to existing"
            if ($choice === 'merge') {
                return $this->mergeInto($otherCategory, $validated, $ngoId);
            }

            // "Yes, keep separate" -> fall through and create a new record
        }

        // 3) Create a new record
        $response = Http::withHeaders($this->supabaseHeaders([
            'Content-Type' => 'application/json',
        ]))->timeout(15)->post(
            $supabaseUrl . '/rest/v1/inventory',
            [
                'name' => trim($validated['item_name']),
                'category' => $validated['category'],
                'current_quantity' => (int) $validated['quantity'],
                'unit' => trim($validated['unit']),
                'minimum_threshold' => (int) $validated['threshold'],
                'status' => 1,
                'ngo_id' => $ngoId,
            ]
        );

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add inventory item.',
                'error' => $response->body()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'merged' => false,
            'message' => 'Item added successfully!',
        ]);
    }


    public function update(Request $request, $id)
    {
        $supabaseUrl = env('SUPABASE_URL');

        $ngoId = session('ngo_id');

        if (!$ngoId) {
            return response()->json([
                'success' => false,
                'message' => 'NGO information could not be found. Please log in again.'
            ], 400);
        }

        $data = $request->json()->all();

        // The ngo_id filter makes sure you can only edit your own NGO's items
        $response = Http::withHeaders($this->supabaseHeaders([
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation',
        ]))->timeout(15)->patch(
            $supabaseUrl . '/rest/v1/inventory?id=eq.' . $id
                . '&ngo_id=eq.' . $ngoId,
            [
                'name' => $data['item_name'] ?? null,
                'category' => $data['category'] ?? null,
                'current_quantity' => $data['quantity'] ?? null,
                'unit' => $data['unit'] ?? null,
                'minimum_threshold' => $data['threshold'] ?? null,
            ]
        );

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update inventory.',
                'error' => $response->body()
            ], 500);
        }

        if (empty($response->json())) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found or it does not belong to your NGO.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Inventory updated successfully',
        ]);
    }
}