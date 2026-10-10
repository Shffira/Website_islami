<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EQuranService
{
    protected string $baseUrlV2 = 'https://equran.id/api/v2';

    protected string $baseUrlDoa = 'https://equran.id/api';

    /**
     * Mengambil daftar 114 surat Al-Qur'an.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSurahList(): array
    {
        return Cache::remember('equran_surah_list', 86400, function () {
            try {
                $response = Http::connectTimeout(5)
                    ->timeout(15)
                    ->get("{$this->baseUrlV2}/surat");

                if ($response->successful()) {
                    $json = $response->json();

                    return $json['data'] ?? [];
                }

                Log::warning('Gagal mengambil daftar surat: status '.$response->status());

                return [];
            } catch (Exception $e) {
                Log::error('Exception saat request daftar surat: '.$e->getMessage());

                return [];
            }
        });
    }

    /**
     * Mengambil detail surat beserta daftar ayat dan audio per-ayat.
     *
     * @return array<string, mixed>|null
     */
    public function getSurahDetail(int $nomor): ?array
    {
        return Cache::remember("equran_surah_{$nomor}", 86400, function () use ($nomor) {
            try {
                $response = Http::connectTimeout(5)
                    ->timeout(15)
                    ->get("{$this->baseUrlV2}/surat/{$nomor}");

                if ($response->successful()) {
                    $json = $response->json();

                    return $json['data'] ?? null;
                }

                return null;
            } catch (Exception $e) {
                Log::error("Exception saat request surat {$nomor}: ".$e->getMessage());

                return null;
            }
        });
    }

    /**
     * Mengambil tafsir surat per-ayat dari Kemenag RI.
     *
     * @return array<string, mixed>|null
     */
    public function getTafsir(int $nomor): ?array
    {
        return Cache::remember("equran_tafsir_{$nomor}", 86400, function () use ($nomor) {
            try {
                $response = Http::connectTimeout(5)
                    ->timeout(15)
                    ->get("{$this->baseUrlV2}/tafsir/{$nomor}");

                if ($response->successful()) {
                    $json = $response->json();

                    return $json['data'] ?? null;
                }

                return null;
            } catch (Exception $e) {
                Log::error("Exception saat request tafsir {$nomor}: ".$e->getMessage());

                return null;
            }
        });
    }

    /**
     * Mengambil seluruh daftar doa harian.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getDoaList(): array
    {
        return Cache::remember('equran_doa_list', 86400, function () {
            try {
                $response = Http::connectTimeout(5)
                    ->timeout(15)
                    ->get("{$this->baseUrlDoa}/doa");

                if ($response->successful()) {
                    $json = $response->json();

                    return $json['data'] ?? [];
                }

                Log::warning('Gagal mengambil daftar doa: status '.$response->status());

                return [];
            } catch (Exception $e) {
                Log::error('Exception saat request daftar doa: '.$e->getMessage());

                return [];
            }
        });
    }

    /**
     * Mengambil detail satu doa harian berdasarkan ID.
     *
     * @return array<string, mixed>|null
     */
    public function getDoaDetail(int $id): ?array
    {
        return Cache::remember("equran_doa_{$id}", 86400, function () use ($id) {
            try {
                $response = Http::connectTimeout(5)
                    ->timeout(15)
                    ->get("{$this->baseUrlDoa}/doa/{$id}");

                if ($response->successful()) {
                    $json = $response->json();

                    return $json['data'] ?? null;
                }

                return null;
            } catch (Exception $e) {
                Log::error("Exception saat request doa {$id}: ".$e->getMessage());

                return null;
            }
        });
    }

    /**
     * Mengambil daftar provinsi untuk jadwal shalat.
     *
     * @return array<int, string>
     */
    public function getProvinsiList(): array
    {
        return Cache::remember('equran_shalat_provinsi', 86400, function () {
            try {
                $response = Http::connectTimeout(5)
                    ->timeout(15)
                    ->get("{$this->baseUrlV2}/shalat/provinsi");

                if ($response->successful()) {
                    $json = $response->json();

                    return $json['data'] ?? [];
                }

                return [];
            } catch (Exception $e) {
                Log::error('Exception saat request provinsi shalat: '.$e->getMessage());

                return [];
            }
        });
    }

    /**
     * Mengambil daftar kabupaten/kota berdasarkan nama provinsi.
     *
     * @return array<int, string>
     */
    public function getKabKotaList(string $provinsi): array
    {
        $cacheKey = 'equran_shalat_kabkota_'.md5($provinsi);

        return Cache::remember($cacheKey, 86400, function () use ($provinsi) {
            try {
                $response = Http::connectTimeout(5)
                    ->timeout(15)
                    ->post("{$this->baseUrlV2}/shalat/kabkota", [
                        'provinsi' => $provinsi,
                    ]);

                if ($response->successful()) {
                    $json = $response->json();

                    return $json['data'] ?? [];
                }

                return [];
            } catch (Exception $e) {
                Log::error("Exception saat request kabkota ({$provinsi}): ".$e->getMessage());

                return [];
            }
        });
    }

    /**
     * Mengambil jadwal shalat bulanan berdasarkan provinsi, kab/kota, bulan, dan tahun.
     *
     * @return array<string, mixed>|null
     */
    public function getJadwalShalat(string $provinsi, string $kabkota, int $bulan, int $tahun): ?array
    {
        $cacheKey = 'equran_shalat_jadwal_'.md5("{$provinsi}_{$kabkota}_{$bulan}_{$tahun}");

        return Cache::remember($cacheKey, 86400, function () use ($provinsi, $kabkota, $bulan, $tahun) {
            try {
                $response = Http::connectTimeout(5)
                    ->timeout(15)
                    ->post("{$this->baseUrlV2}/shalat", [
                        'provinsi' => $provinsi,
                        'kabkota' => $kabkota,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ]);

                if ($response->successful()) {
                    $json = $response->json();

                    return $json['data'] ?? null;
                }

                return null;
            } catch (Exception $e) {
                Log::error("Exception saat request jadwal shalat ({$provinsi}, {$kabkota}): ".$e->getMessage());

                return null;
            }
        });
    }
}
