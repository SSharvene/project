@extends('layouts.app')

@section('title', 'Senarai Aset Sewaan — JAKOA ICT')

@section('content')
<div class="p-6">
  @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
  @endif

  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
    <div>
      <h1 class="text-2xl font-semibold">Senarai Aset Sewaan</h1>
      <div class="text-sm text-slate-400 mt-1">Urus aset: tambah, kemaskini atau padam rekod aset.</div>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('admin.assets.create') }}" class="inline-flex items-center gap-2 bg-sky-600 text-white px-4 py-2 rounded shadow hover:bg-sky-700">
        + Tambah Aset
      </a>

      <a href="{{ route('admin.assets.export') }}" class="inline-flex items-center gap-2 bg-white border px-3 py-2 rounded hover:bg-slate-50">
        Laporan
      </a>
    </div>
  </div>

  <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
    <form method="GET" class="flex items-center gap-2 md:col-span-2">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, jenama, kategori, no siri..." class="flex-1 border rounded px-3 py-2" />
      <button class="px-3 py-2 bg-slate-100 rounded">Cari</button>
    </form>

    <form method="GET" class="flex items-center gap-2 justify-end">
      <label class="text-sm text-slate-600">Per halaman</label>
      <select name="per_page" onchange="this.form.submit()" class="border rounded p-1">
        @foreach([10,25,50] as $size)
          <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
        @endforeach
      </select>
    </form>
  </div>

  {{-- Desktop table --}}
  <div class="hidden md:block bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-slate-700">
      <thead>
        <tr class="bg-slate-50 text-slate-600">
          <th class="px-4 py-3 text-left">#</th>
          <th class="px-4 py-3 text-left">Aset</th>
          <th class="px-4 py-3 text-left">Kategori</th>
          <th class="px-4 py-3 text-left">Jenama</th>
          <th class="px-4 py-3 text-left">No Siri</th>
          <th class="px-4 py-3 text-left">Bilangan</th>
          <th class="px-4 py-3 text-left">Status</th>
          <th class="px-4 py-3 text-left">Tarikh</th>
          <th class="px-4 py-3 text-left">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        @forelse($assets as $asset)
          <tr class="border-b hover:bg-slate-50">
            <td class="px-4 py-3">{{ $asset->id }}</td>

            {{-- asset & thumbnail --}}
            <td class="px-4 py-3 flex items-center gap-3">
              <div class="h-12 w-16 bg-slate-100 rounded overflow-hidden flex items-center justify-center">
                @if(!empty($asset->gambar))
                  <img src="{{ asset('storage/'.$asset->gambar) }}" alt="gambar" class="object-cover h-full w-full">
                @else
                  <svg class="h-8 w-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-width="1.2" d="M3 7h18v13H3zM8 3v4"/>
                    <path stroke-width="1.2" d="M8 11l2 2 4-4 4 4"/>
                  </svg>
                @endif
              </div>
              <div>
                <div class="font-medium">{{ $asset->nama_aset }}</div>
                <div class="text-xs text-slate-400">{{ Str::limit($asset->keterangan,40) }}</div>
              </div>
            </td>

            <td class="px-4 py-3">{{ $asset->kategori ?? '—' }}</td>
            <td class="px-4 py-3">{{ $asset->jenama ?? '—' }}</td>
            <td class="px-4 py-3">{{ $asset->no_siri ?? '—' }}</td>
            <td class="px-4 py-3">{{ $asset->bilangan ?? 0 }}</td>

            <td class="px-4 py-3">
              @php
                $badge = match($asset->status) {
                  'Aktif' => 'bg-green-100 text-green-700',
                  'Dalam Penyelenggaraan' => 'bg-yellow-100 text-yellow-700',
                  'Tidak Aktif' => 'bg-red-100 text-red-700',
                  'Dipinjam' => 'bg-indigo-100 text-indigo-700',
                  default => 'bg-slate-100 text-slate-700'
                };
              @endphp
              <span class="px-2 py-1 rounded text-xs font-medium {{ $badge }}">{{ $asset->status }}</span>
            </td>

            <td class="px-4 py-3">{{ optional($asset->created_at)->format('d/m/Y') }}</td>

            <td class="px-4 py-3">
              <a href="{{ route('admin.assets.edit', $asset) }}" class="text-sky-600 hover:underline mr-3">Edit</a>

              <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" class="inline-block" onsubmit="return confirm('Padam aset ini?')">
                @csrf
                @method('DELETE')
                <button class="text-red-600 hover:underline">Padam</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="px-4 py-6 text-center text-slate-500">Tiada rekod aset.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Mobile card list --}}
  <div class="md:hidden space-y-3">
    @forelse($assets as $asset)
      <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-start gap-4">
          <div class="w-20 h-16 bg-slate-100 rounded overflow-hidden flex items-center justify-center">
            @if(!empty($asset->gambar))
              <img src="{{ asset('storage/'.$asset->gambar) }}" alt="gambar" class="object-cover h-full w-full">
            @else
              <svg class="h-8 w-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="1.2" d="M3 7h18v13H3zM8 3v4"/>
              </svg>
            @endif
          </div>

          <div class="flex-1">
            <div class="flex items-start justify-between gap-3">
              <div>
                <div class="font-semibold">{{ $asset->nama_aset }}</div>
                <div class="text-xs text-slate-400">{{ $asset->jenama ?? '—' }} • {{ $asset->kategori ?? '—' }}</div>
              </div>

              <div class="text-right text-xs">
                <div class="font-medium">{{ $asset->bilangan ?? 0 }}</div>
                <div class="text-slate-400">Qty</div>
              </div>
            </div>

            <div class="mt-3 flex items-center justify-between">
              <div class="text-xs text-slate-500">{{ Str::limit($asset->keterangan, 80) }}</div>
              <div class="flex items-center gap-3">
                <a href="{{ route('admin.assets.edit', $asset) }}" class="text-sky-600 text-sm">Edit</a>
                <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" class="inline-block" onsubmit="return confirm('Padam aset ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-600 text-sm">Padam</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="bg-white rounded-lg shadow p-6 text-center text-slate-500">Tiada rekod aset.</div>
    @endforelse
  </div>

  <div class="mt-6">
    {{ $assets->links() }}
  </div>
</div>
@endsection
