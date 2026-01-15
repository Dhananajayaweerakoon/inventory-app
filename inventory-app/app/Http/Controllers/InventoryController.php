<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        // Requirement 5: Search functionality
        $query = Item::query();
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Dashboard', [
            'items' => $query->latest()->get(),
            'filters' => $request->only(['search']),
        ]);
    }

    // Requirement 1: Add new items (One or Many)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string|max:255',
            'items.*.unit' => 'required|string|max:10',
            'items.*.quantity' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $itemData) {
                $item = Item::create($itemData);
                // Log the initial addition
                InventoryLog::create([
                    'item_id' => $item->id,
                    'user_id' => auth()->id(),
                    'type' => 'add',
                    'quantity' => $itemData['quantity'],
                    'note' => 'Initial Stock',
                ]);
            }
        });

        return redirect()->back();
    }

    // Requirement 3 & 4: Deduct/Add Stock and Track History
    public function updateStock(Request $request)
    {
        $validated = $request->validate([
            'stock_changes' => 'required|array',
            'stock_changes.*.id' => 'required|exists:items,id',
            'stock_changes.*.quantity' => 'required|numeric|min:0.01',
            'type' => 'required|in:add,deduct',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['stock_changes'] as $change) {
                $item = Item::find($change['id']);

                if ($validated['type'] === 'add') {
                    $item->increment('quantity', $change['quantity']);
                } else {
                    // Prevent negative stock
                    if ($item->quantity < $change['quantity']) {
                        throw new \Exception("Not enough stock for {$item->name}");
                    }
                    $item->decrement('quantity', $change['quantity']);
                }

                InventoryLog::create([
                    'item_id' => $item->id,
                    'user_id' => auth()->id(),
                    'type' => $validated['type'],
                    'quantity' => $change['quantity'],
                ]);
            }
        });

        return redirect()->back();
    }

    // Requirement 4: View History
    public function history(Item $item)
    {
        return Inertia::render('History', [
            'item' => $item,
            'logs' => $item->logs()->with('user')->get()
        ]);
    }
}