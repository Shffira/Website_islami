<?php

namespace App\Http\Controllers;

use App\Services\EQuranService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShalatController extends Controller
{
    public function __construct(
        protected EQuranService $quranService
    ) {}

    /**
     * Menampilkan halaman jadwal shalat bulanan dan widget jadwal hari ini.
     */
    public function index(Request $request): View
    {
        $provinsiList = $this->quranService->getProvinsiList();

        $selectedProvinsi = $request->input('provinsi');
        if (! $selectedProvinsi || ! in_array($selectedProvinsi, $provinsiList)) {
            $selectedProvinsi = in_array('DKI Jakarta', $provinsiList) ? 'DKI Jakarta' : ($provinsiList[0] ?? 'DKI Jakarta');
        }

        // Ambil daftar kab/kota untuk provinsi terpilih
        $kabKotaList = $this->quranService->getKabKotaList($selectedProvinsi);

        $selectedKabKota = $request->input('kabkota');
        if (! $selectedKabKota || ! in_array($selectedKabKota, $kabKotaList)) {
            $selectedKabKota = in_array('Kota Jakarta', $kabKotaList) ? 'Kota Jakarta' : ($kabKotaList[0] ?? '');
        }

        $now = Carbon::now();
        $selectedBulan = (int) $request->input('bulan', $now->format('n'));
        $selectedTahun = (int) $request->input('tahun', $now->format('Y'));

        // Batasi rentang bulan 1-12 dan tahun
        if ($selectedBulan < 1 || $selectedBulan > 12) {
            $selectedBulan = (int) $now->format('n');
        }

        $jadwalData = null;
        $todayJadwal = null;
        $errorMessage = null;

        if ($selectedProvinsi && $selectedKabKota) {
            $jadwalData = $this->quranService->getJadwalShalat(
                $selectedProvinsi,
                $selectedKabKota,
                $selectedBulan,
                $selectedTahun
            );

            if (! $jadwalData || empty($jadwalData['jadwal'])) {
                $errorMessage = "Jadwal shalat untuk {$selectedKabKota}, {$selectedProvinsi} bulan {$this->getNamaBulan($selectedBulan)} {$selectedTahun} tidak ditemukan atau belum tersedia dari server Kemenag.";
            } else {
                $todayDate = $now->format('Y-m-d');
                foreach ($jadwalData['jadwal'] as $item) {
                    if (($item['tanggal_lengkap'] ?? '') === $todayDate || (int) ($item['tanggal'] ?? 0) === (int) $now->format('j')) {
                        $todayJadwal = $item;
                        break;
                    }
                }
                if (! $todayJadwal && count($jadwalData['jadwal']) > 0) {
                    $todayJadwal = $jadwalData['jadwal'][0];
                }
            }
        }

        return view('shalat.index', [
            'provinsiList' => $provinsiList,
            'kabKotaList' => $kabKotaList,
            'selectedProvinsi' => $selectedProvinsi,
            'selectedKabKota' => $selectedKabKota,
            'selectedBulan' => $selectedBulan,
            'selectedTahun' => $selectedTahun,
            'jadwalData' => $jadwalData,
            'todayJadwal' => $todayJadwal,
            'errorMessage' => $errorMessage,
            'namaBulanList' => $this->getDaftarBulan(),
        ]);
    }

    /**
     * Endpoint API JSON untuk mengambil daftar kabupaten/kota saat provinsi berubah di UI.
     */
    public function getKabKota(Request $request): JsonResponse
    {
        $provinsi = trim((string) $request->input('provinsi', ''));

        if ($provinsi === '') {
            return response()->json(['data' => []]);
        }

        $kabKotaList = $this->quranService->getKabKotaList($provinsi);

        return response()->json([
            'status' => 'success',
            'data' => $kabKotaList,
        ]);
    }

    /**
     * Daftar nama bulan dalam bahasa Indonesia.
     *
     * @return array<int, string>
     */
    protected function getDaftarBulan(): array
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }

    protected function getNamaBulan(int $bulan): string
    {
        $bulanList = $this->getDaftarBulan();

        return $bulanList[$bulan] ?? (string) $bulan;
    }
}
