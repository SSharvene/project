{{-- resources/views/aduan/staff_index.blade.php --}}
@extends('layouts.app-blue')

@section('title','Aduan Saya — JAKOA ICT')

@section('content')
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-xl font-semibold">Aduan Saya</h2>
      <p class="micro text-slate-500">Senarai aduan yang dihantar oleh anda.</p>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('aduan.create') }}" class="btn btn-primary">Hantar Aduan Baru</a>
      <div class="inline-flex items-center border rounded overflow-hidden">
        <form method="GET" action="{{ route('aduan.index') }}" class="flex items-center">
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari tajuk / keterangan" class="px-3 py-2 w-56 outline-none" />
          <button class="px-3 py-2 micro">Cari</button>
        </form>
      </div>
    </div>
  </div>

  <div class="card p-4 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div class="flex items-center gap-3">
        <label class="micro text-slate-500">Status</label>
        <form id="filterForm" method="GET" action="{{ route('aduan.index') }}" class="flex items-center gap-2">
          <select name="status" onchange="document.getElementById('filterForm').submit()" class="px-3 py-2 border rounded">
            <option value="">Semua</option>
            <option value="open" {{ request('status')=='open' ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ request('status')=='in_progress' ? 'selected' : '' }}>Dalam Proses</option>
            <option value="resolved" {{ request('status')=='resolved' ? 'selected' : '' }}>Selesai</option>
          </select>

          <label class="micro text-slate-500">Saiz halaman</label>
          <select name="per_page" onchange="document.getElementById('filterForm').submit()" class="px-3 py-2 border rounded">
            <option value="10" {{ request('per_page', 10)==10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('per_page')==25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('per_page')==50 ? 'selected' : '' }}>50</option>
          </select>
        </form>
      </div>

      <div class="micro text-slate-500">Unread: <span id="unreadCount">{{ $unreadCount ?? 0 }}</span></div>
    </div>
  </div>

  <div class="grid gap-4">
    @forelse($aduans as $a)
      <div class="card p-4 flex items-start justify-between">
        <div class="w-3/4">
          <div class="flex items-center gap-3">
            <div class="font-medium">{{ $a->tajuk ?? '—' }}</div>
            <div class="text-xs text-slate-400">#{{ $a->id }}</div>
            @if(!$a->is_read)
              <span class="ml-2 px-2 py-1 text-xs rounded bg-sky-100 text-sky-800">Unread</span>
            @endif
          </div>

          <div class="text-sm text-slate-500 mt-1">{{ \Illuminate\Support\Str::limit($a->penerangan,120) }}</div>

          <div class="mt-3 micro text-slate-400">Lokasi: {{ $a->lokasi ?? '—' }} • Peranti: {{ $a->jenis_peranti ?? '—' }} • {{ $a->created_at->format('d M Y H:i') }}</div>
        </div>

        <div class="text-right flex flex-col items-end gap-2">
          <div>
            @if($a->status === 'open') <span class="px-3 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Open</span>
            @elseif($a->status === 'in_progress') <span class="px-3 py-1 text-xs rounded bg-amber-100 text-amber-800">Dalam Proses</span>
            @elseif($a->status === 'resolved') <span class="px-3 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Selesai</span>
            @else <span class="px-3 py-1 text-xs rounded bg-slate-100 text-slate-700">{{ ucfirst($a->status) }}</span>
            @endif
          </div>

          <div class="flex items-center gap-2">
            <button class="btn btn-ghost micro" onclick="openAduanModal({{ $a->id }})">Lihat</button>
            <form method="POST" action="{{ route('aduan.pdf', $a->id) }}" target="_blank">
              @csrf
              <button type="submit" class="btn btn-outline micro">Cetak PDF</button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="card p-6 text-center text-slate-500">Tiada aduan ditemui.</div>
    @endforelse
  </div>

  <div class="mt-6">{{ $aduans->withQueryString()->links() }}</div>

  <!-- Modal -->
  <div id="aduanModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg w-full max-w-3xl p-4">
      <div class="flex items-start justify-between">
        <h3 id="modalTitle" class="font-semibold"></h3>
        <button onclick="closeAduanModal()" class="text-slate-500">✕</button>
      </div>

      <div id="modalBody" class="mt-3 text-sm text-slate-700"></div>

      <div class="mt-4 flex items-center justify-between">
        <div id="modalMeta" class="micro text-slate-400"></div>
        <div class="flex items-center gap-2">
          <button id="markReadBtn" class="btn btn-primary micro" onclick="markAsRead(currentAduanId)">Mark as read</button>
          <a id="replyLink" class="btn btn-ghost micro" href="#">Balas</a>
          <form id="pdfForm" method="POST" target="_blank" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-outline micro">Cetak PDF</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script>
  let currentAduanId = null;

  function openAduanModal(id){
    currentAduanId = id;
    const modal = document.getElementById('aduanModal');
    const title = document.getElementById('modalTitle');
    const body = document.getElementById('modalBody');
    const meta = document.getElementById('modalMeta');
    const replyLink = document.getElementById('replyLink');
    const pdfForm = document.getElementById('pdfForm');

    // fetch details via AJAX
    fetch("{{ url('/aduan') }}/"+id)
      .then(r => r.json())
      .then(data => {
        title.textContent = data.tajuk || '—';
        body.innerHTML = `
          <div class=\"text-slate-600\">${(data.penerangan || 'Tiada penerangan').replace(/\n/g,'<br>')}</div>
          ${data.attachments && data.attachments.length ? `<div class=\"mt-3\"><strong>Lampiran:</strong><ul>${data.attachments.map(a=>`<li><a target=\"_blank\" href=\"${a.url}\">${a.name}</a></li>`).join('')}</ul></div>` : ''}
        `;

        meta.textContent = `Lokasi: ${data.lokasi || '—'} • ${data.jenis_peranti || '—'} • Dihantar: ${data.created_at}`;
        replyLink.href = `/aduan/${id}/reply`;

        // setup pdf form action
        pdfForm.action = `/aduan/${id}/pdf`;

        // update unread indicator
        if(!data.is_read){
          // don't assume server changed — just reflect optimistic UI
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
      })
      .catch(e=>{
        alert('Gagal memuatkan maklumat aduan.');
        console.error(e);
      });
  }

  function closeAduanModal(){
    const modal = document.getElementById('aduanModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  function markAsRead(id){
    if(!id) return;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/aduan/${id}/mark-read`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
      body: JSON.stringify({})
    }).then(r=>r.json())
      .then(res=>{
        if(res.success){
          // decrement unread count if > 0
          const el = document.getElementById('unreadCount');
          const val = parseInt(el.textContent || '0');
          if(val>0) el.textContent = val-1;

          // update UI: remove Unread badge from list item
          const badge = document.querySelector(`#aduan-badge-${id}`);
          if(badge) badge.remove();

          // disable button
          document.getElementById('markReadBtn').disabled = true;
          document.getElementById('markReadBtn').textContent = 'Read';
        } else {
          alert('Gagal kemaskini status.');
        }
      }).catch(e=>{ console.error(e); alert('Ralat rangkaian.'); });
  }

  // close modal on ESC
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeAduanModal(); });
</script>
@endpush
