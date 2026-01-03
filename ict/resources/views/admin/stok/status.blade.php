@extends('layouts.app')

@section('title','Butiran Permohonan')

@section('content')
<div class="p-6 max-w-3xl">
  <h1 class="text-xl font-semibold mb-4">Butiran Permohonan #{{ $requestItem->id }}</h1>

  <div class="bg-white rounded-lg shadow p-4 mb-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <div>
        <div class="text-sm text-slate-500">Pemohon</div>
        <div class="font-medium">{{ $requestItem->requester_name }}</div>
      </div>

      <div>
        <div class="text-sm text-slate-500">Jabatan</div>
        <div class="font-medium">{{ $requestItem->jabatan }}</div>
      </div>

      <div>
        <div class="text-sm text-slate-500">Stok</div>
        <div class="font-medium">{{ optional($requestItem->stok)->nama ?? '—' }}</div>
      </div>

      <div>
        <div class="text-sm text-slate-500">Kuantiti Diminta</div>
        <div class="font-medium">{{ $requestItem->quantity }}</div>
      </div>

      <div class="col-span-2">
        <div class="text-sm text-slate-500">Tujuan</div>
        <div class="font-medium">{{ $requestItem->purpose }}</div>
      </div>
    </div>
  </div>

  <form action="{{ route('admin.stok.requests.update_status', $requestItem) }}" method="POST" class="bg-white rounded-lg shadow p-4">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <div>
        <label class="block text-sm font-medium mb-1">Status</label>
        <select name="status" class="w-full border rounded px-3 py-2">
          <option value="pending" {{ $requestItem->status=='pending' ? 'selected' : '' }}>Pending</option>
          <option value="approved" {{ $requestItem->status=='approved' ? 'selected' : '' }}>Approved</option>
          <option value="rejected" {{ $requestItem->status=='rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Kuantiti Diluluskan</label>
        <input type="number" min="0" name="approved_quantity" value="{{ old('approved_quantity', $requestItem->approved_quantity ?? $requestItem->quantity) }}" class="w-full border rounded px-3 py-2">
      </div>
    </div>

    <div class="mt-3">
      <label class="block text-sm font-medium mb-1">Nota Pentadbir</label>
      <textarea name="admin_note" rows="3" class="w-full border rounded px-3 py-2">{{ old('admin_note', $requestItem->admin_note) }}</textarea>
    </div>

    <div class="mt-4 flex justify-between items-center">
      <a href="{{ route('admin.stok.requests') }}" class="text-slate-600 hover:underline">Kembali</a>
      <button type="submit" class="bg-sky-600 text-white px-4 py-2 rounded">Simpan</button>
    </div>
  </form>
</div>
@endsection
