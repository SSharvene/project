{{-- resources/views/partials/header.blade.php --}}
<div class="flex items-center justify-between gap-4 mb-4">
  <div>
    <h1 class="text-2xl font-extrabold" style="background:linear-gradient(90deg,var(--blue-500),var(--indigo-600));-webkit-background-clip:text;color:transparent">
      Hi, {{ auth()->user()->name ?? 'User' }}
    </h1>
    <div class="text-sm micro mt-1">Ringkasan pantas aktiviti ICT anda</div>
  </div>

  <div class="flex items-center gap-3">
    <!-- Search (visual) -->
    <div class="hidden md:block">
      <div class="relative">
        <input placeholder="Cari aset, permohonan..." class="w-72 pl-10 pr-3 py-2 rounded-xl border border-slate-100 focus:ring-2 focus:ring-blue-200 focus:outline-none" />
        <svg class="absolute left-3 top-2.5 h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.5" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
      </div>
    </div>

    <!-- Notification + small menu are here for extension -->
    <div class="relative">
      <button id="notifSmall" class="p-2 rounded-xl btn-blue shadow hover:scale-[1.02] transition-transform">
        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.5" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1.05-.6 1.45L4 17h5m6 0v1a3 3 0 11-6 0v-1"/></svg>
      </button>
    </div>

    <div class="relative">
      <button id="profileSmall" class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-100 shadow-sm">
        @if(auth()->user()?->profile_pic)
          <img src="{{ asset('storage/'.auth()->user()->profile_pic) }}" class="h-9 w-9 rounded-full object-cover" alt="profile">
        @else
          <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white grid place-items-center font-semibold">{{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}</div>
        @endif
      </button>
    </div>
  </div>
</div>
