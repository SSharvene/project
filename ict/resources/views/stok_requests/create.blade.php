@extends('layouts.app')

@section('title','Mohon Stok')

@section('content')
<div class="p-6 max-w-2xl" x-data="{ uploading:false, fileName:'' }">
  <h1 class="text-xl font-semibold mb-4">Mohon Stok</h1>

  @if($errors->any())
    <div class="mb-4 text-sm text-red-700 bg-red-50 p-3 rounded">
      <ul class="list-disc ml-5">
        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('stok.request.store') }}" method="POST" enctype="multipart/form-data" @submit="uploading=true" class="bg-white rounded-lg shadow p-6 space-y-4">
    @csrf

    <div>
      <label class="block text-sm font-medium mb-1">Pilih Stok (optional)</label>
      <select name="stok_id" class="w-full border rounded px-3 py-2">
        <option value="">-- Pilih Stok (jika berkaitan) --</option>
        @foreach($stoks as $s)
          <option value="{{ $s->id }}" {{ old('stok_id') == $s->id ? 'selected' : '' }}>
            {{ $s->nama }} ({{ $s->kod ?? '—' }}) — {{ $s->kuantiti ?? 0 }} ada
          </option>
        @endforeach
      </select>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <div>
        <label class="block text-sm font-medium mb-1">Nama Pemohon</label>
        <input name="requester_name" value="{{ old('requester_name', $prefill['requester_name'] ?? '') }}" class="w-full border rounded px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Jabatan / Unit</label>
        <input name="jabatan" value="{{ old('jabatan', $prefill['jabatan'] ?? '') }}" class="w-full border rounded px-3 py-2">
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Kuantiti</label>
      <input type="number" min="1" name="quantity" value="{{ old('quantity', 1) }}" class="w-full border rounded px-3 py-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Tujuan / Kegunaan</label>
      <textarea name="purpose" rows="4" class="w-full border rounded px-3 py-2" required>{{ old('purpose') }}</textarea>
    </div>

    <div x-data @change="$el.querySelector('input[type=file]') && (fileName = $el.querySelector('input[type=file]').files[0]?.name || '')">
      <label class="block text-sm font-medium mb-1">Lampiran (pilihan)</label>
      <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" @change="fileName = $event.target.files[0]?.name ?? ''" class="w-full">
      <div class="text-xs text-slate-400 mt-1">Max 5MB. Hanya jika perlu.</div>
      <div class="text-xs mt-1" x-text="fileName"></div>
    </div>

    <div class="flex justify-between items-center">
      <a href="{{ route('stok.request.index') }}" class="text-slate-600 hover:underline">Batal</a>
      <button type="submit" class="bg-sky-600 text-white px-4 py-2 rounded" :disabled="uploading">
        <span x-show="!uploading">Hantar Permohonan</span>
        <span x-show="uploading">Menghantar…</span>
      </button>
    </div>
  </form>
</div>
@endsection
