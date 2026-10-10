<?php

namespace App\Http\Controllers;

use App\Services\EQuranService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DoaController extends Controller
{
    public function __construct(
        protected EQuranService $quranService
    ) {}

    /**
     * Menampilkan daftar doa harian dengan fitur pencarian, filter grup, dan filter tag.
     */
    public function index(Request $request): View
    {
        $allDoas = $this->quranService->getDoaList();
        $query = trim((string) $request->input('q', ''));
        $selectedGrup = trim((string) $request->input('grup', ''));
        $selectedTag = trim((string) $request->input('tag', ''));

        // Ambil daftar grup unik
        $groups = collect($allDoas)
            ->pluck('grup')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Ambil kumpulan tag unik
        $tags = collect($allDoas)
            ->pluck('tag')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $filtered = collect($allDoas);

        if ($query !== '') {
            $filtered = $filtered->filter(function ($item) use ($query) {
                $q = strtolower($query);
                $nama = strtolower($item['nama'] ?? '');
                $idn = strtolower($item['idn'] ?? '');
                $tr = strtolower($item['tr'] ?? '');
                $grup = strtolower($item['grup'] ?? '');

                return str_contains($nama, $q)
                    || str_contains($idn, $q)
                    || str_contains($tr, $q)
                    || str_contains($grup, $q);
            });
        }

        if ($selectedGrup !== '') {
            $filtered = $filtered->filter(function ($item) use ($selectedGrup) {
                return ($item['grup'] ?? '') === $selectedGrup;
            });
        }

        if ($selectedTag !== '') {
            $filtered = $filtered->filter(function ($item) use ($selectedTag) {
                $itemTags = $item['tag'] ?? [];

                return is_array($itemTags) && in_array($selectedTag, $itemTags);
            });
        }

        $page = (int) $request->input('page', 1);
        $perPage = 18;
        $paginated = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => route('doa.index'), 'query' => $request->query()]
        );

        return view('doa.index', [
            'doas' => $paginated,
            'groups' => $groups,
            'tags' => $tags,
            'query' => $query,
            'selectedGrup' => $selectedGrup,
            'selectedTag' => $selectedTag,
            'totalCount' => count($allDoas),
            'filteredCount' => $filtered->count(),
            'apiError' => empty($allDoas),
        ]);
    }

    /**
     * Menampilkan detail doa tertentu.
     */
    public function show(int $id): View
    {
        $doa = $this->quranService->getDoaDetail($id);

        if (! $doa) {
            abort(404, 'Doa tidak ditemukan.');
        }

        // Ambil doa terkait dalam grup yang sama
        $allDoas = $this->quranService->getDoaList();
        $relatedDoas = collect($allDoas)
            ->filter(fn ($item) => ($item['grup'] ?? '') === ($doa['grup'] ?? '') && ($item['id'] ?? 0) !== $id)
            ->take(4)
            ->values();

        return view('doa.show', [
            'doa' => $doa,
            'relatedDoas' => $relatedDoas,
            'prevId' => $id > 1 ? $id - 1 : null,
            'nextId' => $id < 227 ? $id + 1 : null,
        ]);
    }
}
