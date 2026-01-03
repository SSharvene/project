@extends('layouts.app')

@section('title', 'Maklumat Permohonan Stok')

@section('content')
<div class="p-6 max-w-3xl">
  <a href="{{ route('stok.request.index') }}" class="text-sky-600 hover:underline text-sm">
    ← Kembali ke Senarai Permohonan
  </a>

  <div class="bg-white rounded-lg shadow p-6 mt-4 space-y-4">
    <h1 class="text-xl font-semibold mb-3">Maklumat Permohonan Stok</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <div>
        <div class="text-slate-500 text-sm">Nama Stok</div>
        <div class="font-medium">{{ $requestItem->stok->nama ?? '-' }}</div>
      </div>
      <div>
        <div class="text-slate-500 text-sm">Kuantiti</div>
        <div class="font-medium">{{ $requestItem->quantity }}</div>
      </div>

      <div>
        <div class="text-slate-500 text-sm">Nama Pemohon</div>
        <div class="font-medium">{{ $requestItem->requester_name }}</div>
      </div>
      <div>
        <div class="text-slate-500 text-sm">Jabatan / Unit</div>
        <div class="font-medium">{{ $requestItem->jabatan ?? '-' }}</div>
      </div>
    </div>

    <div>
      <div class="text-slate-500 text-sm mb-1">Tujuan Permohonan</div>
      <div class="whitespace-pre-line">{{ $requestItem->purpose }}</div>
    </div>

    <div>
      <div class="text-slate-500 text-sm mb-1">Status</div>
      @php
        $color = match($requestItem->status) {
          'approved' => 'bg-green-100 text-green-700',
          'rejected' => 'bg-red-100 text-red-700',
          'processing' => 'bg-yellow-100 text-yellow-700',
          default => 'bg-slate-100 text-slate-700',
        };
      @endphp
      <span class="px-2 py-1 rounded text-xs font-medium {{ $color }}">
        {{ ucfirst($requestItem->status) }}
      </span>
    </div>

    @if ($requestItem->admin_note)
      <div>
        <div class="text-slate-500 text-sm mb-1">Catatan Admin</div>
        <div class="bg-slate-50 border rounded p-3 text-sm">{{ $requestItem->admin_note }}</div>
      </div>
    @endif

    @if ($requestItem->attachment)
      <div>
        <div class="text-slate-500 text-sm mb-1">Lampiran</div>
        <a href="{{ asset('storage/'.$requestItem->attachment) }}" target="_blank" class="text-sky-600 hover:underline">
          Muat turun / Lihat
        </a>
      </div>
    @endif

    <div class="text-sm text-slate-400">
      Dihantar pada {{ $requestItem->created_at->format('d/m/Y h:i A') }}
      @if ($requestItem->handled_at)
        <br>Kemaskini terakhir oleh admin pada {{ $requestItem->handled_at->format('d/m/Y h:i A') }}
      @endif
    </div>
  </div>
</div>
@endsection
