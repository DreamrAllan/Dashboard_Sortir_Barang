@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 sm:px-0">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <i class="fas fa-box text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Barang</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalItems }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-cubes text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Stock</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalStock) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Nilai</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-tags text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Kategori</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-arrow-down text-green-600"></i> Barang Masuk Hari Ini
            </h3>
            <p class="text-3xl font-bold text-green-600">{{ $todayIn }} unit</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-arrow-up text-red-600"></i> Barang Keluar Hari Ini
            </h3>
            <p class="text-3xl font-bold text-red-600">{{ $todayOut }} unit</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-history"></i> Transaksi Terakhir
                </h3>
            </div>
            <div class="p-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Kode</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Barang</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tipe</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                        <tr class="border-b">
                            <td class="px-4 py-2 text-sm">{{ $transaction->transaction_code }}</td>
                            <td class="px-4 py-2 text-sm">{{ $transaction->item->name }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded-full {{ $transaction->type == 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-right">{{ $transaction->quantity }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i> Stock Menipis
                </h3>
            </div>
            <div class="p-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Barang</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Kategori</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockItems as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2 text-sm">{{ $item->name }}</td>
                            <td class="px-4 py-2 text-sm">{{ $item->category->name }}</td>
                            <td class="px-4 py-2 text-sm text-right">
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                                    {{ $item->stock }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500">Semua stock aman</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection