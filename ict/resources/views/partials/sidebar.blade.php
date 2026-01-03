{{-- resources/views/partials/sidebar.blade.php --}}
@php
  // helper: returns 'active' classes if route matches
  function navActive($patterns) {
    foreach ((array)$patterns as $p) {
      if(request()->routeIs($p)) return 'bg-sky-50 text-sky-700';
    }
    return 'text-slate-700 hover:bg-slate-50';
  }
@endphp

<aside class="w-64 min-h-screen bg-white border-r hidden lg:block">
  <div class="p-4 border-b">
    <a href="{{ route('dashboard.staff') }}" class="flex items-center gap-3">
      <img src="{{ asset('images/logo-small.png') }}" alt="JAKOA" class="w-8 h-8 rounded" />
      <div>
        <div class="font-semibold text-slate-800">JAKOA ICT</div>
        <div class="text-xs text-slate-400 micro">Staff Portal</div>
      </div>
    </a>
  </div>

  <nav class="p-3 space-y-1" aria-label="Main navigation">
    <a href="{{ route('dashboard.staff') }}" class="flex items-center justify-between px-3 py-2 rounded {{ navActive('dashboard.*') }}">
      <div class="flex items-center gap-3">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 13h8V3H3v10zM13 21h8v-8h-8v8zM13 3v8h8V3h-8zM3 21h8v-8H3v8z"/></svg>
        <span>Dashboard</span>
      </div>
    </a>

    {{-- Assets (no submenu) --}}
    <a href="{{ route('assets.index') }}" class="flex items-center justify-between px-3 py-2 rounded {{ navActive('assets.*') }}">
      <div class="flex items-center gap-3">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 2l9 4.9v9.8L12 22 3 16.7V6.9z"/></svg>
        <span>Aset</span>
      </div>
      {{-- optional badge --}}
    </a>

    {{-- Pinjaman submenu --}}
    <div class="submenu">
      <button type="button" aria-expanded="false" class="w-full flex items-center justify-between px-3 py-2 rounded group submenu-toggle">
        <div class="flex items-center gap-3">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
          <span>Pinjaman</span>
        </div>
        <svg class="w-4 h-4 transition-transform transform submenu-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6"/></svg>
      </button>

      <div class="submenu-items mt-1 ml-8 space-y-1 hidden">
        <a href="{{ route('pinjaman.create') }}" class="block px-3 py-2 rounded {{ navActive(['pinjaman.create']) }}">Mohon Pinjaman</a>
        <a href="{{ route('pinjaman.index') }}" class="block px-3 py-2 rounded {{ navActive(['pinjaman.index','pinjaman.show']) }}">Status Pinjaman</a>
      </div>
    </div>

    {{-- Aduan submenu --}}
    <div class="submenu">
      <button type="button" aria-expanded="false" class="w-full flex items-center justify-between px-3 py-2 rounded group submenu-toggle">
        <div class="flex items-center gap-3">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15v4a2 2 0 0 1-2 2H5l-4 0V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          <span>Aduan</span>
        </div>
        <svg class="w-4 h-4 transition-transform transform submenu-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6"/></svg>
      </button>

      <div class="submenu-items mt-1 ml-8 space-y-1 hidden">
        <a href="{{ route('aduan.create') }}" class="block px-3 py-2 rounded {{ navActive(['aduan.create']) }}">Hantar Aduan</a>
        <a href="{{ route('aduan.index') }}" class="block px-3 py-2 rounded {{ navActive(['aduan.index','aduan.show']) }}">
          Aduan Saya
          @if(isset($unreadCount) && $unreadCount > 0)
            <span class="ml-2 inline-block text-xs px-2 py-0.5 rounded bg-rose-100 text-rose-700">{{ $unreadCount }}</span>
          @endif
        </a>
      </div>
    </div>

    {{-- Stok --}}
    <div class="submenu">
      <button type="button" aria-expanded="false" class="w-full flex items-center justify-between px-3 py-2 rounded group submenu-toggle">
        <div class="flex items-center gap-3">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 3h18v4H3zM3 9h18v12H3z"/></svg>
          <span>Stok</span>
        </div>
        <svg class="w-4 h-4 transition-transform transform submenu-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6"/></svg>
      </button>

      <div class="submenu-items mt-1 ml-8 space-y-1 hidden">
        <a href="{{ route('stok.index') }}" class="block px-3 py-2 rounded {{ navActive(['stok.*']) }}">Senarai Stok</a>
        <a href="{{ route('stok.create') }}" class="block px-3 py-2 rounded {{ navActive(['stok.create']) }}">Mohon Stok</a>
      </div>
    </div>

    {{-- Profile & logout --}}
    <div class="mt-6 border-t pt-3">
      <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded {{ navActive(['profile.*']) }}">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zM21 21a9 9 0 1 0-18 0"/></svg>
        <span>Kemaskini Profil</span>
      </a>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2 rounded text-slate-700 hover:bg-slate-50">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/></svg>
          <span>Log Keluar</span>
        </button>
      </form>
    </div>
  </nav>
</aside>


