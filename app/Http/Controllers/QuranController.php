<?php

namespace App\Http\Controllers;

use App\Services\EQuranService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class QuranController extends Controller
{
    /**
     * Daftar qari yang didukung oleh API eQuran.id.
     *
     * @var array<string, string>
     */
    public const QARI_LIST = [
        '01' => 'Abdullah Al-Juhany',
        '02' => 'Abdul-Muhsin Al-Qasim',
        '03' => 'Abdurrahman as-Sudais',
        '04' => 'Ibrahim Al-Dossari',
        '05' => 'Misyari Rasyid Al-Afasi',
        '06' => 'Yasser Al-Dosari',
    ];

    public function __construct(
        protected EQuranService $quranService
    ) {}

    /**
     * Menampilkan daftar 114 surat Al-Qur'an dengan pencarian & filter.
     */
    public function index(Request $request): View
    {
        $surahList = $this->quranService->getSurahList();
        $query = trim((string) $request->input('q', ''));
        $tempat = trim((string) $request->input('tempat', ''));

        $filteredSurahs = collect($surahList);

        if ($query !== '') {
            $filteredSurahs = $filteredSurahs->filter(function ($surah) use ($query) {
                $q = strtolower($query);
                $nomor = (string) ($surah['nomor'] ?? '');
                $namaLatin = strtolower($surah['namaLatin'] ?? '');
                $arti = strtolower($surah['arti'] ?? '');
                $nama = $surah['nama'] ?? '';

                return str_contains($namaLatin, $q)
                    || str_contains($arti, $q)
                    || str_contains($nomor, $q)
                    || str_contains($nama, $q);
            });
        }

        if ($tempat !== '') {
            $filteredSurahs = $filteredSurahs->filter(function ($surah) use ($tempat) {
                return strcasecmp($surah['tempatTurun'] ?? '', $tempat) === 0;
            });
        }

        return view('quran.index', [
            'surahs' => $filteredSurahs->values(),
            'totalSurahs' => count($surahList),
            'query' => $query,
            'tempat' => $tempat,
            'apiError' => empty($surahList),
        ]);
    }

    /**
     * Menampilkan detail surat lengkap dengan ayat, audio, dan tafsir.
     */
    public function show(int $nomor): View
    {
        if ($nomor < 1 || $nomor > 114) {
            abort(404, 'Surat tidak ditemukan.');
        }

        $surah = $this->quranService->getSurahDetail($nomor);

        if (! $surah) {
            abort(404, "Data surat ke-{$nomor} tidak dapat dimuat.");
        }

        // Ambil tafsir untuk surat ini
        $tafsirData = $this->quranService->getTafsir($nomor);
        $tafsirMap = [];
        if (! empty($tafsirData['tafsir'])) {
            foreach ($tafsirData['tafsir'] as $t) {
                $tafsirMap[$t['ayat']] = $t['teks'];
            }
        }

        return view('quran.show', [
            'surah' => $surah,
            'tafsirMap' => $tafsirMap,
            'qariList' => self::QARI_LIST,
        ]);
    }

    /**
     * Menampilkan halaman tafsir lengkap surat.
     */
    public function tafsir(int $nomor): View
    {
        if ($nomor < 1 || $nomor > 114) {
            abort(404, 'Surat tidak ditemukan.');
        }

        $surah = $this->quranService->getSurahDetail($nomor);
        $tafsir = $this->quranService->getTafsir($nomor);

        if (! $surah || ! $tafsir) {
            abort(404, "Data tafsir surat ke-{$nomor} tidak ditemukan.");
        }

        return view('quran.tafsir', [
            'surah' => $surah,
            'tafsir' => $tafsir,
        ]);
    }
}
