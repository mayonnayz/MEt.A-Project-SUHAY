<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\inventory;


class inventory_controller extends Controller
{
public function index()
{
    $lastMovementSub = DB::table('inventory_movement_items as imi')
        ->join('inventory_movement as im', 'imi.movement_id', '=', 'im.id')
        ->select('imi.inventory_item_id', DB::raw('MAX(im.date_updated) as last_movement_date'))
        ->groupBy('imi.inventory_item_id');

    $inventory = DB::table('inventory')
        ->leftJoinSub($lastMovementSub, 'lm', function ($join) {
            $join->on('inventory.id', '=', 'lm.inventory_item_id');
        })
        ->select(
            'inventory.id',
            'inventory.name',
            'inventory.category',
            'inventory.current_quantity',
            'inventory.unit',
            'inventory.minimum_threshold',
            'lm.last_movement_date'
        )
        ->paginate(30);

    $categories = DB::table('inventory')
        ->select('category')
        ->distinct()
        ->pluck('category');

    $inventory->getCollection()->transform(function ($item) {

        if ($item->current_quantity == 0) {
            $item->stock_status = 'No Stock';
        } elseif ($item->current_quantity <= $item->minimum_threshold) {
            $item->stock_status = 'Low Stock';
        } else {
            $item->stock_status = 'In Stock';
        }

        return $item;
    });

    return view('inventory-master-list', compact('inventory', 'categories'));
}

   public function update(Request $request, $id)
{
    $data = $request->json()->all();

    DB::table('inventory')
        ->where('id', $id)
        ->update([
            'name' => $data['item_name'] ?? null,
            'category' => $data['category'] ?? null,
            'current_quantity' => $data['quantity'] ?? null,
            'unit' => $data['unit'] ?? null,
            'minimum_threshold' => $data['threshold'] ?? null,
        ]);

    return response()->json([
        'message' => 'Inventory updated successfully',
        'data' => $data
    ]);
}
}