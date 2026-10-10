<x-islami-layout title="Jadwal Sholat">

    <div class="bg-emerald-800 text-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-2"> Jadwal Sholat</h1>
            <p class="text-emerald-200">Jadwal sholat bulanan untuk 517 kabupaten/kota di 34 provinsi Indonesia.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Form Pilih Lokasi --}}
        <form method="GET" action="{{ route('shalat.index') }}"
              x-data="{
                  provinsi: '{{ addslashes($selectedProvinsi) }}',
                  kabKotaOptions: @json($kabKotaList),
                  loadingKabKota: false,
                  async updateKabKota() {
                      if (!this.provinsi) return;
                      this.loadingKabKota = true;
                      try {
                          const res = await fetch('{{ route('shalat.kabkota') }}?provinsi=' + encodeURIComponent(this.provinsi));
                          const json = await res.json();
                          this.kabKotaOptions = json.data || [];
                      } catch(e) { this.kabKotaOptions = []; }
                      this.loadingKabKota = false;
                  }
              }"
              class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-8">

            <h2 class="text-lg font-bold text-gray-800 mb-4">📍 Pilih Lokasi & Waktu</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Provinsi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                    <select name="provinsi" x-model="provinsi" @change="updateKabKota()"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                        @foreach($provinsiList as $prov)
                        <option value="{{ $prov }}" {{ $selectedProvinsi === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kab/Kota --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten / Kota</label>
                    <select name="kabkota"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none bg-white"
                            :disabled="loadingKabKota">
                        <template x-for="kota in kabKotaOptions" :key="kota">
                            <option :value="kota" :selected="kota === '{{ addslashes($selectedKabKota) }}'">
                                <span x-text="kota"></span>
                            </option>
                        </template>
                        <template x-if="kabKotaOptions.length === 0">
                            <option value="">{{ $selectedKabKota ?: 'Pilih provinsi dahulu' }}</option>
                        </template>
                    </select>
                </div>

                {{-- Bulan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                        @foreach($namaBulanList as $num => $nama)
                        <option value="{{ $num }}" {{ $selectedBulan === $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tahun --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                        @foreach(range(2026, 2028) as $yr)
                        <option value="{{ $yr }}" {{ $selectedTahun === $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="px-8 py-2.5 bg-emerald-700 text-white rounded-xl hover:bg-emerald-600 transition font-semibold">
                    Lihat Jadwal
                </button>
            </div>
        </form>

        {{-- Error Message --}}
        @if($errorMessage)
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-sm">
            ⚠️ {{ $errorMessage }}
        </div>
        @endif

        @if($jadwalData && !empty($jadwalData['jadwal']))
        @php
            $jadwal = $jadwalData['jadwal'];
            $today = \Carbon\Carbon::now()->format('Y-m-d');
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Jadwal Hari Ini --}}
            <div class="lg:col-span-1">
                @if($todayJadwal)
                <div class="bg-emerald-800 text-white rounded-2xl overflow-hidden shadow-lg mb-6">
                    <div class="px-6 py-5 border-b border-emerald-700">
                        <p class="text-emerald-300 text-sm font-medium">Jadwal Hari Ini</p>
                        <h2 class="text-xl font-bold">
                            {{ $todayJadwal['hari'] ?? '' }}, {{ $todayJadwal['tanggal'] ?? '' }}
                            {{ $namaBulanList[$selectedBulan] }} {{ $selectedTahun }}
                        </h2>
                        <p class="text-emerald-300 text-sm mt-1">{{ $selectedKabKota }}, {{ $selectedProvinsi }}</p>
                    </div>
                    <div class="px-6 py-5 space-y-2.5">
                        @php
                            $waktuList = [
                                ['label' => 'Imsak',   'key' => 'imsak',   'icon' => ''],
                                ['label' => 'Subuh',   'key' => 'subuh',   'icon' => ''],
                                ['label' => 'Terbit',  'key' => 'terbit',  'icon' => ''],
                                ['label' => 'Dhuha',   'key' => 'dhuha',   'icon' => ''],
                                ['label' => 'Dzuhur',  'key' => 'dzuhur',  'icon' => ''],
                                ['label' => 'Ashar',   'key' => 'ashar',   'icon' => ''],
                                ['label' => 'Maghrib', 'key' => 'maghrib', 'icon' => ''],
                                ['label' => 'Isya',    'key' => 'isya',    'icon' => ''],
                            ];
                        @endphp
                        @foreach($waktuList as $w)
                        <div class="flex justify-between items-center py-2 border-b border-emerald-700 last:border-0">
                            <span class="text-emerald-200 text-sm">{{ $w['icon'] }} {{ $w['label'] }}</span>
                            <span class="font-bold text-xl tabular-nums">{{ $todayJadwal[$w['key']] ?? '-' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Info --}}
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-sm text-emerald-800">
                    <p class="font-semibold mb-1">📌 Info</p>
                    <p>Data jadwal sholat dari <strong>Kementerian Agama RI</strong> via eQuran.id. Hanya tahun 2026 yang tersedia.</p>
                </div>
            </div>

            {{-- Tabel Bulanan --}}
            <div class="lg:col-span-2">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    Jadwal Sholat Bulan {{ $namaBulanList[$selectedBulan] }} {{ $selectedTahun }}
                    — {{ $selectedKabKota }}
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-emerald-700 text-white">
                                <th class="px-3 py-2.5 text-left rounded-tl-xl">Tgl</th>
                                <th class="px-3 py-2.5 text-left">Hari</th>
                                <th class="px-3 py-2.5 text-center">Imsak</th>
                                <th class="px-3 py-2.5 text-center">Subuh</th>
                                <th class="px-3 py-2.5 text-center">Dzuhur</th>
                                <th class="px-3 py-2.5 text-center">Ashar</th>
                                <th class="px-3 py-2.5 text-center">Maghrib</th>
                                <th class="px-3 py-2.5 text-center rounded-tr-xl">Isya</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($jadwal as $row)
                            @php
                                $isToday = ($row['tanggal_lengkap'] ?? '') === $today;
                            @endphp
                            <tr class="{{ $isToday ? 'bg-emerald-50 font-semibold ring-1 ring-emerald-400' : 'bg-white hover:bg-gray-50' }} transition">
                                <td class="px-3 py-2 text-gray-600 {{ $isToday ? 'text-emerald-700' : '' }}">
                                    {{ $row['tanggal'] ?? '' }}
                                    @if($isToday) <span class="text-xs text-emerald-600 ml-1">★</span> @endif
                                </td>
                                <td class="px-3 py-2 text-gray-600">{{ $row['hari'] ?? '' }}</td>
                                <td class="px-3 py-2 text-center tabular-nums">{{ $row['imsak'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-center tabular-nums">{{ $row['subuh'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-center tabular-nums">{{ $row['dzuhur'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-center tabular-nums">{{ $row['ashar'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-center tabular-nums">{{ $row['maghrib'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-center tabular-nums">{{ $row['isya'] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        @elseif(!$errorMessage)
        <div class="text-center py-16 text-gray-500">
            <p class="text-5xl mb-4">🕌</p>
            <p class="text-lg font-medium">Silakan pilih lokasi dan bulan untuk melihat jadwal sholat.</p>
        </div>
        @endif

    </div>

</x-islami-layout>
