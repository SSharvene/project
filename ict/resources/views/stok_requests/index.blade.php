@extends('layouts.app')

@section('title', 'Senarai Permohonan Stok')

@section('content')
<div class="p-6">
  <h1 class="text-xl font-semibold mb-4">Senarai Permohonan Stok Saya</h1>

  @if (session('success'))
    <div class="mb-4 bg-green-50 text-green-700 px-4 py-2 rounded">
      {{ session('success') }}
    </div>
  @endif

  @if ($requests->count() === 0)
    <div class="bg-white rounded-lg shadow p-6 text-center text-slate-500">
      Tiada permohonan stok dibuat setakat ini.
      <div class="mt-3">
        <a href="{{ route('stok.request.create') }}" class="bg-sky-600 text-white px-4 py-2 rounded hover:bg-sky-700">
          + Mohon Stok
        </a>
      </div>
    </div>
  @else
    <div class="flex justify-between items-center mb-3">
      <a href="{{ route('stok.request.create') }}" class="bg-sky-600 text-white px-3 py-2 rounded hover:bg-sky-700 text-sm">
        + Mohon Stok Baru
      </a>

      <form method="GET" class="flex items-center space-x-2">
        <label for="per_page" class="text-sm text-slate-600">Paparan:</label>
        <select name="per_page" id="per_page" class="border rounded text-sm px-2 py-1"
                onchange="this.form.submit()">
          @foreach([10,25,50] as $size)
            <option value="{{ $size }}" {{ request('per_page',15)==$size?'selected':'' }}>{{ $size }}</option>
          @endforeach
        </select>
      </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
      <table class="w-full text-sm border-collapse">
        <thead class="bg-slate-50 border-b">
          <tr>
            <th class="px-4 py-2 text-left font-medium text-slate-600">ID</th>
            <th class="px-4 py-2 text-left font-medium text-slate-600">Nama Stok</th>
            <th class="px-4 py-2 text-left font-medium text-slate-600">Kuantiti</th>
            <th class="px-4 py-2 text-left font-medium text-slate-600">Status</th>
            <th class="px-4 py-2 text-left font-medium text-slate-600">Tarikh</th>
            <th class="px-4 py-2 text-center font-medium text-slate-600">Tindakan</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($requests as $req)
            <tr class="border-b hover:bg-slate-50">
              <td class="px-4 py-2">{{ $req->id }}</td>
              <td class="px-4 py-2">{{ $req->stok->nama ?? '-' }}</td>
              <td class="px-4 py-2">{{ $req->quantity }}</td>
              <td class="px-4 py-2">
                @php
                  $color = match($req->status) {
                    'approved' => 'bg-green-100 text-green-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    'processing' => 'bg-yellow-100 text-yellow-700',
                    default => 'bg-slate-100 text-slate-700',
                  };
                @endphp
                <span class="px-2 py-1 rounded text-xs font-medium {{ $color }}">
                  {{ ucfirst($req->status) }}
                </span>
              </td>
              <td class="px-4 py-2">{{ $req->created_at->format('d/m/Y') }}</td>
              <td class="px-4 py-2 text-center">
                <a href="{{ route('stok.request.show', $req->id) }}" class="text-sky-600 hover:underline">Lihat</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $requests->links() }}
    </div>
  @endif
</div>
@endsection
