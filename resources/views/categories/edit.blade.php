@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="px-4 sm:px-0">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Kategori</h2>
    </div>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('categories.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection