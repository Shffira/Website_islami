<x-islami-layout title="Beranda">

    {{-- Hero Section --}}
    <section class="bg-gradient-to-b from-emerald-800 to-emerald-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-emerald-200 text-xl font-arabic mb-2">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</p>
            <h1 class="text-4xl sm:text-5xl font-bold mb-4">Nurul Quran</h1>
            <p class="text-emerald-100 text-lg mb-8 max-w-2xl mx-auto">Portal Islami digital — baca Al-Qur'an, pelajari doa harian, dan cek jadwal sholat kota Anda.</p>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('quran.index') }}"
                   class="px-6 py-3 bg-white text-emerald-800 font-semibold rounded-xl hover:bg-emerald-50 transition shadow">
                     Buka Al-Qur'an
                </a>
                <a href="{{ route('doa.index') }}"
                   class="px-6 py-3 bg-emerald-700 text-white font-semibold rounded-xl hover:bg-emerald-600 border border-emerald-400 transition">
                     Kumpulan Doa
                </a>
            </div>
        </div>
    </section>

    {{-- Stats Bar --}}
    <section class="bg-white border-b border-gray-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <div>
                    <p class="text-3xl font-bold text-emerald-700">{{ $totalSurah }}</p>
                    <p class="text-sm text-gray-500 mt-1">Surah Al-Qur'an</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-emerald-700">6.236</p>
                    <p class="text-sm text-gray-500 mt-1">Ayat</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-emerald-700">{{ $totalDoa }}</p>
                    <p class="text-sm text-gray-500 mt-1">Doa Harian</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-emerald-700">517</p>
                    <p class="text-sm text-gray-500 mt-1">Kab/Kota Jadwal Sholat</p>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Main Column --}}
            <div class="lg:col-span-2 space-y-10">

                {{-- Surah Pilihan --}}
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">📖 Surah Pilihan</h2>
                        <a href="{{ route('quran.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Lihat semua →</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($featuredSurahs as $surah)
                        <a href="{{ route('quran.show', $surah['nomor']) }}"
                           class="flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-200 hover:border-emerald-400 hover:shadow-md transition group">
                            <div class="w-10 h-10 flex items-center justify-center bg-emerald-100 text-emerald-700 font-bold rounded-full text-sm shrink-0 group-hover:bg-emerald-700 group-hover:text-white transition">
                                {{ $surah['nomor'] }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-800 group-hover:text-emerald-700 transition">{{ $surah['namaLatin'] }}</p>
                                <p class="text-sm text-gray-500">{{ $surah['arti'] }} · {{ $surah['jumlahAyat'] }} ayat</p>
                            </div>
                            <div class="ml-auto text-right text-gray-400 text-xl font-arabic leading-none">
                                {{ $surah['nama'] }}
                            </div>
                        </a>
                        @endforeach
                    </div>
                </section>

                {{-- Doa Pilihan --}}
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800"> Doa Harian Pilihan</h2>
                        <a href="{{ route('doa.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Lihat semua →</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($featuredDoas as $doa)
                        <a href="{{ route('doa.show', $doa['id']) }}"
                           class="block p-4 bg-white rounded-xl border border-gray-200 hover:border-emerald-400 hover:shadow-md transition">
                            <p class="font-semibold text-gray-800 text-sm mb-1 truncate">{{ $doa['nama'] }}</p>
                            <p class="text-xs text-emerald-600 mb-2 font-medium">{{ $doa['grup'] }}</p>
                            <p class="text-right text-base text-gray-700 font-arabic leading-loose line-clamp-2">{{ Str::limit($doa['ar'], 80) }}</p>
                        </a>
                        @endforeach
                    </div>
                </section>

            </div>

            {{-- Sidebar: Jadwal Sholat Hari Ini --}}
            <aside>
                <div class="bg-emerald-800 text-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="px-6 py-5 border-b border-emerald-700">
                        <h2 class="text-lg font-bold"> Jadwal Sholat Hari Ini</h2>
                        <p class="text-emerald-300 text-sm">{{ $kotaDefault }}, {{ $provinsiDefault }}</p>
                    </div>

                    @if($todayJadwal)
                    <div class="px-6 py-5 space-y-3">
                        @php
                            $waktuSholat = [
                                'Imsak'   => $todayJadwal['imsak']   ?? '-',
                                'Subuh'   => $todayJadwal['subuh']   ?? '-',
                                'Terbit'  => $todayJadwal['terbit']  ?? '-',
                                'Dhuha'   => $todayJadwal['dhuha']   ?? '-',
                                'Dzuhur'  => $todayJadwal['dzuhur']  ?? '-',
                                'Ashar'   => $todayJadwal['ashar']   ?? '-',
                                'Maghrib' => $todayJadwal['maghrib'] ?? '-',
                                'Isya'    => $todayJadwal['isya']    ?? '-',
                            ];
                        @endphp
                        @foreach($waktuSholat as $nama => $jam)
                        <div class="flex justify-between items-center py-1.5 border-b border-emerald-700 last:border-0">
                            <span class="text-emerald-200 text-sm">{{ $nama }}</span>
                            <span class="font-bold text-lg">{{ $jam }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="px-6 py-8 text-center text-emerald-300">
                        <p>Data jadwal tidak tersedia.</p>
                    </div>
                    @endif

                    <div class="px-6 pb-5">
                        <a href="{{ route('shalat.index') }}"
                           class="block w-full text-center py-2.5 bg-emerald-600 hover:bg-emerald-500 rounded-lg text-sm font-semibold transition">
                            Cek Kota Lain →
                        </a>
                    </div>
                </div>

                {{-- Ayat Motivasi --}}
                <div class="mt-6 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-right text-xl font-arabic text-gray-800 leading-loose mb-3">
                        ﴿ إِنَّ مَعَ ٱلۡعُسۡرِ يُسۡرًا ﴾
                    </p>
                    <p class="text-sm text-gray-600 italic">"Sesungguhnya bersama kesulitan ada kemudahan."</p>
                    <p class="text-xs text-emerald-600 mt-1 font-medium">— Q.S. Al-Insyirah: 6</p>
                </div>
            </aside>

        </div>
    </div>

</x-islami-layout>
