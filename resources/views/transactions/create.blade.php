@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="max-w-xl">
    <p class="text-gray-500 mb-6">Catat transaksi barang masuk atau keluar</p>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="/transactions">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang</label>
                <select name="item_id" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Pilih Barang</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }} (Stok: {{ $item->stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi</label>
                    <select name="type" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Pilih Tipe</option>
                        <option value="masuk" {{ old('type') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ old('type') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi</label>
                <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan <span class="text-gray-400">(opsional)</span></label>
                <textarea name="notes" rows="2"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="/transactions" class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-800 rounded-xl hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="bg-primary hover:bg-purple-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
