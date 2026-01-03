{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.app')

@section('title','Staff Dashboard — JAKOA ICT')


@section('content')
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
    <div class="card p-4">
      <div class="text-sm text-slate-500">My Pinjaman</div>
      <div class="text-2xl font-semibold">{{ $myPinjaman ?? 0 }}</div>
      <div class="micro mt-1">Pending / Active</div>
    </div>

    <div class="card p-4">
      <div class="text-sm text-slate-500">My Aduan</div>
      <div class="text-2xl font-semibold">{{ $myAduan ?? 0 }}</div>
      <div class="micro mt-1">Open / Resolved</div>
    </div>

    <div class="card p-4">
      <div class="text-sm text-slate-500">Permohonan Stok</div>
      <div class="text-2xl font-semibold">{{ $myStok ?? 0 }}</div>
      <div class="micro mt-1">Approved / Pending</div>
    </div>
  </div>

  <div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <div class="card p-4">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-semibold">Permohonan Terkini</h3>
          <div class="micro">
            <a href="{{ route('pinjaman.index') }}" class="text-sky-600 hover:underline">Lihat semua</a>
          </div>
        </div>

        @if(!empty($recentPinjaman) && count($recentPinjaman))
          <ul class="divide-y">
            @foreach($recentPinjaman as $p)
              <li class="py-3 flex items-start justify-between">
                <div>
                  <div class="font-medium">{{ $p->asset?->nama ?? '—' }} <span class="text-xs text-slate-400">×{{ $p->quantity }}</span></div>
                  <div class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($p->purpose,60) }}</div>
                </div>
                <div class="text-right micro">
                  <div>
                    @if($p->status === 'pending') <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pending</span>
                    @elseif($p->status === 'approved') <span class="px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Approved</span>
                    @elseif($p->status === 'rejected') <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Rejected</span>
                    @else <span class="px-2 py-1 text-xs rounded bg-slate-100 text-slate-700">{{ ucfirst($p->status) }}</span>
                    @endif
                  </div>
                  <div class="text-xs text-slate-400 mt-1">{{ $p->created_at->diffForHumans() }}</div>
                </div>
              </li>
            @endforeach
          </ul>
        @else
          <div class="py-6 text-center micro text-slate-500">Tiada permohonan terkini</div>
        @endif
      </div>

      <div class="card p-4">
        <h3 class="font-semibold mb-3">Aktiviti Anda — 30 Hari</h3>
        <div class="h-56">
          <canvas id="overviewChart"></canvas>
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <div class="card p-4">
        <h4 class="font-semibold">Tindakan Pantas</h4>
        <div class="mt-3 space-y-2">
          <a class="inline-flex items-center justify-between w-full card p-3" href="{{ route('assets.index') }}">
            <div>Semak Aset</div>
            <div class="text-slate-400">→</div>
          </a>
          <a class="inline-flex items-center justify-between w-full card p-3" href="{{ route('pinjaman.create') }}">
            <div>Mohon Pinjaman</div>
            <div class="text-slate-400">→</div>
          </a>
          <a class="inline-flex items-center justify-between w-full card p-3" href="{{ route('aduan.create') }}">
            <div>Hantar Aduan</div>
            <div class="text-slate-400">→</div>
          </a>
        </div>
      </div>

      <div class="card p-4">
        <h4 class="font-semibold">Hubungan</h4>
        <p class="micro">Perlu bantuan? <a href="mailto:it-support@jakoa.gov.my" class="text-sky-600">it-support@jakoa.gov.my</a></p>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  (function(){
    const ctx = document.getElementById('overviewChart');
    if(!ctx) return;

    const labels = {!! json_encode($chartLabels ?? array_map(fn($i)=>\Carbon\Carbon::today()->subDays($i)->format('d M'), range(29,0))) !!};
    const data = {!! json_encode($chartData ?? array_fill(0,30,0)) !!};

    new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Permohonan',
          data,
          tension: 0.36,
          borderColor: 'rgba(0,123,255,1)',
          backgroundColor: (ctxEl) => {
            // gradient fill
            const c = ctx.getContext('2d');
            const grad = c.createLinearGradient(0,0,0,200);
            grad.addColorStop(0, 'rgba(0,123,255,0.18)');
            grad.addColorStop(1, 'rgba(0,123,255,0.0)');
            return grad;
          },
          pointRadius: 2,
          fill: true,
          borderWidth: 2.5
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: { grid:{display:false}, ticks:{ maxTicksLimit:8 }},
          y: { beginAtZero:true, grid:{color:'#f1f5f9'} }
        },
        plugins:{ legend:{display:false} }
      }
    });
  })();
</script>
@endpush
