@extends('layouts.app-blue')
@section('title','Senarai Kategori Aduan')

@section('content')
<div class="max-w-5xl mx-auto p-4 bg-white rounded">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Senarai Kategori Aduan</h1>
    <a href="{{ route('aduan.kategori.create') }}" class="px-3 py-2 bg-sky-600 text-white rounded">Tambah Kategori</a>
  </div>

  @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded">{{ session('success') }}</div>
  @endif

  <form method="GET" class="mb-3">
    <div class="flex gap-2">
      <input type="text" name="q" value="{{ $q ?? '' }}" class="border p-2 rounded w-full" placeholder="Cari kategori...">
      <button class="px-3 py-2 border rounded">Cari</button>
    </div>
  </form>

  <div class="overflow-x-auto">
    <table class="w-full table-auto border-collapse">
      <thead>
        <tr class="text-left">
          <th class="p-2">Nama</th>
          <th class="p-2">Keterangan</th>
          <th class="p-2">Status</th>
          <th class="p-2">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kategoris as $kat)
          <tr class="border-t">
            <td class="p-2 font-medium">{{ $kat->name }}</td>
            <td class="p-2">{{ \Illuminate\Support\Str::limit($kat->description,80) }}</td>
            <td class="p-2">{{ $kat->status_label }}</td>
            <td class="p-2">
              <a href="{{ route('aduan.kategori.edit', $kat) }}" class="px-2 py-1 border rounded">Edit</a>
              <form action="{{ route('aduan.kategori.destroy', $kat) }}" method="POST" class="inline" onsubmit="return confirm('Padam kategori?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-2 py-1 border rounded ml-1">Padam</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="p-4 text-center">Tiada kategori ditemui.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $kategoris->links() }}</div>
</div>
@endsection
```

6b) create.blade.php (also used for edit with minor differences)

```blade
@extends('layouts.app-blue')
@section('title','Tambah Kategori Aduan')

@section('content')
<div class="max-w-3xl mx-auto p-4 bg-white rounded">
  <h1 class="text-xl font-semibold mb-4">Tambah Kategori Aduan</h1>

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('aduan.kategori.store') }}" method="POST">
    @csrf

    <div class="mb-3">
      <label class="block font-medium">Nama Kategori</label>
      <input type="text" name="name" value="{{ old('name') }}" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-3">
      <label class="block font-medium">Keterangan</label>
      <textarea name="description" class="w-full border p-2 rounded" rows="4">{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
      <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" checked>
        <span>Aktif</span>
      </label>
    </div>

    <div class="flex gap-2">
      <button class="px-3 py-2 bg-sky-600 text-white rounded">Simpan</button>
      <a href="{{ route('aduan.kategori') }}" class="px-3 py-2 border rounded">Batal</a>
    </div>
  </form>
</div>
@endsection
```

6c) edit.blade.php

```blade
@extends('layouts.app-blue')
@section('title','Kemaskini Kategori Aduan')

@section('content')
<div class="max-w-3xl mx-auto p-4 bg-white rounded">
  <h1 class="text-xl font-semibold mb-4">Kemaskini Kategori</h1>

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('aduan.kategori.update', $kategori) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="block font-medium">Nama Kategori</label>
      <input type="text" name="name" value="{{ old('name', $kategori->name) }}" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-3">
      <label class="block font-medium">Keterangan</label>
      <textarea name="description" class="w-full border p-2 rounded" rows="4">{{ old('description', $kategori->description) }}</textarea>
    </div>

    <div class="mb-3">
      <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" {{ $kategori->is_active ? 'checked' : '' }}>
        <span>Aktif</span>
      </label>
    </div>

    <div class="flex gap-2">
      <button class="px-3 py-2 bg-sky-600 text-white rounded">Simpan Perubahan</button>
      <a href="{{ route('aduan.kategori') }}" class="px-3 py-2 border rounded">Batal</a>
    </div>
  </form>
</div>
@endsection