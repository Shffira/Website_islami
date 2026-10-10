<?php

namespace App\Http\Controllers;

use App\Services\EQuranService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected EQuranService $quranService
    ) {}

    public function index(): View
    {
        $surahList = $this->quranService->getSurahList();
        $doaList = $this->quranService->getDoaList();

        // Ambil beberapa surah favorit/populer
        $featuredSurahIds = [1, 18, 36, 55, 67]; // Al-Fatihah, Al-Kahf, Yasin, Ar-Rahman, Al-Mulk
        $featuredSurahs = collect($surahList)->filter(fn ($s) => in_array($s['nomor'] ?? 0, $featuredSurahIds))->values();

        // Ambil beberapa doa pilihan
        $featuredDoas = collect($doaList)->take(6);

        // Ambil jadwal shalat hari ini untuk Jakarta (default)
        $now = Carbon::now();
        $jadwalData = $this->quranService->getJadwalShalat('DKI Jakarta', 'Kota Jakarta', (int) $now->format('n'), (int) $now->format('Y'));
        $todayJadwal = null;
        if (! empty($jadwalData['jadwal'])) {
            $todayDate = $now->format('Y-m-d');
            foreach ($jadwalData['jadwal'] as $j) {
                if (($j['tanggal_lengkap'] ?? '') === $todayDate || (int) ($j['tanggal'] ?? 0) === (int) $now->format('j')) {
                    $todayJadwal = $j;
                    break;
                }
            }
            if (! $todayJadwal && count($jadwalData['jadwal']) > 0) {
                $todayJadwal = $jadwalData['jadwal'][0];
            }
        }

        return view('home', [
            'totalSurah' => count($surahList),
            'totalDoa' => count($doaList),
            'featuredSurahs' => $featuredSurahs,
            'featuredDoas' => $featuredDoas,
            'todayJadwal' => $todayJadwal,
            'kotaDefault' => 'Kota Jakarta',
            'provinsiDefault' => 'DKI Jakarta',
        ]);
    }
}
