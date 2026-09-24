<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class inventory_movement_controller extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SUPABASE HELPER
    |--------------------------------------------------------------------------
    */

    private function supabase()
    {
        return Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
            'Content-Type' => 'application/json',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MOVEMENT TYPE LABEL
    |--------------------------------------------------------------------------
    */

    private function movementLabel($type)
    {
        switch ((int) $type) {

            case 0:
                return 'Inbound';

            case 1:
                return 'Outbound';

            case 2:
                return 'Pending';

            default:
                return 'Unknown';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | INVENTORY MOVEMENT LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $supabaseUrl = env('SUPABASE_URL');


        /*
        |--------------------------------------------------------------------------
        | GET MOVEMENTS
        |--------------------------------------------------------------------------
        */

        $movementResponse = $this->supabase()->get(
            $supabaseUrl . '/rest/v1/inventory_movement',
            [
                'select' => '*',
                'order' => 'date_updated.desc,id.desc'
            ]
        );

        if (!$movementResponse->successful()) {

            return back()->with(
                'error',
                'Failed to load inventory movements.'
            );
        }

        $movements = collect(
            $movementResponse->json()
        );


        /*
        |--------------------------------------------------------------------------
        | GET MOVEMENT ITEMS
        |--------------------------------------------------------------------------
        */

        $movementItemsResponse = $this->supabase()->get(
            $supabaseUrl . '/rest/v1/inventory_movement_items',
            [
                'select' => '*'
            ]
        );

        if (!$movementItemsResponse->successful()) {

            return back()->with(
                'error',
                'Failed to load movement items.'
            );
        }

        $movementItems = collect(
            $movementItemsResponse->json()
        );


        /*
        |--------------------------------------------------------------------------
        | GET INVENTORY
        |--------------------------------------------------------------------------
        */

        $inventoryResponse = $this->supabase()->get(
            $supabaseUrl . '/rest/v1/inventory',
            [
                'select' =>
                    'id,name,unit,category,current_quantity,status'
            ]
        );

        if (!$inventoryResponse->successful()) {

            return back()->with(
                'error',
                'Failed to load inventory.'
            );
        }

        $inventory = collect(
            $inventoryResponse->json()
        );


        /*
        |--------------------------------------------------------------------------
        | CREATE INVENTORY LOOKUP
        |--------------------------------------------------------------------------
        |
        | inventory.id
        |      ↓
        | inventory record
        |
        */

        $inventoryLookup = [];

        foreach ($inventory as $inventoryItem) {

            $inventoryLookup[
                (string) $inventoryItem['id']
            ] = $inventoryItem;
        }


        /*
        |--------------------------------------------------------------------------
        | GET ACCOUNTS
        |--------------------------------------------------------------------------
        |
        | inventory_movement.account_at
        |              ↓
        | accounts.id
        |              ↓
        | first_name + last_name
        |
        */

        $accountResponse = $this->supabase()->get(
            $supabaseUrl . '/rest/v1/accounts',
            [
                'select' =>
                    'id,first_name,last_name,email'
            ]
        );

        if (!$accountResponse->successful()) {

            return back()->with(
                'error',
                'Failed to load accounts.'
            );
        }

        $accounts = collect(
            $accountResponse->json()
        );


        /*
        |--------------------------------------------------------------------------
        | CREATE ACCOUNT LOOKUP
        |--------------------------------------------------------------------------
        */

        $accountLookup = [];

        foreach ($accounts as $account) {

            $accountLookup[
                (string) $account['id']
            ] = $account;
        }


        /*
        |--------------------------------------------------------------------------
        | ATTACH ITEMS + ACCOUNT
        |--------------------------------------------------------------------------
        */

        $movements = $movements->map(
            function ($movement) use (
                $movementItems,
                $inventoryLookup,
                $accountLookup
            ) {

                /*
                |--------------------------------------------------------------------------
                | GET ITEMS FOR THIS MOVEMENT
                |--------------------------------------------------------------------------
                */

                $items = $movementItems
                    ->where(
                        'movement_id',
                        $movement['id']
                    )
                    ->map(
                        function ($item) use (
                            $inventoryLookup
                        ) {

                            $inventoryId =
                                (string) (
                                    $item['inventory_item_id']
                                    ?? ''
                                );

                            $inventoryItem =
                                $inventoryLookup[
                                    $inventoryId
                                ] ?? null;


                            return [

                                'id' =>
                                    $item['id'],

                                'inventory_item_id' =>
                                    $item['inventory_item_id']
                                    ?? null,

                                'donated_item_id' =>
                                    $item['donated_item_id']
                                    ?? null,

                                'quantity' =>
                                    (int) (
                                        $item['quantity']
                                        ?? 0
                                    ),

                                'name' =>
                                    $inventoryItem['name']
                                    ?? 'Unknown Item',

                                'unit' =>
                                    $inventoryItem['unit']
                                    ?? '',

                                'category' =>
                                    $inventoryItem['category']
                                    ?? '',

                            ];
                        }
                    )
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | ATTACH ITEMS
                |--------------------------------------------------------------------------
                */

                $movement['items'] =
                    $items;

                $movement['item_count'] =
                    $items->count();

                $movement['total_quantity'] =
                    $items->sum('quantity');


                /*
                |--------------------------------------------------------------------------
                | MOVEMENT TYPE
                |--------------------------------------------------------------------------
                */

                $movement['movement_label'] =
                    $this->movementLabel(
                        $movement['movement_type']
                    );


                /*
                |--------------------------------------------------------------------------
                | ATTACH ACCOUNT
                |--------------------------------------------------------------------------
                */

                $accountId =
                    (string) (
                        $movement['account_at']
                        ?? ''
                    );

                $account =
                    $accountLookup[$accountId]
                    ?? null;


                if ($account) {

                    $firstName =
                        trim(
                            $account['first_name']
                            ?? ''
                        );

                    $lastName =
                        trim(
                            $account['last_name']
                            ?? ''
                        );

                    $fullName =
                        trim(
                            $firstName .
                            ' ' .
                            $lastName
                        );


                    $movement['account'] =
                        (object) [

                            'id' =>
                                $account['id'],

                            'first_name' =>
                                $firstName,

                            'last_name' =>
                                $lastName,

                            'name' =>
                                $fullName !== ''
                                    ? $fullName
                                    : 'Unknown User',
                        ];

                } else {

                    $movement['account'] =
                        (object) [

                            'id' =>
                                $movement['account_at']
                                ?? null,

                            'first_name' =>
                                '',

                            'last_name' =>
                                '',

                            'name' =>
                                'Unknown User',
                        ];
                }


                /*
                |--------------------------------------------------------------------------
                | RETURN AS OBJECT
                |--------------------------------------------------------------------------
                */

                return (object) $movement;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $currentPage =
            LengthAwarePaginator::resolveCurrentPage();

        $perPage = 30;


        $pagedData =
            $movements
                ->slice(
                    ($currentPage - 1) *
                    $perPage,
                    $perPage
                )
                ->values();


        $movementsPaginated =
            new LengthAwarePaginator(
                $pagedData,
                $movements->count(),
                $perPage,
                $currentPage,
                [
                    'path' =>
                        request()->url()
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | ACTIVE INVENTORY ITEMS
        |--------------------------------------------------------------------------
        */

        $inventoryItems =
            $inventory
                ->where(
                    'status',
                    1
                )
                ->sortBy(
                    'name'
                )
                ->values();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'inventory_movement',
            [
                'movements' =>
                    $movementsPaginated,

                'inventoryItems' =>
                    $inventoryItems,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE NEW MOVEMENT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate([

                'date_updated' => [
                    'required',
                    'date'
                ],

                'movement_type' => [
                    'required',
                    'integer',
                    'in:0,1,2'
                ],

                'remarks' => [
                    'nullable',
                    'string',
                    'max:300'
                ],

                'items' => [
                    'required',
                    'array',
                    'min:1'
                ],

                'items.*.inventory_item_id' => [
                    'required',
                    'integer'
                ],

                'items.*.quantity' => [
                    'required',
                    'integer',
                    'min:1'
                ],

            ]);


        $supabaseUrl =
            env('SUPABASE_URL');


        /*
        |--------------------------------------------------------------------------
        | ACCOUNT
        |--------------------------------------------------------------------------
        */

        $accountId =
            session('user_id');

        if (!$accountId) {

            return back()->with(
                'error',
                'Your session has expired. Please log in again.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NGO
        |--------------------------------------------------------------------------
        */

        $ngoId =
            session('ngo_id');

        if (!$ngoId) {

            return back()->with(
                'error',
                'NGO information could not be found.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GET SELECTED INVENTORY ITEMS
        |--------------------------------------------------------------------------
        */

        $inventoryIds =
            collect(
                $validated['items']
            )
                ->pluck(
                    'inventory_item_id'
                )
                ->unique()
                ->values()
                ->toArray();


        $inventoryResponse =
            $this->supabase()->get(
                $supabaseUrl .
                '/rest/v1/inventory',
                [

                    'select' =>
                        'id,name,current_quantity,status,ngo_id',

                    'id' =>
                        'in.(' .
                        implode(
                            ',',
                            $inventoryIds
                        ) .
                        ')'

                ]
            );


        if (
            !$inventoryResponse->successful()
        ) {

            return back()->with(
                'error',
                'Unable to verify inventory items.'
            );
        }


        $inventoryItems =
            collect(
                $inventoryResponse->json()
            );


        /*
        |--------------------------------------------------------------------------
        | CHECK ITEMS
        |--------------------------------------------------------------------------
        */

        foreach (
            $validated['items']
            as $item
        ) {

            $inventoryItem =
                $inventoryItems->firstWhere(
                    'id',
                    $item['inventory_item_id']
                );


            if (!$inventoryItem) {

                return back()->with(
                    'error',
                    'One of the selected inventory items does not exist.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | CHECK STATUS
            |--------------------------------------------------------------------------
            */

            if (
                (int)
                $inventoryItem['status']
                !== 1
            ) {

                return back()->with(
                    'error',
                    $inventoryItem['name'] .
                    ' is currently archived.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | CHECK NGO
            |--------------------------------------------------------------------------
            */

            if (
                isset($inventoryItem['ngo_id']) &&
                (int) $inventoryItem['ngo_id']
                !== (int) $ngoId
            ) {

                return back()->with(
                    'error',
                    $inventoryItem['name'] .
                    ' does not belong to your NGO.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | OUTBOUND STOCK CHECK
            |--------------------------------------------------------------------------
            */

            if (
                (int)
                $validated['movement_type']
                === 1
                &&
                (int)
                $item['quantity']
                >
                (int)
                $inventoryItem['current_quantity']
            ) {

                return back()->with(
                    'error',
                    'Not enough stock for ' .
                    $inventoryItem['name'] .
                    '.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | INSERT MOVEMENT HEADER
        |--------------------------------------------------------------------------
        */

        $movementData = [

            'account_at' =>
                $accountId,

            'ngo_id' =>
                $ngoId,

            'date_updated' =>
                $validated['date_updated'],

            'remarks' =>
                $validated['remarks']
                ?? null,

            'movement_type' =>
                (int)
                $validated['movement_type'],

        ];


        $movementResponse =
            $this->supabase()
                ->withHeaders([
                    'Prefer' =>
                        'return=representation'
                ])
                ->post(
                    $supabaseUrl .
                    '/rest/v1/inventory_movement',
                    $movementData
                );


        if (
            !$movementResponse->successful()
        ) {

            return back()->with(
                'error',
                'Failed to create inventory movement.'
            );
        }


        $movementResult =
            $movementResponse->json();


        if (
            empty($movementResult)
        ) {

            return back()->with(
                'error',
                'Movement was created but its ID could not be retrieved.'
            );
        }


        $movementId =
            $movementResult[0]['id'];


        /*
        |--------------------------------------------------------------------------
        | INSERT MOVEMENT ITEMS
        |--------------------------------------------------------------------------
        */

        foreach (
            $validated['items']
            as $item
        ) {

            $itemResponse =
                $this->supabase()
                    ->post(
                        $supabaseUrl .
                        '/rest/v1/inventory_movement_items',
                        [

                            'movement_id' =>
                                $movementId,

                            'inventory_item_id' =>
                                $item['inventory_item_id'],

                            'quantity' =>
                                (int)
                                $item['quantity'],

                        ]
                    );


            if (
                !$itemResponse->successful()
            ) {

                return back()->with(
                    'error',
                    'Movement was created, but one or more items could not be saved.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE INVENTORY QUANTITY
        |--------------------------------------------------------------------------
        |
        | 0 = Inbound  → ADD
        | 1 = Outbound → SUBTRACT
        | 2 = Pending  → NO CHANGE
        |
        */

        if (
            (int)
            $validated['movement_type']
            !== 2
        ) {

            foreach (
                $validated['items']
                as $item
            ) {

                $inventoryItem =
                    $inventoryItems->firstWhere(
                        'id',
                        $item['inventory_item_id']
                    );


                $currentQuantity =
                    (int)
                    $inventoryItem['current_quantity'];


                $movementQuantity =
                    (int)
                    $item['quantity'];


                if (
                    (int)
                    $validated['movement_type']
                    === 0
                ) {

                    $newQuantity =
                        $currentQuantity +
                        $movementQuantity;

                } else {

                    $newQuantity =
                        $currentQuantity -
                        $movementQuantity;
                }


                $updateResponse =
                    $this->supabase()->patch(
                        $supabaseUrl .
                        '/rest/v1/inventory?id=eq.' .
                        $item['inventory_item_id'],
                        [
                            'current_quantity' =>
                                $newQuantity
                        ]
                    );


                if (
                    !$updateResponse->successful()
                ) {

                    return back()->with(
                        'error',
                        'Movement saved, but inventory quantity could not be updated.'
                    );
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'inventory.movement'
            )
            ->with(
                'success',
                'Inventory movement added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW MOVEMENT DETAILS
    |--------------------------------------------------------------------------
    */

  public function show($id)
{
    $supabaseUrl = env('SUPABASE_URL');

    /*
    |--------------------------------------------------------------------------
    | 1. GET MOVEMENT
    |--------------------------------------------------------------------------
    */

    $movementResponse = $this->supabase()->get(
        $supabaseUrl . '/rest/v1/inventory_movement',
        [
            'select' => '*',
            'id' => 'eq.' . $id
        ]
    );

    if (
        !$movementResponse->successful() ||
        empty($movementResponse->json())
    ) {
        return response()->json([
            'message' => 'Movement not found.'
        ], 404);
    }

    $movement = $movementResponse->json()[0];


    /*
    |--------------------------------------------------------------------------
    | 2. GET ACCOUNT
    |--------------------------------------------------------------------------
    */

    $accountResponse = $this->supabase()->get(
        $supabaseUrl . '/rest/v1/accounts',
        [
            'select' => 'id,first_name,last_name,email',
            'id' => 'eq.' . $movement['account_at']
        ]
    );

    $encodedBy = 'Unknown User';

    if (
        $accountResponse->successful() &&
        !empty($accountResponse->json())
    ) {
        $account = $accountResponse->json()[0];

        $firstName = trim($account['first_name'] ?? '');
        $lastName = trim($account['last_name'] ?? '');

        $encodedBy = trim(
            $firstName . ' ' . $lastName
        );

        if ($encodedBy === '') {
            $encodedBy = 'Unknown User';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 3. GET MOVEMENT ITEMS
    |--------------------------------------------------------------------------
    */

    $itemsResponse = $this->supabase()->get(
        $supabaseUrl . '/rest/v1/inventory_movement_items',
        [
            'select' => '*',
            'movement_id' => 'eq.' . $id
        ]
    );

    if (!$itemsResponse->successful()) {
        return response()->json([
            'message' => 'Failed to load movement items.',
            'error' => $itemsResponse->body()
        ], 500);
    }

    $movementItems = $itemsResponse->json();


    /*
    |--------------------------------------------------------------------------
    | 4. GET INVENTORY
    |--------------------------------------------------------------------------
    |
    | inventory.id is the PRIMARY KEY.
    |
    | Example:
    |
    | inventory.id = 1
    | inventory.name = Rice
    |
    */

    $inventoryResponse = $this->supabase()->get(
        $supabaseUrl . '/rest/v1/inventory',
        [
            'select' => 'id,name,unit,category'
        ]
    );

    if (!$inventoryResponse->successful()) {
        return response()->json([
            'message' => 'Failed to load inventory.',
            'error' => $inventoryResponse->body()
        ], 500);
    }

    $inventory = $inventoryResponse->json();


    /*
    |--------------------------------------------------------------------------
    | 5. BUILD INVENTORY LOOKUP
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | The KEY is inventory.id.
    |
    | Example:
    |
    | $inventoryLookup[1] = Rice
    | $inventoryLookup[2] = Canned Goods
    | $inventoryLookup[3] = Clothes
    |
    */

    $inventoryLookup = [];

    foreach ($inventory as $inventoryItem) {

        $inventoryId = (int) $inventoryItem['id'];

        $inventoryLookup[$inventoryId] = $inventoryItem;
    }


    /*
    |--------------------------------------------------------------------------
    | 6. CONNECT MOVEMENT ITEM TO INVENTORY
    |--------------------------------------------------------------------------
    |
    | inventory_movement_items.inventory_item_id
    |                         ↓
    |                  inventory.id
    |
    */

    $items = [];

    foreach ($movementItems as $movementItem) {

        $inventoryItemId =
            (int) ($movementItem['inventory_item_id'] ?? 0);


        /*
        |--------------------------------------------------------------------------
        | FIND INVENTORY RECORD
        |--------------------------------------------------------------------------
        */

        $inventoryItem =
            $inventoryLookup[$inventoryItemId]
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | DEBUG FALLBACK
        |--------------------------------------------------------------------------
        */

        if (!$inventoryItem) {

            $items[] = [
                'id' =>
                    $movementItem['id'],

                'inventory_item_id' =>
                    $inventoryItemId,

                'quantity' =>
                    (int) ($movementItem['quantity'] ?? 0),

                'name' =>
                    'Unknown Item',

                'unit' =>
                    '',

                'category' =>
                    '',
            ];

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | ADD CORRECT INVENTORY INFORMATION
        |--------------------------------------------------------------------------
        */

        $items[] = [

            'id' =>
                $movementItem['id'],

            'inventory_item_id' =>
                $inventoryItemId,

            'quantity' =>
                (int) ($movementItem['quantity'] ?? 0),

            'name' =>
                $inventoryItem['name'] ?? 'Unknown Item',

            'unit' =>
                $inventoryItem['unit'] ?? '',

            'category' =>
                $inventoryItem['category'] ?? '',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | 7. MOVEMENT LABEL
    |--------------------------------------------------------------------------
    */

    $movementType =
        (int) ($movement['movement_type'] ?? 0);

    if ($movementType === 0) {

        $movementLabel = 'Inbound';

    } elseif ($movementType === 1) {

        $movementLabel = 'Outbound';

    } elseif ($movementType === 2) {

        $movementLabel = 'Pending';

    } else {

        $movementLabel = 'Unknown';
    }


    /*
    |--------------------------------------------------------------------------
    | 8. RETURN COMPLETE MOVEMENT
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'id' =>
            $movement['id'],

        'account_at' =>
            $movement['account_at'] ?? null,

        'ngo_id' =>
            $movement['ngo_id'] ?? null,

        'date_updated' =>
            $movement['date_updated'] ?? null,

        'remarks' =>
            $movement['remarks'] ?? '',

        'movement_type' =>
            $movementType,

        'movement_label' =>
            $movementLabel,

        'encoded_by' =>
            $encodedBy,

        'item_count' =>
            count($items),

        'total_quantity' =>
            collect($items)->sum('quantity'),

        'items' =>
            $items,
    ]);
}


    /*
    |--------------------------------------------------------------------------
    | DELETE / CANCEL MOVEMENT
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $supabaseUrl =
            env('SUPABASE_URL');


        /*
        |--------------------------------------------------------------------------
        | GET MOVEMENT
        |--------------------------------------------------------------------------
        */

        $movementResponse =
            $this->supabase()->get(
                $supabaseUrl .
                '/rest/v1/inventory_movement',
                [
                    'select' =>
                        '*',

                    'id' =>
                        'eq.' . $id
                ]
            );


        if (
            !$movementResponse->successful() ||
            empty($movementResponse->json())
        ) {

            return back()->with(
                'error',
                'Movement not found.'
            );
        }


        $movement =
            $movementResponse->json()[0];


        /*
        |--------------------------------------------------------------------------
        | GET MOVEMENT ITEMS
        |--------------------------------------------------------------------------
        */

        $itemsResponse =
            $this->supabase()->get(
                $supabaseUrl .
                '/rest/v1/inventory_movement_items',
                [
                    'select' =>
                        '*',

                    'movement_id' =>
                        'eq.' . $id
                ]
            );


        if (
            !$itemsResponse->successful()
        ) {

            return back()->with(
                'error',
                'Failed to load movement items.'
            );
        }


        $items =
            collect(
                $itemsResponse->json()
            );


        /*
        |--------------------------------------------------------------------------
        | REVERSE INVENTORY QUANTITY
        |--------------------------------------------------------------------------
        |
        | Inbound  → subtract
        | Outbound → add
        | Pending  → no change
        |
        */

        if (
            (int)
            $movement['movement_type']
            !== 2
        ) {

            foreach (
                $items
                as $item
            ) {

                $inventoryResponse =
                    $this->supabase()->get(
                        $supabaseUrl .
                        '/rest/v1/inventory',
                        [
                            'select' =>
                                'current_quantity',

                            'id' =>
                                'eq.' .
                                $item[
                                    'inventory_item_id'
                                ]
                        ]
                    );


                if (
                    !$inventoryResponse->successful() ||
                    empty(
                        $inventoryResponse->json()
                    )
                ) {

                    continue;
                }


                $currentQuantity =
                    (int)
                    $inventoryResponse
                        ->json()[0]
                        ['current_quantity'];


                $quantity =
                    (int)
                    $item['quantity'];


                /*
                |--------------------------------------------------------------------------
                | REVERSE INBOUND
                |--------------------------------------------------------------------------
                */

                if (
                    (int)
                    $movement['movement_type']
                    === 0
                ) {

                    $newQuantity =
                        $currentQuantity -
                        $quantity;

                }

                /*
                |--------------------------------------------------------------------------
                | REVERSE OUTBOUND
                |--------------------------------------------------------------------------
                */

                else {

                    $newQuantity =
                        $currentQuantity +
                        $quantity;
                }


                /*
                |--------------------------------------------------------------------------
                | PREVENT NEGATIVE
                |--------------------------------------------------------------------------
                */

                $newQuantity =
                    max(
                        0,
                        $newQuantity
                    );


                /*
                |--------------------------------------------------------------------------
                | UPDATE INVENTORY
                |--------------------------------------------------------------------------
                */

                $this->supabase()->patch(
                    $supabaseUrl .
                    '/rest/v1/inventory?id=eq.' .
                    $item[
                        'inventory_item_id'
                    ],
                    [
                        'current_quantity' =>
                            $newQuantity
                    ]
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE MOVEMENT ITEMS
        |--------------------------------------------------------------------------
        */

        $this->supabase()->delete(
            $supabaseUrl .
            '/rest/v1/inventory_movement_items',
            [
                'movement_id' =>
                    'eq.' . $id
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | DELETE MOVEMENT HEADER
        |--------------------------------------------------------------------------
        */

        $deleteResponse =
            $this->supabase()->delete(
                $supabaseUrl .
                '/rest/v1/inventory_movement',
                [
                    'id' =>
                        'eq.' . $id
                ]
            );


        if (
            !$deleteResponse->successful()
        ) {

            return back()->with(
                'error',
                'Failed to delete movement.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'inventory.movement'
            )
            ->with(
                'success',
                'Inventory movement deleted successfully.'
            );
    }
}