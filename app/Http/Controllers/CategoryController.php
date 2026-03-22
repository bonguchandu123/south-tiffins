<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success'    => true,
            'categories' => $categories
        ]);
    }

    public function addCategory(Request $request)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $category = Category::create([
            'name_en'    => $request->name_en,
            'name_te'    => $request->name_te,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => true
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Category added',
            'category' => $category
        ]);
    }

    public function updateCategory(Request $request)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $category = Category::findOrFail($request->id);
        $category->update([
            'name_en'    => $request->name_en,
            'name_te'    => $request->name_te,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->is_active ?? true
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Category updated',
            'category' => $category
        ]);
    }
}