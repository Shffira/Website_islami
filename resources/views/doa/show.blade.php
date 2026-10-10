<x-islami-layout :title="$doa['nama'] ?? 'Detail Doa'">

    <div class="bg-emerald-800 text-white py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 text-emerald-300 text-sm mb-4">
                <a href="{{ route('doa.index') }}" class="hover:text-white">Doa Harian</a>
                <span>›</span>
                <span class="text-white">{{ $doa['nama'] }}</span>
            </div>
            <p class="text-emerald-300 text-sm font-medium mb-1">{{ $doa['grup'] }}</p>
            <h1 class="text-2xl sm:text-3xl font-bold">{{ $doa['nama'] }}</h1>
            @if(!empty($doa['tag']))
            <div class="flex flex-wrap gap-2 mt-3">
                @foreach($doa['tag'] as $tag)
                <a href="{{ route('doa.index', ['tag' => $tag]) }}"
                   class="px-2.5 py-0.5 bg-emerald-700 text-emerald-200 rounded-full text-xs font-medium hover:bg-emerald-600 transition">
                    #{{ $tag }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Doa Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">

            {{-- Teks Arab --}}
            <div class="px-6 sm:px-10 py-8 bg-gradient-to-b from-emerald-50 to-white border-b border-gray-100">
                <p class="text-right text-2xl sm:text-3xl font-arabic text-gray-800 leading-[2] select-text">
                    {{ $doa['ar'] }}
                </p>
            </div>

            {{-- Latin & Terjemahan --}}
            <div class="px-6 sm:px-10 py-6 space-y-4">
                @if(!empty($doa['tr']))
                <div>
                    <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide mb-1">Latin</p>
                    <p class="text-gray-700 italic leading-relaxed">{{ $doa['tr'] }}</p>
                </div>
                @endif

                @if(!empty($doa['idn']))
                <div>
                    <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide mb-1">Terjemahan</p>
                    <p class="text-gray-700 leading-relaxed">{{ $doa['idn'] }}</p>
                </div>
                @endif

                @if(!empty($doa['tentang']))
                <div class="pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-1">Keterangan & Sumber</p>
                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $doa['tentang'] }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Navigasi Prev / Next --}}
        <div class="flex justify-between gap-4 mb-8">
            @if($prevId)
            <a href="{{ route('doa.show', $prevId) }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl hover:border-emerald-400 transition text-sm text-gray-600">
                ← Doa Sebelumnya
            </a>
            @else
            <div></div>
            @endif

            <a href="{{ route('doa.index') }}" class="px-4 py-2.5 bg-emerald-700 text-white rounded-xl hover:bg-emerald-600 transition text-sm font-medium">
                ☰ Daftar Doa
            </a>

            @if($nextId)
            <a href="{{ route('doa.show', $nextId) }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl hover:border-emerald-400 transition text-sm text-gray-600">
                Doa Berikutnya →
            </a>
            @else
            <div></div>
            @endif
        </div>

        {{-- Doa Terkait --}}
        @if($relatedDoas->isNotEmpty())
        <section>
            <h2 class="text-lg font-bold text-gray-800 mb-4">Doa Lain dalam Kategori "{{ $doa['grup'] }}"</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($relatedDoas as $related)
                <a href="{{ route('doa.show', $related['id']) }}"
                   class="block p-4 bg-white border border-gray-200 rounded-xl hover:border-emerald-400 hover:shadow transition">
                    <p class="font-semibold text-sm text-gray-800 mb-1">{{ $related['nama'] }}</p>
                    <p class="text-right text-base font-arabic text-gray-600 leading-loose line-clamp-2">{{ $related['ar'] }}</p>
                </a>
                @endforeach
            </div>
        </section>
        @endif

    </div>

</x-islami-layout>
