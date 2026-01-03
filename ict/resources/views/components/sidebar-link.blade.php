<a href="{{ route($route) }}" class="px-3 py-2 rounded flex items-center gap-2 hover:bg-white/5 {{ request()->routeIs($route.'*') ? 'bg-gradient-to-r from-cyan-400 to-indigo-500 text-black' : '' }}">
  {{ $label }}
</a>