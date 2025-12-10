@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <p class="text-gray-500">Kelola kategori barang Anda</p>
        <a href="/categories/create" class="bg-primary hover:bg-purple-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50">
                    <th class="px-6 py-4 font-medium">No</th>
                    <th class="px-6 py-4 font-medium">Nama Kategori</th>
                    <th class="px-6 py-4 font-medium">Deskripsi</th>
                    <th class="px-6 py-4 font-medium text-center">Jumlah Barang</th>
                    <th class="px-6 py-4 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($categories as $index => $category)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">{{ $categories->firstItem() + $index }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $category->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-xs bg-purple-100 text-purple-700 rounded-lg font-medium">{{ $category->items_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="/categories/{{ $category->id }}/edit" class="px-3 py-1.5 text-xs bg-cyan-100 text-cyan-700 rounded-lg hover:bg-cyan-200 transition">Edit</a>
                            <form action="/categories/{{ $category->id }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-xs bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">Belum ada kategori</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $categories->links() }}</div>
</div>
@endsection