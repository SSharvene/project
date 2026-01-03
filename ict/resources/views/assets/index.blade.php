@extends('layouts.app')

@section('title', 'Aset Sewaan')

@section('content')
<div class="max-w-[1400px] mx-auto p-6">

  <div class="flex flex-col lg:flex-row gap-4 items-start mb-6">
    <div class="flex-1">
      <h2 class="text-2xl font-semibold">Aset Sewaan</h2>
      <p class="text-sm text-slate-400">Senarai aset untuk disewa — cari, tapis, dan eksport.</p>
    </div>

    <div class="flex gap-2 items-center w-full lg:w-auto">
      <form id="filtersForm" method="GET" action="{{ route('assets.index') }}" class="flex gap-2 items-center">
        <input name="q" value="{{ request('q') }}" type="search" placeholder="Cari nama, jenama, model..." class="px-3 py-2 rounded bg-slate-800 text-white/90 w-56" />

        <select name="category" onchange="document.getElementById('filtersForm').submit()" class="px-3 py-2 rounded bg-slate-800 text-white/90">
          <option value="">Semua Kategori</option>
          @foreach($categories as $c)
            <option value="{{ $c }}" @selected(request('category') == $c)>{{ $c }}</option>
          @endforeach
        </select>

        <select name="per_page" onchange="document.getElementById('filtersForm').submit()" class="px-3 py-2 rounded bg-slate-800 text-white/90">
          <option value="8" @selected(request('per_page')==8)>8 / halaman</option>
          <option value="12" @selected(request('per_page')==12)>12 / halaman</option>
          <option value="24" @selected(request('per_page')==24)>24 / halaman</option>
        </select>

        <input type="hidden" name="view" id="viewInput" value="{{ request('view', $viewMode ?? 'card') }}">

        <a href="{{ route('assets.export', request()->query()) }}" class="inline-flex items-center px-3 py-2 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-sm">Eksport CSV</a>
      </form>

      <!-- view switcher -->
      <div class="ml-2 inline-flex items-center gap-1">
        <button type="button" onclick="setView('card')" id="btnCard" class="px-3 py-2 rounded bg-slate-800">Card</button>
        <button type="button" onclick="setView('list')" id="btnList" class="px-3 py-2 rounded bg-slate-800">List</button>
        <button type="button" onclick="setView('photo')" id="btnPhoto" class="px-3 py-2 rounded bg-slate-800">Photo</button>
      </div>
    </div>
  </div>

  <!-- results -->
  <div id="viewContainer">
    {{-- Card view --}}
    <div id="cardView" class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" style="display: none;">
      @foreach($assets as $asset)
      <div class="bg-slate-900 p-4 rounded-lg shadow">
        <div class="h-40 w-full mb-3 bg-slate-800 rounded overflow-hidden grid place-items-center">
          @if($asset->image)
            <img src="{{ asset('storage/'.$asset->image) }}" alt="{{ $asset->title }}" class="object-cover h-full w-full">
          @else
            <div class="text-sm text-slate-400">Tiada imej</div>
          @endif
        </div>
        <h3 class="font-semibold text-white">{{ $asset->title }}</h3>
        <p class="text-sm text-slate-400 mt-1">{{ $asset->brand }} {{ $asset->model }}</p>
        <div class="flex justify-between items-center mt-3">
          <div class="text-sm text-emerald-400 font-semibold">{{ $asset->status ?? 'Available' }}</div>
          <a href="{{ route('assets.show', $asset->id) }}" class="text-sm px-3 py-1 rounded bg-white/5">Lihat</a>
        </div>
      </div>
      @endforeach
    </div>

    {{-- List view --}}
    <div id="listView" class="bg-slate-900 rounded overflow-hidden" style="display: none;">
      <table class="min-w-full divide-y">
        <thead class="bg-slate-800">
          <tr>
            <th class="px-4 py-3 text-left text-sm">#</th>
            <th class="px-4 py-3 text-left text-sm">Aset</th>
            <th class="px-4 py-3 text-left text-sm">Kategori</th>
            <th class="px-4 py-3 text-left text-sm">Jenama / Model</th>
            <th class="px-4 py-3 text-left text-sm">Nombor Siri</th>
            <th class="px-4 py-3 text-left text-sm">Status</th>
            <th class="px-4 py-3 text-right text-sm">Tindakan</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @foreach($assets as $asset)
          <tr>
            <td class="px-4 py-3 text-sm">{{ $asset->id }}</td>
            <td class="px-4 py-3 text-sm">{{ $asset->title }}</td>
            <td class="px-4 py-3 text-sm">{{ $asset->category }}</td>
            <td class="px-4 py-3 text-sm">{{ $asset->brand }} {{ $asset->model }}</td>
            <td class="px-4 py-3 text-sm">{{ $asset->serial_number }}</td>
            <td class="px-4 py-3 text-sm">{{ $asset->status }}</td>
            <td class="px-4 py-3 text-right text-sm">
              <a href="{{ route('assets.show', $asset->id) }}" class="px-3 py-1 rounded bg-white/5">Lihat</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Photo view (grid of images) --}}
    <div id="photoView" class="grid gap-3 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4" style="display: none;">
      @foreach($assets as $asset)
      <div class="rounded overflow-hidden">
        <div class="h-40 w-full bg-slate-800 grid place-items-center overflow-hidden">
          @if($asset->image)
            <img src="{{ asset('storage/'.$asset->image) }}" alt="{{ $asset->title }}" class="object-cover h-full w-full">
          @else
            <div class="text-sm text-slate-400">Tiada imej</div>
          @endif
        </div>
        <div class="mt-2 text-sm">
          <div class="font-medium text-white">{{ $asset->title }}</div>
          <div class="text-xs text-slate-400">{{ $asset->brand }} • {{ $asset->category }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- Pagination --}}
  <div class="mt-6">
    {{ $assets->links() }}
  </div>

  @if($assets->isEmpty())
    <div class="mt-6 text-center text-slate-400">Tiada aset ditemui.</div>
  @endif

</div>

@endsection

@section('scripts')
<script>
  // initial view from server
  const initialView = "{{ request('view', $viewMode ?? 'card') }}";
  function showView(v) {
    document.getElementById('cardView').style.display = (v==='card') ? '' : 'none';
    document.getElementById('listView').style.display = (v==='list') ? '' : 'none';
    document.getElementById('photoView').style.display = (v==='photo') ? '' : 'none';
    document.getElementById('viewInput').value = v;
    // highlight active button
    ['btnCard','btnList','btnPhoto'].forEach(id=>{
      document.getElementById(id).classList.remove('bg-emerald-600','text-white');
    });
    if(v==='card') document.getElementById('btnCard').classList.add('bg-emerald-600','text-white');
    if(v==='list') document.getElementById('btnList').classList.add('bg-emerald-600','text-white');
    if(v==='photo') document.getElementById('btnPhoto').classList.add('bg-emerald-600','text-white');
  }

  function setView(v){
    // set value and submit to preserve on server-side (so pagination keeps view in querystring)
    const f = document.getElementById('filtersForm');
    document.getElementById('viewInput').value = v;
    f.submit();
  }

  // on page load show correct one without submitting
  document.addEventListener('DOMContentLoaded', ()=>{
    showView(initialView || 'card');
  });
</script>
@endsection
