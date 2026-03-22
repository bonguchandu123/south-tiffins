<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParlourTable;
use App\Models\Order;

class TableController extends Controller
{
    public function index()
    {
        return view('admin.tables');
    }

    public function getTables()
    {
        $tables = ParlourTable::orderBy('table_number')->get();

        return response()->json([
            'success' => true,
            'tables'  => $tables
        ]);
    }

    public function getActiveTables()
    {
        $tables = ParlourTable::where('is_active', true)
            ->orderBy('table_number')
            ->get();

        return response()->json([
            'success' => true,
            'tables'  => $tables
        ]);
    }

    public function addTable(Request $request)
    {
        $existing = ParlourTable::where('table_number', $request->table_number)->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Table number already exists'
            ]);
        }

        $table = ParlourTable::create([
            'table_number' => $request->table_number,
            'is_active'    => true
        ]);

        $menuUrl = url('/menu?table=' . $table->id);

        $table->update([
            'qr_code_url' => $menuUrl
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Table added successfully',
            'table'   => $table->fresh()
        ]);
    }

    public function deleteTable(Request $request)
    {
        $table = ParlourTable::find($request->id);

        if (!$table) {
            return response()->json([
                'success' => false,
                'message' => 'Table not found'
            ]);
        }

        $activeOrders = Order::where('table_id', $table->id)
            ->whereIn('status', ['PENDING', 'PREPARING', 'READY'])
            ->count();

        if ($activeOrders > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete table with active orders'
            ]);
        }

        $table->delete();

        return response()->json([
            'success' => true,
            'message' => 'Table deleted successfully'
        ]);
    }

    public function toggleTable(Request $request)
    {
        $table = ParlourTable::find($request->id);

        if (!$table) {
            return response()->json([
                'success' => false,
                'message' => 'Table not found'
            ]);
        }

        $table->update(['is_active' => !$table->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $table->is_active,
            'message'   => $table->is_active ? 'Table activated' : 'Table deactivated'
        ]);
    }

    public function getQR(Request $request)
    {
        $table = ParlourTable::find($request->id);

        if (!$table) {
            return response()->json([
                'success' => false,
                'message' => 'Table not found'
            ]);
        }

        $menuUrl = url('/menu?table=' . $table->id);

        $table->update(['qr_code_url' => $menuUrl]);

        return response()->json([
            'success'      => true,
            'menu_url'     => $menuUrl,
            'table_number' => $table->table_number
        ]);
    }
}