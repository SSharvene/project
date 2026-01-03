@extends('layouts.app')

@section('title', 'Kemaskini Aset — JAKOA ICT')

@section('content')
<div class="p-6 max-w-3xl">
    <h1 class="text-xl font-semibold mb-4">Kemaskini Aset</h1>

    @if($errors->any())
      <div class="mb-4 text-sm text-red-700 bg-red-50 p-3 rounded">
        <ul class="list-disc ml-5">
          @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.assets.update', $asset) }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Aset</label>
            <input value="{{ old('nama_aset', $asset->nama_aset) }}" type="text" name="nama_aset" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
            <input value="{{ old('kategori', $asset->kategori) }}" type="text" name="kategori" class="w-full border border-slate-300 rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
            <select name="status" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                <option value="Aktif" {{ old('status', $asset->status)=='Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Tidak Aktif" {{ old('status', $asset->status)=='Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nota</label>
            <textarea name="nota" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-2">{{ old('nota', $asset->nota) }}</textarea>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('admin.assets.index') }}" class="text-slate-600 hover:underline">Batal</a>
            <div class="flex items-center gap-2">
                <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Padam aset ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded">Padam</button>
                </form>

                <button type="submit" form="the-edit-form" class="bg-sky-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </div>
    </form>
</div>
@endsection
