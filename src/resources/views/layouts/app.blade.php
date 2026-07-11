<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Voltfix - Servis Laptop, HP & TV')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                        display: ['Bricolage Grotesque', 'Plus Jakarta Sans', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-body: 'Plus Jakarta Sans', system-ui, sans-serif;
            --font-display: 'Bricolage Grotesque', 'Plus Jakarta Sans', sans-serif;
        }
        body {
            font-family: var(--font-body);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-feature-settings: 'kern' 1, 'liga' 1;
            background:
                radial-gradient(ellipse 60% 40% at 0% 0%, rgba(251,146,60,.08) 0%, transparent 50%),
                radial-gradient(ellipse 50% 30% at 100% 0%, rgba(56,189,248,.07) 0%, transparent 45%),
                #FAFAF9;
        }

        h1, h2, h3, h4, .font-display {
            font-family: var(--font-display);
            letter-spacing: -0.025em;
        }
        .brand-name {
            font-family: var(--font-display);
            font-weight: 800;
            letter-spacing: -0.045em;
        }
        .text-stat {
            font-family: var(--font-display);
            font-weight: 800;
            font-variant-numeric: tabular-nums;
            letter-spacing: -0.04em;
            line-height: 1;
        }
        .section-eyebrow {
            font-family: var(--font-display);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .lead-text {
            font-size: 1.0625rem;
            line-height: 1.7;
            letter-spacing: 0.01em;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            color: #64748B;
            transition: all .15s;
            white-space: nowrap;
        }
        .nav-link:hover { background: #FFF7ED; color: #C2410C; }
        .nav-link.active {
            background: linear-gradient(135deg, #FFF7ED, #FFEDD5);
            color: #EA580C;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(249,115,22,.15);
        }
        .band-bg {
            background:
                radial-gradient(circle at 18% 50%, rgba(249,115,22,.22) 0%, transparent 48%),
                radial-gradient(circle at 82% 50%, rgba(56,189,248,.12) 0%, transparent 42%),
                linear-gradient(90deg, #0f172a 0%, #1e293b 42%, #7c2d12 100%);
        }

        .btn-primary   { @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-xl transition-colors shadow-sm; }
        .btn-secondary { @apply bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-xl transition-colors; }
        .btn-danger    { @apply bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-xl transition-colors; }
        .card          { @apply bg-white rounded-2xl shadow-sm border border-gray-100 p-6; }
        .badge-status  { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium; }
    </style>
    @stack('head')
</head>
<body class="min-h-screen flex flex-col antialiased">

    {{-- ── Navbar ─────────────────────────────────────────────────────────── --}}
    <nav class="bg-white/95 backdrop-blur-md border-b border-orange-200/60 shadow-sm sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-15" style="height:60px">

                {{-- Brand --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-amber-500 rounded-lg flex items-center justify-center shadow-md shadow-orange-100">
                        <svg class="w-4 h-4 text-white" style="width:17px;height:17px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="brand-name text-slate-900 text-base">Voltfix</span>
                </a>

                {{-- Nav links --}}
                @auth
                <div class="hidden md:flex items-center gap-0.5">
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('customer.tickets.create') }}" class="nav-link {{ request()->routeIs('customer.tickets.create') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Ajukan Servis
                        </a>
                        <a href="{{ route('customer.tickets.index') }}" class="nav-link {{ request()->routeIs('customer.tickets.*') && !request()->routeIs('customer.tickets.create') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Tiket Saya
                        </a>
                    @elseif(auth()->user()->isTechnician())
                        <a href="{{ route('technician.dashboard') }}" class="nav-link {{ request()->routeIs('technician.dashboard') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('technician.tickets') }}" class="nav-link {{ request()->routeIs('technician.tickets') || request()->routeIs('technician.ticket.*') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Tiket Tugasan
                        </a>
                        <a href="{{ route('technician.profile.edit') }}" class="nav-link {{ request()->routeIs('technician.profile.*') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya
                        </a>
                    @endif
                </div>
                @endauth

                {{-- Right: user info + logout --}}
                <div class="flex items-center gap-2.5">
                    @auth
                        @php $authUser = auth()->user(); @endphp
                        <div class="flex items-center gap-2">
                            @if($authUser->isTechnician())
                            <a href="{{ route('technician.profile.edit') }}" class="flex items-center gap-2 group">
                            @else
                            <div class="flex items-center gap-2">
                            @endif
                                @if($authUser->avatarUrl())
                                    <img src="{{ $authUser->avatarUrl() }}" alt="{{ $authUser->name }}"
                                         class="w-8 h-8 rounded-full object-cover flex-shrink-0 shadow-sm border-2 border-white">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <span class="text-white font-bold text-xs">{{ $authUser->initials() }}</span>
                                    </div>
                                @endif
                                <div class="hidden sm:block">
                                    <p class="text-xs font-semibold text-gray-800 leading-tight group-hover:text-orange-600 transition-colors">{{ $authUser->name }}</p>
                                    @php
                                    $roleColors = [
                                        'CUSTOMER'   => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'TECHNICIAN' => 'bg-teal-50 text-teal-600 border-teal-100',
                                        'ADMIN'      => 'bg-red-50 text-red-600 border-red-100',
                                        'MANAGER'    => 'bg-amber-50 text-amber-700 border-amber-100',
                                    ];
                                    $rc = $roleColors[$authUser->role] ?? 'bg-gray-50 text-gray-500 border-gray-100';
                                    @endphp
                                    <span class="text-[10px] font-semibold {{ $rc }} border px-1.5 py-0 rounded-md tracking-wide">
                                        {{ $authUser->role }}
                                    </span>
                                </div>
                            @if($authUser->isTechnician())
                            </a>
                            @else
                            </div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-xs font-medium text-gray-400 hover:text-red-500 hover:bg-red-50 px-2.5 py-1.5 rounded-lg transition-colors">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-all">Login</a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white px-4 py-2 rounded-lg shadow-sm shadow-orange-200 transition-all">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ── Flash messages ──────────────────────────────────────────────────── --}}
    @foreach(['success' => 'green', 'error' => 'red', 'warning' => 'amber'] as $type => $color)
    @if(session($type))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="mx-4 mt-4 sm:mx-auto sm:max-w-6xl">
        <div class="flex items-center gap-3 bg-{{ $color }}-50 border border-{{ $color }}-200/60 text-{{ $color }}-800 px-4 py-3 rounded-xl shadow-sm">
            @if($type === 'success')
            <svg class="w-4 h-4 text-{{ $color }}-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            @else
            <svg class="w-4 h-4 text-{{ $color }}-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            @endif
            <span class="text-sm font-medium">{{ session($type) }}</span>
            <button @click="show = false" class="ml-auto text-{{ $color }}-400 hover:text-{{ $color }}-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    @endif
    @endforeach

    {{-- ── Page content ─────────────────────────────────────────────────────── --}}
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-7">
        @yield('content')
    </main>

    <footer class="mt-auto band-bg border-t border-white/10 py-6 text-center text-xs">
        <p class="text-slate-400">&copy; {{ date('Y') }} <span class="brand-name text-orange-400">Voltfix</span> — servis laptop, HP & TV · Kab. Tangerang</p>
    </footer>

    @stack('scripts')
</body>
</html>
