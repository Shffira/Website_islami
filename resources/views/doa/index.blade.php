<x-islami-layout title="Doa Harian">

    <div class="bg-emerald-800 text-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-2">🤲 Kumpulan Doa Harian</h1>
            <p class="text-emerald-200">{{ $totalCount }} doa lengkap dengan teks Arab, Latin, terjemahan, sumber, dan kategori.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if($apiError)
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
            ⚠️ Gagal memuat daftar doa dari server. Silakan coba beberapa saat lagi.
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Sidebar Filter --}}
            <aside class="lg:w-64 shrink-0">
                <form method="GET" action="{{ route('doa.index') }}" id="filterForm">

                    {{-- Search --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Doa</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                            <input type="text" name="q" value="{{ $query }}"
                                placeholder="Nama atau teks doa..."
                                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        </div>
                    </div>

                    {{-- Filter Grup --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="grup" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none bg-white"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($groups as $grup)
                            <option value="{{ $grup }}" {{ $selectedGrup === $grup ? 'selected' : '' }}>{{ $grup }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Tag --}}
                    @if($tags->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tag Populer</label>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($tags->take(20) as $tag)
                            <a href="{{ route('doa.index', array_merge(request()->query(), ['tag' => $tag])) }}"
                               class="px-2 py-0.5 rounded-full text-xs font-medium border transition
                                      {{ $selectedTag === $tag ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-white text-gray-600 border-gray-300 hover:border-emerald-400 hover:text-emerald-700' }}">
                                #{{ $tag }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 py-2 bg-emerald-700 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition">
                            Filter
                        </button>
                        @if($query || $selectedGrup || $selectedTag)
                        <a href="{{ route('doa.index') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>

                {{-- Stats --}}
                <div class="mt-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm">
                    <p class="font-semibold text-emerald-800 mb-1">Hasil Pencarian</p>
                    <p class="text-emerald-700">Menampilkan <strong>{{ $filteredCount }}</strong> dari {{ $totalCount }} doa</p>
                </div>
            </aside>

            {{-- Doa Grid --}}
            <div class="flex-1 min-w-0">
                @if($doas->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-5xl mb-4">🤲</p>
                    <p class="text-lg font-medium">Doa tidak ditemukan</p>
                    <p class="text-sm mt-1">Coba kata kunci atau kategori lain.</p>
                    <a href="{{ route('doa.index') }}" class="mt-4 inline-block text-emerald-600 hover:underline">← Tampilkan semua</a>
                </div>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($doas as $doa)
                    <a href="{{ route('doa.show', $doa['id']) }}"
                       class="block bg-white border border-gray-200 rounded-xl p-4 hover:border-emerald-400 hover:shadow-md transition group">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div>
                                <p class="font-semibold text-gray-800 group-hover:text-emerald-700 transition text-sm leading-snug">{{ $doa['nama'] }}</p>
                                <p class="text-xs text-emerald-600 mt-0.5 font-medium">{{ $doa['grup'] }}</p>
                            </div>
                            <span class="shrink-0 text-sm text-gray-400 font-bold">#{{ $doa['id'] }}</span>
                        </div>
                        <p class="text-right text-base font-arabic text-gray-700 leading-loose line-clamp-3">{{ $doa['ar'] }}</p>
                        <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $doa['idn'] }}</p>
                        @if(!empty($doa['tag']))
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach(array_slice($doa['tag'], 0, 3) as $tag)
                            <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[10px] font-medium">#{{ $tag }}</span>
                            @endforeach
                        </div>
                        @endif
                    </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $doas->links() }}
                </div>
                @endif
            </div>

        </div>

    </div>

</x-islami-layout>
