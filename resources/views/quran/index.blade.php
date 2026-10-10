<x-islami-layout title="Al-Qur'an Digital">

    {{-- Page Header --}}
    <div class="bg-emerald-800 text-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-2">📖 Al-Qur'an Digital</h1>
            <p class="text-emerald-200">Baca {{ $totalSurahs }} surah lengkap dengan teks Arab, Latin, terjemahan, tafsir, dan audio dari 6 qari terbaik.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- API Error --}}
        @if($apiError)
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
            ⚠️ Gagal memuat data dari server eQuran.id. Silakan coba beberapa saat lagi.
        </div>
        @endif

        {{-- Filter & Search --}}
        <form method="GET" action="{{ route('quran.index') }}" class="mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $query }}"
                        placeholder="Cari nama, arti, nomor surah..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                    >
                </div>
                <select name="tempat" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                    <option value="">Semua Tempat Turun</option>
                    <option value="Mekah" {{ $tempat === 'Mekah' ? 'selected' : '' }}>Makkiyyah (Mekah)</option>
                    <option value="Madinah" {{ $tempat === 'Madinah' ? 'selected' : '' }}>Madaniyyah (Madinah)</option>
                </select>
                <button type="submit" class="px-6 py-2.5 bg-emerald-700 text-white rounded-xl hover:bg-emerald-600 transition font-medium">
                    Cari
                </button>
                @if($query || $tempat)
                <a href="{{ route('quran.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition text-gray-600 text-center">
                    Reset
                </a>
                @endif
            </div>
        </form>

        {{-- Results Count --}}
        @if($query || $tempat)
        <p class="text-sm text-gray-500 mb-4">
            Menampilkan <strong>{{ $surahs->count() }}</strong> dari {{ $totalSurahs }} surah
            @if($query) untuk pencarian "<em>{{ $query }}</em>"@endif
            @if($tempat) di wilayah <em>{{ $tempat }}</em>@endif
        </p>
        @endif

        {{-- Surah Grid --}}
        @if($surahs->isEmpty())
        <div class="text-center py-16 text-gray-500">
            <p class="text-5xl mb-4">📭</p>
            <p class="text-lg font-medium">Surah tidak ditemukan</p>
            <p class="text-sm mt-1">Coba kata kunci lain atau hapus filter.</p>
            <a href="{{ route('quran.index') }}" class="mt-4 inline-block text-emerald-600 hover:underline">← Tampilkan semua</a>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($surahs as $surah)
            <a href="{{ route('quran.show', $surah['nomor']) }}"
               class="flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-200 hover:border-emerald-400 hover:shadow-md transition group">
                {{-- Nomor --}}
                <div class="w-10 h-10 flex items-center justify-center bg-emerald-100 text-emerald-700 font-bold rounded-full text-sm shrink-0 group-hover:bg-emerald-700 group-hover:text-white transition">
                    {{ $surah['nomor'] }}
                </div>
                {{-- Info --}}
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-gray-800 group-hover:text-emerald-700 transition truncate">{{ $surah['namaLatin'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $surah['arti'] }} · {{ $surah['jumlahAyat'] }} ayat ·
                        <span class="text-emerald-600">{{ $surah['tempatTurun'] }}</span>
                    </p>
                </div>
                {{-- Arab --}}
                <div class="text-gray-500 text-xl font-arabic leading-none shrink-0">{{ $surah['nama'] }}</div>
            </a>
            @endforeach
        </div>
        @endif

    </div>

</x-islami-layout>
