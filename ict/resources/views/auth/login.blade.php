{{-- resources/views/auth/login.blade.php --}}
<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Log Masuk — JAKOA ICT</title>

  <!-- Tailwind CDN (quick) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { theme: { extend: { fontFamily: { inter: ['Inter','ui-sans-serif','system-ui'] } } } }</script>

  <!-- Alpine for small UI niceties -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <link rel="icon" href="{{ asset('images/logo_jakoa.png') }}">

  <style>
    :root{
      --neon-pink:#FF2CDF;
      --deep-blue:#0014FF;
      --cyan:#00E1FD;
      --cyan-2:#00E5FF;
      --indigo:#1200FF;
      --muted: rgba(220,230,255,0.8);
    }

    html,body { height:100%; }
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background:
        radial-gradient(700px 400px at 10% 12%, rgba(0,225,253,0.06), transparent 6%),
        radial-gradient(600px 300px at 92% 78%, rgba(255,45,140,0.05), transparent 8%),
        linear-gradient(180deg, #061a3a 0%, #03204a 55%, #00122a 100%);
      color: #eaf6ff;
      -webkit-font-smoothing:antialiased;
    }

    /* neon ribbons */
    .ribbon { position: fixed; inset:0; z-index:0; pointer-events:none; mix-blend-mode: screen; }
    .ribbon .r { position:absolute; width:140%; height:220px; left:-20%; filter: blur(36px); transform: rotate(-12deg); opacity:.45; background: linear-gradient(90deg,var(--cyan),var(--neon-pink),var(--indigo)); animation:move 18s linear infinite; }
    .ribbon .r.r2 { top:55%; opacity:.22; animation-duration: 26s; filter: blur(80px); transform: rotate(-8deg); }
    @keyframes move { 0%{ transform:translateX(-15%) rotate(-12deg)} 50%{ transform:translateX(15%) rotate(-12deg)} 100%{ transform:translateX(-15%) rotate(-12deg)} }

    /* form card */
    .glass-card {
      background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.02));
      border: 1px solid rgba(255,255,255,0.04);
      border-radius: 16px;
      padding: 28px;
      box-shadow: 0 20px 50px rgba(2,6,23,0.6);
      position: relative;
      z-index: 5;
    }

    /* subtle animated accent */
    .accent-line {
      height: 6px;
      border-radius: 999px;
      background: linear-gradient(90deg, var(--cyan), var(--indigo), var(--neon-pink));
      box-shadow: 0 8px 30px rgba(0,225,253,0.06);
      margin-bottom: 14px;
      transform: translateZ(0);
    }

    input:focus { outline: none; box-shadow: 0 6px 28px rgba(0,225,253,0.06); border-color: rgba(0,225,253,0.18) !important; }

    .btn-neon {
      background: linear-gradient(90deg, var(--cyan), var(--indigo));
      color: white;
      padding: 12px 16px;
      border-radius: 12px;
      font-weight:700;
      box-shadow: 0 12px 34px rgba(18,0,255,0.12);
      border: 1px solid rgba(255,255,255,0.03);
      transition: transform .15s ease, filter .12s;
    }
    .btn-neon:hover { transform: translateY(-3px); filter: brightness(1.03); }

    .muted { color: var(--muted); }
    .brand-title { font-weight:800; font-size:1.15rem; background: linear-gradient(90deg,var(--cyan),var(--indigo)); -webkit-background-clip:text; color:transparent; }
    @media (min-width: 768px) { .card-wrap { display:flex; gap:36px; align-items:center; } }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen">

  <div class="ribbon" aria-hidden="true">
    <div class="r r1" style="top:6%"></div>
    <div class="r r2" style="top:62%"></div>
  </div>

  <div class="w-full max-w-3xl mx-auto p-6">
    <div class="card-wrap">

      <!-- left: illustration / welcome (hidden on small screens) -->
      <div class="hidden md:flex flex-col justify-center w-1/2">
        <div class="glass-card" style="background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));">
          <div class="accent-line"></div>
          <div class="mb-4 flex items-center gap-3">
            <img src="{{ asset('images/logo_jakoa.png') }}" alt="JAKOA" class="h-12 w-12 rounded-md shadow" />
            <div>
              <div class="brand-title">Sistem Pengurusan Aset ICT</div>
              <div class="muted text-sm">Jabatan Kemajuan Orang Asli (JAKOA)</div>
            </div>
          </div>

          <div class="mt-6">
            <h2 class="text-2xl font-extrabold" style="color: white">Selamat Datang</h2>
            <p class="muted mt-2">Masuk menggunakan emel rasmi JAKOA untuk mengurus permohonan pinjaman, permohonan stok dan aduan peralatan.</p>

            <div class="mt-6">
              <ul class="space-y-3 text-sm">
                <li class="flex items-start gap-3">
                  <div class="w-8 h-8 rounded-md grid place-items-center bg-gradient-to-br from-cyan-300 to-indigo-600 text-black font-bold">✓</div>
                  <div>
                    <div class="font-semibold">Akses pantas</div>
                    <div class="muted text-xs">Permohonan & laporan dalam satu tempat</div>
                  </div>
                </li>

                <li class="flex items-start gap-3">
                  <div class="w-8 h-8 rounded-md grid place-items-center bg-gradient-to-br from-indigo-400 to-neon-pink text-black font-bold">★</div>
                  <div>
                    <div class="font-semibold">Keselamatan</div>
                    <div class="muted text-xs">Autentikasi & peranan terurus</div>
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <div class="mt-6 text-xs muted">© 2025 JAKOA ICT</div>
        </div>
      </div>

      <!-- right: form -->
      <div class="w-full md:w-1/2">
        <div class="glass-card">
          <div class="accent-line"></div>

          <div class="flex items-center justify-center gap-3 mb-4">
            <img src="{{ asset('images/logo_jakoa.png') }}" class="h-12 drop-shadow-lg" alt="JAKOA">
            <div class="text-center">
              <div class="brand-title">JAKOA ICT</div>
              <div class="muted text-sm">Sistem Pengurusan Aset</div>
            </div>
          </div>

          <!-- Session Status -->
          @if (session('status'))
            <div class="mb-4 text-sm rounded px-3 py-2" style="background: rgba(0,225,253,0.06); border:1px solid rgba(0,225,253,0.08); color:var(--muted)">{{ session('status') }}</div>
          @endif

          <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- email --}}
            <div>
              <label for="email" class="block text-sm font-semibold mb-2">Emel</label>
              <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                     class="w-full px-4 py-3 rounded-lg bg-transparent border border-white/6 text-white placeholder:muted focus:ring-2 focus:ring-cyan-300" placeholder="contoh@jakoa.gov.my" />
              @error('email') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- password --}}
            <div>
              <label for="password" class="block text-sm font-semibold mb-2">Kata Laluan</label>
              <input id="password" name="password" type="password" required
                     class="w-full px-4 py-3 rounded-lg bg-transparent border border-white/6 text-white placeholder:muted focus:ring-2 focus:ring-cyan-300" placeholder="Masukkan kata laluan anda" />
              @error('password') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- remember / forgot --}}
            <div class="flex items-center justify-between text-sm">
              <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-white/10 bg-transparent" />
                <span class="muted">Ingat saya</span>
              </label>

              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-semibold" style="color: var(--cyan-2)">Lupa kata laluan?</a>
              @endif
            </div>

            {{-- submit --}}
            <div>
              <button type="submit" class="btn-neon w-full">Log Masuk</button>
            </div>
          </form>

          <div class="mt-6 text-center text-sm muted">
            Tiada akaun? Sila hubungi <a href="mailto:it-support@jakoa.gov.my" class="font-semibold" style="color:var(--cyan-2)">IT Support</a>
          </div>
        </div>

        <div class="mt-4 text-center text-xs muted">© 2025 JAKOA ICT Asset Management System</div>
      </div>
    </div>
  </div>

</body>
</html>
