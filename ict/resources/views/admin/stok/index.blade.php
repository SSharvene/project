@extends('layouts.app')

@section('title','Senarai Stok')

@section('content')
<div class="p-6">
  @if(session('success')) <div class="mb-4 px-4 py-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div> @endif

  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Senarai Stok</h1>
    <div class="flex gap-2">
      <a href="{{ route('admin.stok.register') }}" class="bg-sky-600 text-white px-4 py-2 rounded">+ Daftar Stok</a>
      <a href="{{ route('admin.stok.requests') }}" class="bg-gray-100 px-3 py-2 rounded border">Permohonan</a>
    </div>
  </div>

  <form method="GET" class="mb-3 flex items-center gap-2">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama / kod / kategori" class="border rounded px-2 py-1">
    <select name="per_page" onchange="this.form.submit()" class="border rounded p-1">
      <option value="15" {{ request('per_page')==15 ? 'selected' : '' }}>15</option>
      <option value="25" {{ request('per_page')==25 ? 'selected' : '' }}>25</option>
      <option value="50" {{ request('per_page')==50 ? 'selected' : '' }}>50</option>
    </select>
    <button class="ml-2 px-3 py-1 bg-slate-100 rounded">Cari</button>
  </form>

  <div class="bg-white rounded-lg shadow p-4 overflow-x-auto">
    <table class="min-w-full text-sm text-slate-700">
      <thead>
        <tr class="bg-slate-100 text-slate-600">
          <th class="px-4 py-2 text-left">#</th>
          <th class="px-4 py-2 text-left">Nama</th>
          <th class="px-4 py-2 text-left">Kod</th>
          <th class="px-4 py-2 text-left">Kategori</th>
          <th class="px-4 py-2 text-left">Kuantiti</th>
          <th class="px-4 py-2 text-left">Lokasi</th>
          <th class="px-4 py-2 text-left">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        @forelse($stoks as $stok)
          <tr class="border-b hover:bg-slate-50">
            <td class="px-4 py-2">{{ $stok->id }}</td>
            <td class="px-4 py-2">{{ $stok->nama }}</td>
            <td class="px-4 py-2">{{ $stok->kod }}</td>
            <td class="px-4 py-2">{{ $stok->kategori }}</td>
            <td class="px-4 py-2">{{ $stok->kuantiti }}</td>
            <td class="px-4 py-2">{{ $stok->lokasi }}</td>
            <td class="px-4 py-2">
              <a href="{{ route('admin.stok.edit', $stok) }}" class="text-sky-600 mr-3">Edit</a>
              <form action="{{ route('admin.stok.destroy', $stok) }}" method="POST" class="inline-block" onsubmit="return confirm('Padam stok ini?')">
                @csrf
                @method('DELETE')
                <button class="text-red-600">Padam</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="px-4 py-6 text-center text-slate-500">Tiada stok berdaftar.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $stoks->links() }}
  </div>
</div>
@endsection
