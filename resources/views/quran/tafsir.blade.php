<x-islami-layout :title="'Tafsir ' . ($surah['namaLatin'] ?? '')">

    @php $nomor = $surah['nomor']; @endphp

    <div class="bg-emerald-800 text-white py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 text-emerald-300 text-sm mb-4">
                <a href="{{ route('quran.index') }}" class="hover:text-white">Al-Qur'an</a>
                <span>›</span>
                <a href="{{ route('quran.show', $nomor) }}" class="hover:text-white">{{ $surah['namaLatin'] }}</a>
                <span>›</span>
                <span class="text-white">Tafsir</span>
            </div>
            <h1 class="text-3xl font-bold">Tafsir Q.S. {{ $surah['namaLatin'] }}</h1>
            <p class="text-emerald-200 mt-1">{{ $surah['jumlahAyat'] }} ayat · {{ $surah['tempatTurun'] }} · {{ $surah['arti'] }}</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex justify-end mb-4">
            <a href="{{ route('quran.show', $nomor) }}" class="text-sm text-emerald-600 hover:underline">← Kembali ke halaman baca</a>
        </div>

        @php
            $ayatMap = [];
            foreach ($surah['ayat'] ?? [] as $a) {
                $ayatMap[$a['nomorAyat']] = $a;
            }
            $tafsirList = $tafsir['tafsir'] ?? [];
        @endphp

        @forelse($tafsirList as $t)
        @php $nomorAyat = $t['ayat']; @endphp
        <div class="mb-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                <div class="w-8 h-8 flex items-center justify-center bg-emerald-100 text-emerald-700 font-bold rounded-full text-sm shrink-0">
                    {{ $nomorAyat }}
                </div>
                <span class="text-sm text-gray-500">Ayat {{ $nomorAyat }}</span>
            </div>

            <div class="px-5 py-4">
                {{-- Teks Arab --}}
                @if(!empty($ayatMap[$nomorAyat]['teksArab']))
                <p class="text-right text-xl font-arabic text-gray-800 leading-loose mb-3">
                    {{ $ayatMap[$nomorAyat]['teksArab'] }}
                </p>
                @endif

                {{-- Terjemahan --}}
                @if(!empty($ayatMap[$nomorAyat]['teksIndonesia']))
                <p class="text-sm text-emerald-700 italic mb-3">{{ $ayatMap[$nomorAyat]['teksIndonesia'] }}</p>
                @endif

                {{-- Tafsir Kemenag --}}
                <div class="mt-3 p-4 bg-amber-50 border border-amber-100 rounded-lg">
                    <p class="text-xs font-semibold text-amber-700 mb-2">Tafsir Kemenag RI</p>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $t['teks'] }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-500">
            <p>Data tafsir tidak tersedia untuk surah ini.</p>
        </div>
        @endforelse

        <div class="text-center mt-8">
            <a href="{{ route('quran.show', $nomor) }}" class="px-6 py-2.5 bg-emerald-700 text-white rounded-xl hover:bg-emerald-600 transition font-medium">
                ← Kembali Baca Surah
            </a>
        </div>

    </div>

</x-islami-layout>
