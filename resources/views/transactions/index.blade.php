@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="px-4 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Transaksi</h2>
        <a href="{{ route('transactions.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
            <i class="fas fa-plus mr-2"></i>Tambah Transaksi
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $transaction->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $transaction->item->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-center">{{ $transaction->quantity }}</td>
                    <td class="px-6 py-4 text-sm text-center capitalize">
                        <span class="px-2 py-1 rounded-full {{ $transaction->type == 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transaction->type == 'in' ? 'Masuk' : 'Keluar' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-center text-gray-900">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-center">
                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
