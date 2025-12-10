@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-xl">
    <p class="text-gray-500 mb-6">Ubah detail kategori</p>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="/categories/{{ $category->id }}">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-gray-400">(opsional)</span></label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="/categories" class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-800 rounded-xl hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="bg-primary hover:bg-purple-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection