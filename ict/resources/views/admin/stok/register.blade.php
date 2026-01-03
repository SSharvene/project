@extends('layouts.app')

@section('title', isset($stok) ? 'Kemaskini Stok' : 'Daftar Stok')

@section('content')
<div class="p-6 max-w-3xl">
  <h1 class="text-xl font-semibold mb-4">{{ isset($stok) ? 'Kemaskini Stok' : 'Daftar Stok' }}</h1>

  @if($errors->any())
    <div class="mb-4 text-sm text-red-700 bg-red-50 p-3 rounded">
      <ul class="list-disc ml-5">
        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ isset($stok) ? route('admin.stok.update', $stok) : route('admin.stok.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-4">
    @csrf
    @if(isset($stok)) @method('PUT') @endif

    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
      <input name="nama" value="{{ old('nama', $stok->nama ?? '') }}" class="w-full border rounded px-3 py-2" required>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Kod</label>
        <input name="kod" value="{{ old('kod', $stok->kod ?? '') }}" class="w-full border rounded px-3 py-2">
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
        <input name="kategori" value="{{ old('kategori', $stok->kategori ?? '') }}" class="w-full border rounded px-3 py-2">
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Kuantiti</label>
        <input type="number" min="0" name="kuantiti" value="{{ old('kuantiti', $stok->kuantiti ?? 0) }}" class="w-full border rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi</label>
        <input name="lokasi" value="{{ old('lokasi', $stok->lokasi ?? '') }}" class="w-full border rounded px-3 py-2">
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Nota</label>
      <textarea name="nota" rows="3" class="w-full border rounded px-3 py-2">{{ old('nota', $stok->nota ?? '') }}</textarea>
    </div>

    <div class="flex justify-between items-center">
      <a href="{{ route('admin.stok.index') }}" class="text-slate-600 hover:underline">Batal</a>
      <button type="submit" class="bg-sky-600 text-white px-4 py-2 rounded">{{ isset($stok) ? 'Simpan' : 'Daftar' }}</button>
    </div>
  </form>
</div>
@endsection
