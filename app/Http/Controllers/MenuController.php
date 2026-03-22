<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Category;

class MenuController extends Controller
{
    public function customerMenu(Request $request)
    {
        $tableId = $request->query('table');
        $table   = null;

        if ($tableId) {
            $table = \App\Models\ParlourTable::find($tableId);
        }

        return view('customer.menu', compact('table'));
    }

    public function adminMenu()
    {
        return view('admin.menu');
    }

    public function getMenu()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['menuItems' => function ($q) {
                $q->where('is_available', true)
                  ->orderBy('sort_order');
            }])
            ->get()
            ->filter(fn($c) => $c->menuItems->count() > 0)
            ->values();

        return response()->json([
            'success'    => true,
            'categories' => $categories
        ]);
    }

    public function getAllMenu()
    {
        $categories = Category::orderBy('sort_order')
            ->with(['menuItems' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->get();

        return response()->json([
            'success'    => true,
            'categories' => $categories
        ]);
    }

    public function addItem(Request $request)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $item = MenuItem::create([
            'category_id'     => $request->category_id,
            'name_en'         => $request->name_en,
            'name_te'         => $request->name_te,
            'description_en'  => $request->description_en,
            'description_te'  => $request->description_te,
            'price'           => $request->price,
            'image_url'       => $request->image_url,
            'is_veg'          => $request->is_veg ?? true,
            'is_available'    => true,
            'sort_order'      => $request->sort_order ?? 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item added successfully',
            'item'    => $item
        ]);
    }

    public function updateItem(Request $request)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $item = MenuItem::findOrFail($request->id);
        $item->update([
            'category_id'    => $request->category_id,
            'name_en'        => $request->name_en,
            'name_te'        => $request->name_te,
            'description_en' => $request->description_en,
            'description_te' => $request->description_te,
            'price'          => $request->price,
            'image_url'      => $request->image_url,
            'is_veg'         => $request->is_veg ?? true,
            'sort_order'     => $request->sort_order ?? 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item updated successfully',
            'item'    => $item
        ]);
    }

    public function deleteItem(Request $request)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $item = MenuItem::findOrFail($request->id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully'
        ]);
    }

    public function toggleItem(Request $request)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $item = MenuItem::findOrFail($request->id);
        $item->update(['is_available' => !$item->is_available]);

        return response()->json([
            'success'      => true,
            'is_available' => $item->is_available
        ]);
    }
}