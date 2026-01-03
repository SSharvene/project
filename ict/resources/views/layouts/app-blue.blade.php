{{-- resources/views/layouts/app-blue.blade.php --}}
<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title','JAKOA ICT — Futuristic')</title>

  <!-- Tailwind CDN (dev) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { theme: { extend: { fontFamily: { inter: ['Inter','ui-sans-serif','system-ui'] } } } }</script>

  <!-- Alpine -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <link rel="icon" href="{{ asset('images/logo_jakoa.png') }}">

  <style>
    /*
      Palette (user-supplied, cleaned):
      --neon-pink:   #FF2CDF
      --deep-blue:   #0014FF
      --cyan:        #00E1FD
      --hot-rose:    #FC007A
      --neon-green:  #00FF5B   (fixed from OOFF5B)
      --cyan-2:      #00E5FF
      --indigo:      #1200FF
      --gold:        #FFE53B
      --red:         #FF2525
      --coral:       #FF005B
      --gold-2:      #FFE53B
      --peach:       #FFA06C   (fixed from FFOA6C)
      --violet:      #2D27FF
    */

    :root{
      --neon-pink:#FF2CDF;
      --deep-blue:#0014FF;
      --cyan:#00E1FD;
      --hot-rose:#FC007A;
      --neon-green:#00FF5B;
      --cyan-2:#00E5FF;
      --indigo:#1200FF;
      --gold:#FFE53B;
      --red:#FF2525;
      --coral:#FF005B;
      --peach:#FFA06C;
      --violet:#2D27FF;

      /* UI variables */
      --bg-start: linear-gradient(135deg, rgba(2,6,69,1) 0%, rgba(8,10,50,1) 40%, rgba(4,8,38,1) 100%);
      --panel-bg: rgba(6,8,25,0.56);
      --glass: rgba(255,255,255,0.03);
      --muted: rgba(200,210,235,0.75);
      --card-edge: rgba(255,255,255,0.04);
    }

    html,body { height:100%; }
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background:
        radial-gradient(800px 400px at 10% 10%, rgba(18,0,255,0.14), transparent 8%),
        radial-gradient(700px 300px at 90% 80%, rgba(255,45,140,0.06), transparent 10%),
        linear-gradient(180deg,#02102e 0%, #03061b 50%, #010214 100%);
      color: #e9f2ff;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }

    /* floating neon ribbons */
    .ribbon {
      pointer-events: none;
      position: fixed;
      inset: 0;
      z-index: 0;
    }
    .ribbon .r {
      position:absolute;
      width:140%;
      height:220px;
      left:-20%;
      filter: blur(36px);
      transform:rotate(-12deg);
      opacity:0.45;
      background: linear-gradient(90deg,var(--cyan),var(--neon-pink),var(--indigo));
      animation: ribbon-move 18s linear infinite;
      mix-blend-mode: screen;
    }
    .ribbon .r.r2 {
      top:48%;
      opacity:0.22;
      animation-duration: 26s;
      filter: blur(80px);
      transform: rotate(-8deg);
    }
    @keyframes ribbon-move { 0%{ transform: translateX(-15%) rotate(-12deg)} 50%{ transform: translateX(15%) rotate(-12deg)} 100%{ transform: translateX(-15%) rotate(-12deg)} }

    /* app container */
    .app-wrap { position:relative; z-index:10; max-width:1400px; margin:0 auto; padding:28px; }
    @media (min-width:1024px){ .layout{ display:grid; grid-template-columns:260px 1fr; gap:28px; } }

    /* sidebar */
    .sidebar {
      background: linear-gradient(180deg, rgba(6,10,30,0.85), rgba(8,12,40,0.65));
      border: 1px solid rgba(255,255,255,0.04);
      border-radius: 14px;
      padding:18px;
      box-shadow: 0 10px 40px rgba(2,6,23,0.5);
    }
    .sidebar .brand { display:flex; gap:12px; align-items:center; margin-bottom:14px; }
    .sidebar nav a { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; color:var(--muted); text-decoration:none; transition:transform .18s ease, background .18s ease; font-weight:600 }
    .sidebar nav a:hover { transform: translateX(6px); background: linear-gradient(90deg, rgba(0,129,255,0.06), rgba(45,39,255,0.03)); color:#eaf6ff; }
    .sidebar nav a.active { background: linear-gradient(90deg,var(--cyan),var(--indigo)); color:#00142b; box-shadow: 0 6px 20px rgba(18,0,255,0.12); }

    /* card */
    .card {
      position:relative;
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      border-radius:14px;
      padding:18px;
      border: 1px solid rgba(255,255,255,0.03);
      box-shadow: 0 10px 30px rgba(2,6,23,0.45);
      overflow:hidden;
      transition: transform .28s cubic-bezier(.2,.9,.2,1), box-shadow .28s;
    }
    .card::after{
      content:"";
      position:absolute;
      inset:-40% -40% auto -40%;
      height:180%;
      background: linear-gradient(120deg, rgba(255,45,140,0.04), rgba(0,225,253,0.04), rgba(18,0,255,0.02));
      transform:translateX(-25%) rotate(10deg);
      transition: transform .9s ease;
      pointer-events:none;
      mix-blend-mode: screen;
    }
    .card:hover{ transform: translateY(-8px); box-shadow: 0 20px 50px rgba(2,6,23,0.6) }
    .card:hover::after{ transform: translateX(0) rotate(10deg); }

    /* neon outline for headers */
    .neon-title {
      background: linear-gradient(90deg, var(--cyan-2), var(--neon-pink));
      -webkit-background-clip: text; color: transparent;
      font-weight:800; font-size:1.6rem;
      text-shadow: 0 6px 26px rgba(0,225,253,0.06);
    }

    /* buttons */
    .btn-neon {
      background: linear-gradient(90deg, var(--neon-pink) 0%, var(--deep-blue) 50%, var(--cyan) 100%);
      color: #fff; padding:8px 14px; border-radius:10px; font-weight:700;
      box-shadow: 0 10px 40px rgba(18,0,255,0.12), 0 2px 6px rgba(0,225,253,0.06);
      border: 1px solid rgba(255,255,255,0.03);
      transition: transform .18s, filter .12s;
      background-size: 200% 100%;
    }
    .btn-neon:hover{ transform: translateY(-2px); filter:brightness(1.02); background-position: 100% 0; }

    .micro{ font-size:13px; color:rgba(220,230,255,0.75); }

    /* small pills */
    .pill { padding:6px 10px; border-radius:999px; font-weight:700; font-size:12px; color:#03132b; background: linear-gradient(90deg,var(--gold), #ffca3b); }

    /* header area */
    .header-bar { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:14px; }
    .search { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.04); padding:8px 12px; border-radius:10px; color:var(--muted) }

    /* small dropdown */
    .dropdown { min-width:210px; background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border-radius:10px; border:1px solid rgba(255,255,255,0.03); box-shadow: 0 12px 30px rgba(2,6,23,0.55); overflow:hidden; }

    /* canvas */
    canvas{ display:block; width:100%; height:100%; }

    /* responsive tweaks */
    @media (max-width:1023px){
      .layout{ display:block; }
      .sidebar{ display:flex; gap:12px; align-items:center; justify-content:space-between; padding:12px }
    }
  </style>
</head>
<body>

  <!-- animated ribbons -->
  <div class="ribbon" aria-hidden="true">
    <div class="r r1" style="top:8%;"></div>
    <div class="r r2" style="top:60%;"></div>
  </div>

  <div class="app-wrap">
    <div class="layout">

     <!-- resources/views/partials/sidebar.blade.php -->
<aside class="sidebar"> <div> <div class="brand"> <img src="{{ asset('images/logo_jakoa.png') }}" class="h-12 w-12 rounded-md shadow-lg" alt="JAKOA"> <div> <div class="text-lg font-bold" style="color:var(--cyan-2)">JAKOA ICT</div> <div class="micro">Sistem Pengurusan Aset</div> </div> </div> <nav class="mt-3 space-y-2"> {{-- Dashboard (no submenu) --}} <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"> <span class="px-3 py-2 rounded inline-flex items-center gap-3"> <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.2" d="M3 12l2-2 4 4 8-8 4 4v6a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg> Dashboard </span> </a> {{-- Assets with submenu --}} <div class="sidebar-group"> <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded {{ request()->routeIs('assets.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}" aria-expanded="{{ request()->routeIs('assets.*') ? 'true' : 'false' }}" onclick="document.getElementById('submenu-assets').classList.toggle('hidden')"> <span class="inline-flex items-center gap-3"> <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.2" d="M3 7h18M7 11h10M8 15h8"/></svg> Aset Sewaan </span> <svg class="h-4 w-4 transform {{ request()->routeIs('assets.*') ? '' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.2" d="M6 9l6 6 6-6"/></svg> </button> <div id="submenu-assets" class="mt-1 ml-6 space-y-1 {{ request()->routeIs('assets.*') ? '' : 'hidden' }}"> <a href="{{ route('assets.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('assets.index') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-50' }}">Senarai Aset</a> <a href="{{ route('assets.create') }}" class="block px-3 py-2 rounded {{ request()->routeIs('assets.create') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-50' }}">Tambah Aset</a> <a href="{{ route('assets.export') ?? '#' }}" class="block px-3 py-2 rounded hover:bg-slate-50">Export / Laporan</a> </div> </div> {{-- Pinjaman with submenu --}} <div class="sidebar-group"> <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded {{ request()->routeIs('pinjaman.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}" aria-expanded="{{ request()->routeIs('pinjaman.*') ? 'true' : 'false' }}" onclick="document.getElementById('submenu-pinjaman').classList.toggle('hidden')"> <span class="inline-flex items-center gap-3"> <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.2" d="M12 4v16m8-8H4"/></svg> Pinjaman </span> <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.2" d="M6 9l6 6 6-6"/></svg> </button> <div id="submenu-pinjaman" class="mt-1 ml-6 space-y-1 {{ request()->routeIs('pinjaman.*') ? '' : 'hidden' }}"> <a href="{{ route('pinjaman.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('pinjaman.index') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-50' }}">Senarai Pinjaman</a> <a href="{{ route('pinjaman.create') }}" class="block px-3 py-2 rounded {{ request()->routeIs('pinjaman.create') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-50' }}">Borang Pinjaman</a> <a href="{{ route('pinjaman.history') ?? '#' }}" class="block px-3 py-2 rounded hover:bg-slate-50">Sejarah Pinjaman</a> </div> </div> {{-- Aduan with submenu --}} <div class="sidebar-group"> <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded {{ request()->routeIs('aduan.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}" aria-expanded="{{ request()->routeIs('aduan.*') ? 'true' : 'false' }}" onclick="document.getElementById('submenu-aduan').classList.toggle('hidden')"> <span class="inline-flex items-center gap-3"> <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.2" d="M9 12h6M12 8v8"/></svg> Aduan </span> <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.2" d="M6 9l6 6 6-6"/></svg> </button> <div id="submenu-aduan" class="mt-1 ml-6 space-y-1 {{ request()->routeIs('aduan.*') ? '' : 'hidden' }}"> <a href="{{ route('aduan.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('aduan.index') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-50' }}">Senarai Aduan</a> <a href="{{ route('aduan.create') }}" class="block px-3 py-2 rounded {{ request()->routeIs('aduan.create') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-50' }}">Buat Aduan</a> </div> </div> {{-- Stok Requests with submenu --}} <div class="sidebar-group"> <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded {{ request()->routeIs('stok-requests.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}" aria-expanded="{{ request()->routeIs('stok-requests.*') ? 'true' : 'false' }}" onclick="document.getElementById('submenu-stok').classList.toggle('hidden')"> <span class="inline-flex items-center gap-3"> <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.2" d="M3 7h18v13H3zM8 3v4"/></svg> Permohonan Stok </span> <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.2" d="M6 9l6 6 6-6"/></svg> </button> <div id="submenu-stok" class="mt-1 ml-6 space-y-1 {{ request()->routeIs('stok-requests.*') ? '' : 'hidden' }}"> <a href="{{ route('stok-requests.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('stok-requests.index') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-50' }}">Senarai Permohonan</a> <a href="{{ route('stok-requests.create') }}" class="block px-3 py-2 rounded {{ request()->routeIs('stok-requests.create') ? 'bg-slate-100 font-semibold' : 'hover:bg-slate-50' }}">Permohonan Baru</a> <a href="{{ route('stok.index') ?? '#' }}" class="block px-3 py-2 rounded hover:bg-slate-50">Senarai Stok</a> </div> </div> </nav> </div>


  <div class="mt-6 pt-4 border-t border-white/6">
    <div class="flex items-center gap-3">
      @if(auth()->user()?->profile_pic)
        <img src="{{ asset('storage/'.auth()->user()->profile_pic) }}" class="h-11 w-11 rounded-full object-cover" alt="profile">
      @else
        <div class="h-11 w-11 rounded-full bg-gradient-to-br from-cyan-300 to-violet-600 text-black grid place-items-center font-semibold">{{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}</div>
      @endif
      <div>
        <div class="font-semibold">{{ auth()->user()->name ?? 'User' }}</div>
        <div class="micro">{{ auth()->user()?->roleName() ?? auth()->user()?->role ?? 'Staff' }}</div>
      </div>
    </div>
  </div>
</aside>

{{-- Note: This sidebar uses simple inline JS toggles so it works without Alpine. If your app already includes Alpine.js you can replace the toggles with x-data/x-show for smoother transitions. --}}


      <!-- MAIN -->
      <main>

        <!-- header -->
        <div class="header-bar">
          <div>
            <div class="neon-title">Selamat Datang, {{ auth()->user()->name ?? 'User' }}</div>
            <div class="micro mt-1">Modern Futuristic Dashboard • JAKOA ICT</div>
          </div>

          <div class="flex items-center gap-3">
            <input class="search" placeholder="Cari aset, permohonan, aduan..." />

            <div x-data="{ open:false }" class="relative">
              <button @click="open = !open" class="btn-neon inline-flex items-center gap-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.3" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1.05-.6 1.45L4 17h5"/></svg>
                Notifikasi
              </button>

              <div x-show="open" x-transition @click.outside="open=false" class="dropdown right-0 mt-2 p-2" style="display:none">
                <div class="px-3 py-2 text-sm font-semibold">Notifications</div>
                <div class="divide-y">
                  <div class="px-3 py-2 text-xs micro text-slate-200">Tiada notifikasi baru.</div>
                </div>
              </div>
            </div>

            <div x-data="{ uopen:false }" class="relative">
              <button @click="uopen = !uopen" class="user-btn flex items-center gap-2 px-3">
                @if(auth()->user()?->profile_pic)
                  <img src="{{ asset('storage/'.auth()->user()->profile_pic) }}" class="h-9 w-9 rounded-full object-cover" alt="profile">
                @else
                  <div class="h-9 w-9 rounded-full bg-gradient-to-br from-cyan-300 to-violet-600 text-black grid place-items-center font-semibold">{{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}</div>
                @endif
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.3" d="M19 9l-7 7-7-7"/></svg>
              </button>

              <div x-show="uopen" x-transition @click.outside="uopen=false" class="dropdown right-0 mt-2 p-2" style="display:none">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm hover:bg-white/5 rounded">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="w-full text-left px-3 py-2 text-sm hover:bg-white/5 rounded">Log Keluar</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        {{-- content area --}}
        <div>
          @yield('content')
        </div>

      </main>
    </div>
  </div>

  @stack('scripts')
</body>
</html>
