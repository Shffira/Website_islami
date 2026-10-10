@props(['title' => 'Nurul Quran'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — Nurul Quran</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-arabic {
            font-family: 'Amiri', 'Traditional Arabic', 'Arial Unicode MS', serif;
            direction: rtl;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    {{-- Navbar --}}
    <nav x-data="{ open: false }" class="sticky top-0 z-50 bg-emerald-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Logo & Primary Nav --}}
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-white font-bold text-lg shrink-0">
                        <span class="text-2xl leading-none">☪</span>
                        <span>Nurul Quran</span>
                    </a>

                    <div class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('quran.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition
                                  {{ request()->routeIs('quran.*') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
                            📖 Al-Qur'an
                        </a>
                        <a href="{{ route('doa.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition
                                  {{ request()->routeIs('doa.*') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
                            🤲 Doa Harian
                        </a>
                        <a href="{{ route('shalat.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition
                                  {{ request()->routeIs('shalat.*') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-700 hover:text-white' }}">
                            🕌 Jadwal Sholat
                        </a>
                    </div>
                </div>

                {{-- Hamburger --}}
                <div class="flex items-center sm:hidden">
                    <button @click="open = !open" class="p-2 rounded-md text-emerald-200 hover:text-white hover:bg-emerald-700 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-emerald-700">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('quran.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-emerald-100 hover:bg-emerald-700 hover:text-white {{ request()->routeIs('quran.*') ? 'bg-emerald-700 text-white' : '' }}">📖 Al-Qur'an</a>
                <a href="{{ route('doa.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-emerald-100 hover:bg-emerald-700 hover:text-white {{ request()->routeIs('doa.*') ? 'bg-emerald-700 text-white' : '' }}">🤲 Doa Harian</a>
                <a href="{{ route('shalat.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-emerald-100 hover:bg-emerald-700 hover:text-white {{ request()->routeIs('shalat.*') ? 'bg-emerald-700 text-white' : '' }}">🕌 Jadwal Sholat</a>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-emerald-900 text-emerald-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
                <div>
                    <h3 class="text-white font-semibold mb-2 text-base">☪ Nurul Quran</h3>
                    <p class="text-emerald-300">Portal Islami berbasis eQuran.id API — menyediakan Al-Qur'an, doa harian, dan jadwal sholat.</p>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-2">Layanan</h3>
                    <ul class="space-y-1">
                        <li><a href="{{ route('quran.index') }}" class="hover:text-white transition">📖 Al-Qur'an Digital</a></li>
                        <li><a href="{{ route('doa.index') }}" class="hover:text-white transition">🤲 Doa Harian</a></li>
                        <li><a href="{{ route('shalat.index') }}" class="hover:text-white transition">🕌 Jadwal Sholat</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-2">Sumber Data</h3>
                    <p class="text-emerald-300">Data dari <a href="https://equran.id" target="_blank" rel="noopener" class="text-emerald-400 hover:text-white">eQuran.id</a>, bersumber dari Kementerian Agama RI.</p>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-emerald-800 text-center text-xs text-emerald-500">
                © {{ date('Y') }} Nurul Quran · Dibuat untuk tugas RPL · Integrasi API eQuran.id
            </div>
        </div>
    </footer>

</body>
</html>