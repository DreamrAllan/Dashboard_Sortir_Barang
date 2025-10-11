@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="px-4 sm:px-0">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Transaksi Baru</h2>
    </div>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('transactions.store') }}">
            @csrf

            {{-- Pilih Barang --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Barang *</label>
                <select name="item_id" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Barang --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }} (Stok: {{ $item->stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Jumlah Transaksi --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah *</label>
                <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- Tipe Transaksi (Masuk/Keluar) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi *</label>
                <select name="type" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="masuk" {{ old('type') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                    <option value="keluar" {{ old('type') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                </select>
            </div>

            {{-- Keterangan --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea name="note" rows="3"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('note') }}</textarea>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end space-x-3">
                <a href="{{ route('transactions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
