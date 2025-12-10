@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="max-w-xl">
    <p class="text-gray-500 mb-6">Ubah detail barang</p>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="/items/{{ $item->id }}">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">ID Barang</label>
                <input type="text" value="{{ $item->code }}" disabled
                    class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-500">
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                <input type="text" name="name" value="{{ old('name', $item->name) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category_id" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                    <input type="number" value="{{ $item->stock }}" disabled
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-500">
                    <p class="text-xs text-gray-400 mt-1">Stok diubah via transaksi</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $item->price) }}" min="0" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="aktif" {{ old('status', $item->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $item->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="/items" class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-800 rounded-xl hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="bg-primary hover:bg-purple-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
