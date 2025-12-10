@extends('layouts.app')

@section('title', 'Barang')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <p class="text-gray-500">Kelola data barang Anda</p>
        <a href="/items/create" class="bg-primary hover:bg-purple-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Barang
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="/items" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode..."
                class="flex-1 min-w-[200px] border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <select name="category_id" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-5 py-2.5 rounded-xl transition">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50">
                    <th class="px-6 py-4 font-medium">No</th>
                    <th class="px-6 py-4 font-medium">ID Barang</th>
                    <th class="px-6 py-4 font-medium">Nama Barang</th>
                    <th class="px-6 py-4 font-medium">Kategori</th>
                    <th class="px-6 py-4 font-medium text-center">Stok</th>
                    <th class="px-6 py-4 font-medium text-right">Harga</th>
                    <th class="px-6 py-4 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($items as $index => $item)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">{{ $items->firstItem() + $index }}</td>
                    <td class="px-6 py-4 text-primary font-mono text-xs font-medium">{{ $item->code }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $item->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $item->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-xs rounded-lg font-medium {{ $item->stock < 10 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $item->stock }}</span>
                    </td>
                    <td class="px-6 py-4 text-right text-gray-800">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="/items/{{ $item->id }}/edit" class="px-3 py-1.5 text-xs bg-cyan-100 text-cyan-700 rounded-lg hover:bg-cyan-200 transition">Edit</a>
                            <form action="/items/{{ $item->id }}" method="POST" class="inline" onsubmit="return confirm('Hapus barang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-xs bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada barang</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $items->links() }}</div>
</div>
@endsection
