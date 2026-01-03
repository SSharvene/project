@extends('layouts.app')

@section('title','Permohonan Stok')

@section('content')
<div class="p-6">
  @if(session('success')) <div class="mb-4 px-4 py-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div> @endif

  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Permohonan Stok</h1>
    <div class="micro text-slate-400">Urus permohonan oleh staf</div>
  </div>

  <form method="GET" class="mb-3 flex items-center gap-2">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama pemohon / jabatan" class="border rounded px-2 py-1">
    <select name="per_page" onchange="this.form.submit()" class="border rounded p-1">
      <option value="15" {{ request('per_page')==15 ? 'selected' : '' }}>15</option>
      <option value="25" {{ request('per_page')==25 ? 'selected' : '' }}>25</option>
    </select>
    <button class="ml-2 px-3 py-1 bg-slate-100 rounded">Cari</button>
  </form>

  <div class="bg-white rounded-lg shadow p-4 overflow-x-auto">
    <table class="min-w-full text-sm text-slate-700">
      <thead>
        <tr class="bg-slate-100 text-slate-600">
          <th class="px-4 py-2 text-left">#</th>
          <th class="px-4 py-2 text-left">Pemohon</th>
          <th class="px-4 py-2 text-left">Jabatan</th>
          <th class="px-4 py-2 text-left">Stok</th>
          <th class="px-4 py-2 text-left">Kuantiti</th>
          <th class="px-4 py-2 text-left">Status</th>
          <th class="px-4 py-2 text-left">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        @forelse($requests as $r)
          <tr class="border-b hover:bg-slate-50">
            <td class="px-4 py-2">{{ $r->id }}</td>
            <td class="px-4 py-2">{{ $r->requester_name }}</td>
            <td class="px-4 py-2">{{ $r->jabatan }}</td>
            <td class="px-4 py-2">{{ optional($r->stok)->nama ?? '-' }}</td>
            <td class="px-4 py-2">{{ $r->quantity }}</td>
            <td class="px-4 py-2">
              @if($r->status == 'pending') <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800">Pending</span>
              @elseif($r->status == 'approved') <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-800">Approved</span>
              @else <span class="px-2 py-1 rounded bg-red-100 text-red-800">Rejected</span>
              @endif
            </td>
            <td class="px-4 py-2">
              <a href="{{ route('admin.stok.requests.show', $r) }}" class="text-sky-600 mr-3">Lihat</a>
              <form action="{{ route('admin.stok.requests.update_status', $r) }}" method="POST" class="inline-block">
                @csrf
                <input type="hidden" name="status" value="{{ $r->status == 'pending' ? 'approved' : 'rejected' }}">
                <button class="text-emerald-600">{{ $r->status == 'pending' ? 'Luluskan' : 'Tolak' }}</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="px-4 py-6 text-center text-slate-500">Tiada permohonan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $requests->links() }}
  </div>
</div>
@endsection
