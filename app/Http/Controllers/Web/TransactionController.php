<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('item.category');

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('transaction_date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $transactions = $query->latest()->paginate(15);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $items = Item::where('status', 'aktif')->get();
        return view('transactions.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:masuk,keluar',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $item = Item::find($request->item_id);

        if ($request->type === 'keluar' && $item->stock < $request->quantity) {
            return back()->withErrors([
                'quantity' => 'Stock tidak mencukupi. Stock tersedia: ' . $item->stock
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            $totalPrice = $item->price * $request->quantity;

            Transaction::create([
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
            return redirect('/transactions')->with('success', 'Transaksi berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Transaksi gagal: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Transaction $transaction)
    {
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

            return redirect('/transactions')->with('success', 'Transaksi berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus transaksi: ' . $e->getMessage()]);
        }
    }
}