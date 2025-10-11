@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
<div class="px-4 sm:px-0">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Barang</h2>
        <a href="{{ route('items.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
            <i class="fas fa-plus mr-2"></i>Tambah Barang
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stok</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Harga</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($items as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full">{{ $item->stock }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        <a href="{{ route('items.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
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
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data barang</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
@endsection
