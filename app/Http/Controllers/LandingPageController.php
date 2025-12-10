<?php

namespace App\Http\Controllers;

use App\Models\DokumenSop;
use App\Models\Direktorat;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Data Direktorat
        $direktorats = Direktorat::all();

        // 2. Query Utama SOP
        $query = DokumenSop::query()
            ->with(['unitTerkait', 'unitPemilik.direktorat'])
            ->where('status', 'AKTIF');

        // 3. Search Logic
        if ($request->filled('search')) {
            $query->where('judul_sop', 'like', '%' . $request->search . '%');
        }

        // 4. Filter Logic
        if ($request->filled('direktorat_id')) {
            $query->whereHas('unitPemilik', function (Builder $q) use ($request) {
                $q->where('id_direktorat', $request->direktorat_id);
            });
        }

        // 5. Pagination dengan Fragment (Agar tidak scroll ke atas)
        // Urutkan berdasarkan tanggal pengesahan (saat SOP disetujui & menjadi aktif)
        $sop_list = $query->orderBy('tgl_pengesahan', 'desc')
            ->paginate(6)
            ->withQueryString()
            ->fragment('dokumen'); // <--- INI KUNCI AGAR LAYAR TETAP DI LIST

        return view('landing-page', [
            'sop_list' => $sop_list,
            'direktorats' => $direktorats,
        ]);
    }
}
