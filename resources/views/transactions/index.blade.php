@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <p class="text-gray-500">Riwayat transaksi barang masuk dan keluar</p>
        <a href="/transactions/create" class="bg-primary hover:bg-purple-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Transaksi
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="/transactions" class="flex flex-wrap gap-3">
            <select name="type" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">Semua Tipe</option>
                <option value="masuk" {{ request('type') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ request('type') == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <input type="date" name="end_date" value="{{ request('end_date') }}" 
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-5 py-2.5 rounded-xl transition">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50">
                    <th class="px-6 py-4 font-medium">No</th>
                    <th class="px-6 py-4 font-medium">Tanggal</th>
                    <th class="px-6 py-4 font-medium">Kode</th>
                    <th class="px-6 py-4 font-medium">Barang</th>
                    <th class="px-6 py-4 font-medium text-center">Tipe</th>
                    <th class="px-6 py-4 font-medium text-center">Qty</th>
                    <th class="px-6 py-4 font-medium text-right">Total</th>
                    <th class="px-6 py-4 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($transactions as $index => $transaction)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">{{ $transactions->firstItem() + $index }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-primary font-mono text-xs font-medium">{{ $transaction->transaction_code }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $transaction->item->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-xs rounded-lg font-medium {{ $transaction->type == 'masuk' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-gray-800">{{ $transaction->quantity }}</td>
                    <td class="px-6 py-4 text-right text-gray-800">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right">
                        <form action="/transactions/{{ $transaction->id }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-400">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $transactions->links() }}</div>
</div>
@endsection
