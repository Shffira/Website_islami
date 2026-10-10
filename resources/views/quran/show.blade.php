<x-islami-layout :title="'Q.S. ' . ($surah['namaLatin'] ?? '') . ' — Al-Qur\'an'">

    @php
        $nomor = $surah['nomor'];
        $ayatList = $surah['ayat'] ?? [];
        $prevSurah = $surah['suratSebelumnya'] ?? null;
        $nextSurah = $surah['suratSelanjutnya'] ?? null;
    @endphp

    {{-- Header Surah --}}
    <div class="bg-emerald-800 text-white py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-emerald-300 text-sm mb-4">
                <a href="{{ route('quran.index') }}" class="hover:text-white">Al-Qur'an</a>
                <span>›</span>
                <span class="text-white">Surah {{ $surah['namaLatin'] }}</span>
            </div>

            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-3xl font-bold">{{ $surah['namaLatin'] }}</h1>
                    <p class="text-emerald-200 mt-1">{{ $surah['arti'] }}</p>
                    <div class="flex items-center gap-3 mt-2 text-sm text-emerald-300">
                        <span>Surah ke-{{ $nomor }}</span>
                        <span>·</span>
                        <span>{{ $surah['jumlahAyat'] }} ayat</span>
                        <span>·</span>
                        <span>{{ $surah['tempatTurun'] }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-5xl font-arabic text-emerald-100">{{ $surah['nama'] }}</p>
                </div>
            </div>

            {{-- Audio Lengkap per Surah --}}
            @if(!empty($surah['audioFull']))
            <div x-data="{ qari: '05' }" class="mt-6 bg-emerald-700 rounded-xl p-4">
                <p class="text-sm text-emerald-200 mb-2 font-medium">🎧 Audio Seluruh Surah</p>
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($qariList as $kode => $namaQari)
                    <button
                        @click="qari = '{{ $kode }}'"
                        :class="qari === '{{ $kode }}' ? 'bg-white text-emerald-800' : 'bg-emerald-600 text-white hover:bg-emerald-500'"
                        class="px-3 py-1 rounded-full text-xs font-medium transition"
                    >
                        {{ $namaQari }}
                    </button>
                    @endforeach
                </div>
                @foreach($qariList as $kode => $namaQari)
                <audio
                    x-show="qari === '{{ $kode }}'"
                    controls
                    class="w-full"
                    :src="'{{ $surah['audioFull'][$kode] ?? '' }}'"
                    src="{{ $surah['audioFull'][$kode] ?? '' }}"
                >
                </audio>
                @endforeach
            </div>
            @endif

        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Deskripsi --}}
        @if(!empty($surah['deskripsi']))
        <details class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
            <summary class="font-semibold text-amber-800 cursor-pointer select-none">📜 Tentang Surah Ini</summary>
            <div class="mt-3 text-sm text-amber-900 leading-relaxed prose max-w-none">
                {!! $surah['deskripsi'] !!}
            </div>
        </details>
        @endif

        {{-- Audio Player Controls --}}
        <div x-data="{ qari: '05', showTafsir: false }" class="space-y-0">

            {{-- Pilihan Qari Ayat per Ayat --}}
            <div class="flex flex-wrap items-center gap-2 mb-6 p-4 bg-gray-100 rounded-xl">
                <span class="text-sm font-medium text-gray-600 mr-1">🎙 Qari:</span>
                @foreach($qariList as $kode => $namaQari)
                <button
                    @click="qari = '{{ $kode }}'"
                    :class="qari === '{{ $kode }}' ? 'bg-emerald-700 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50'"
                    class="px-3 py-1 rounded-full text-xs font-medium transition"
                >
                    {{ $namaQari }}
                </button>
                @endforeach
            </div>

            {{-- Basmalah (kecuali At-Taubah = 9 dan Al-Fatihah = 1 yang sudah ada di ayat 1) --}}
            @if($nomor !== 9 && $nomor !== 1)
            <div class="text-center py-6 bg-white border border-gray-100 rounded-xl mb-4 shadow-sm">
                <p class="text-2xl font-arabic text-gray-800">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</p>
                <p class="text-sm text-gray-500 mt-1">Dengan nama Allah Yang Maha Pengasih lagi Maha Penyayang</p>
            </div>
            @endif

            {{-- Daftar Ayat --}}
            @forelse($ayatList as $ayat)
            @php $nomorAyat = $ayat['nomorAyat'] ?? 0; @endphp
            <div id="ayat-{{ $nomorAyat }}" class="bg-white border border-gray-100 rounded-xl shadow-sm mb-3 overflow-hidden hover:border-emerald-200 transition">

                {{-- Nomor ayat bar --}}
                <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                    <div class="w-8 h-8 flex items-center justify-center bg-emerald-100 text-emerald-700 font-bold rounded-full text-sm">
                        {{ $nomorAyat }}
                    </div>
                    {{-- Tombol play ayat --}}
                    @if(!empty($ayat['audio']))
                    <button
                        @click="
                            const audio = $el.closest('.ayat-container').querySelector('audio');
                            if (audio.paused) { audio.play(); $el.textContent='⏸'; }
                            else { audio.pause(); $el.textContent='▶'; }
                        "
                        class="text-emerald-600 hover:text-emerald-800 transition text-sm font-medium px-3 py-1 rounded-full hover:bg-emerald-50 border border-emerald-200"
                    >▶ Putar</button>
                    @endif
                </div>

                <div class="ayat-container px-5 py-4">
                    {{-- Teks Arab --}}
                    <p class="text-right text-2xl font-arabic text-gray-800 leading-loose mb-4">
                        {{ $ayat['teksArab'] ?? '' }}
                    </p>

                    {{-- Audio element (tersembunyi, dikontrol tombol) --}}
                    @if(!empty($ayat['audio']))
                    <template x-for="[k, url] in Object.entries({{ json_encode($ayat['audio'] ?? []) }})" :key="k">
                        <audio
                            x-show="qari === k"
                            :src="url"
                            class="hidden"
                        ></audio>
                    </template>
                    {{-- Fallback static for crawlers --}}
                    @foreach($qariList as $kode => $namaQari)
                    @if(!empty($ayat['audio'][$kode]))
                    <audio
                        x-show="qari === '{{ $kode }}'"
                        src="{{ $ayat['audio'][$kode] }}"
                        class="hidden"
                        @ended="$el.closest('.ayat-container').querySelector('button').textContent='▶'"
                    ></audio>
                    @endif
                    @endforeach
                    @endif

                    {{-- Latin --}}
                    <p class="text-sm text-emerald-700 italic leading-relaxed mb-2">{{ $ayat['teksLatin'] ?? '' }}</p>

                    {{-- Terjemahan --}}
                    <p class="text-sm text-gray-700 leading-relaxed">
                        <span class="text-gray-400 mr-1">{{ $nomorAyat }}.</span>{{ $ayat['teksIndonesia'] ?? '' }}
                    </p>

                    {{-- Tafsir (jika ada) --}}
                    @if(!empty($tafsirMap[$nomorAyat]))
                    <details class="mt-3">
                        <summary class="text-xs font-medium text-amber-700 cursor-pointer hover:text-amber-900 select-none">📖 Lihat Tafsir Kemenag</summary>
                        <p class="mt-2 text-xs text-gray-600 leading-relaxed bg-amber-50 border border-amber-100 rounded-lg p-3">
                            {{ $tafsirMap[$nomorAyat] }}
                        </p>
                    </details>
                    @endif
                </div>

            </div>
            @empty
            <div class="text-center py-12 text-gray-500">
                <p>Data ayat tidak dapat dimuat.</p>
            </div>
            @endforelse

        </div>

        {{-- Navigasi Prev / Next --}}
        <div class="flex justify-between gap-4 mt-8 pt-6 border-t border-gray-200">
            @if($prevSurah && is_array($prevSurah))
            <a href="{{ route('quran.show', $prevSurah['nomor']) }}"
               class="flex items-center gap-3 px-5 py-3 bg-white border border-gray-200 rounded-xl hover:border-emerald-400 hover:shadow transition flex-1 max-w-sm">
                <span class="text-gray-400">←</span>
                <div>
                    <p class="text-xs text-gray-400">Sebelumnya</p>
                    <p class="font-semibold text-gray-800">{{ $prevSurah['namaLatin'] }}</p>
                </div>
            </a>
            @else
            <div></div>
            @endif

            <a href="{{ route('quran.index') }}"
               class="px-5 py-3 bg-white border border-gray-200 rounded-xl hover:border-emerald-400 hover:shadow transition text-sm text-gray-600 self-center">
                ☰ Daftar Surah
            </a>

            @if($nextSurah && is_array($nextSurah))
            <a href="{{ route('quran.show', $nextSurah['nomor']) }}"
               class="flex items-center justify-end gap-3 px-5 py-3 bg-white border border-gray-200 rounded-xl hover:border-emerald-400 hover:shadow transition flex-1 max-w-sm text-right">
                <div>
                    <p class="text-xs text-gray-400">Berikutnya</p>
                    <p class="font-semibold text-gray-800">{{ $nextSurah['namaLatin'] }}</p>
                </div>
                <span class="text-gray-400">→</span>
            </a>
            @else
            <div></div>
            @endif
        </div>

    </div>

</x-islami-layout>
