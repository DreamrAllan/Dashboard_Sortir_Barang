<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('item.category');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('transaction_date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $transactions = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:masuk,keluar',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $item = Item::find($request->item_id);

        if ($request->type === 'keluar' && $item->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock tidak mencukupi. Stock tersedia: ' . $item->stock
            ], 422);
        }

        DB::beginTransaction();
        try {
            $totalPrice = $item->price * $request->quantity;

            $transaction = Transaction::create([
                'transaction_code' => Transaction::generateCode($request->type),
                'item_id' => $request->item_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'total_price' => $totalPrice,
                'notes' => $request->notes,
                'transaction_date' => $request->transaction_date,
            ]);

            if ($request->type === 'masuk') {
                $item->increment('stock', $request->quantity);
            } else {
                $item->decrement('stock', $request->quantity);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditambahkan',
                'data' => $transaction->load('item.category')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with('item.category')->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            $item = $transaction->item;
            
            if ($transaction->type === 'masuk') {
                $item->decrement('stock', $transaction->quantity);
            } else {
                $item->increment('stock', $transaction->quantity);
            }

            $transaction->delete();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}