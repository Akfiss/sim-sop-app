<?php

namespace App\Http\Controllers;

use App\Models\DokumenSop;
use App\Models\Direktorat;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Data Direktorat & Unit Kerja
        $direktorats = Direktorat::all();
        $units = \App\Models\UnitKerja::orderBy('nama_unit', 'asc')->get();

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

        if ($request->filled('unit_id')) {
            $query->where('id_unit_pemilik', $request->unit_id);
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
            'units' => $units,
        ]);
    }

    public function summarize($id)
    {
        // 1. Cari Dokumen
        $sop = DokumenSop::findOrFail($id);

        // 2. Validasi File
        if (!$sop->file_path || !file_exists(storage_path('app/public/' . $sop->file_path))) {
            return response()->json(['error' => 'File dokumen tidak ditemukan fisik di server.'], 404);
        }
        
        $path = storage_path('app/public/' . $sop->file_path);

        try {
            // 3. Baca Teks dari PDF
            $parser = new \Smalot\PdfParser\Parser();
            
            try {
                $pdf = $parser->parseFile($path);
                $text = $pdf->getText();
            } catch (\Exception $e) {
                Log::error("PDF Parse Error: " . $e->getMessage());
                return response()->json(['error' => 'Gagal membaca isi file PDF. Pastikan file tidak rusak/terpassword.'], 422);
            }

            // Bersihkan teks sedikit (menghapus tab/spasi berlebih)
            $text = preg_replace('/\s+/', ' ', trim($text));

            if (strlen($text) < 50) {
                 return response()->json(['error' => 'Teks tidak terbaca. Dokumen ini mungkin hasil scan/gambar yang tidak bisa diproses.'], 422);
            }

            // Batasi karakter agar tidak over token limit (Gemini 1.5 Flash up to 1M tokens, tapi kita keep safe & fast di 30k chars)
            $text = substr($text, 0, 30000);

            // 4. Persiapan API Key
            $apiKey = env('GOOGLE_AI_API_KEY');

            if (empty($apiKey)) {
                Log::error('Gemini Error: API Key belum disetting di .env');
                return response()->json(['error' => 'Konfigurasi Server: API Key belum dipasang.'], 500);
            }

            // 5. Kirim Request ke Google Gemini API (User Request: Gemini 2.5 Flash)
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";
            
            // Prompt Engineering yang lebih tegas
            $prompt =   "Kamu adalah asisten AI profesional untuk lingkungan Rumah Sakit.\n" .
                        "Tugas utama kamu adalah membuat RINGKASAN EKSEKUTIF dari dokumen SOP berikut.\n\n" .
                        "KETENTUAN WAJIB:\n" .
                        "1. Ringkasan HARUS mencakup poin-poin penting dari isi SOP secara menyeluruh.\n" .
                        "2. Ringkasan HARUS terdiri dari minimal 7 (tujuh) kalimat informatif.\n" .
                        "3. Setiap kalimat harus mencerminkan aspek penting SOP seperti: tujuan, ruang lingkup, kewajiban, larangan, prosedur utama, jangka waktu, konsekuensi, dan pihak terkait (jika ada).\n" .
                        "4. Gunakan Bahasa Indonesia yang formal, jelas, dan sesuai standar dokumen resmi Rumah Sakit.\n\n" .

                        "FORMAT OUTPUT (SANGAT PENTING):\n" .
                        "- Output WAJIB berupa HTML list menggunakan tag <ul> dan <li>.\n" .
                        "- Jangan gunakan markdown, jangan gunakan ```html, jangan gunakan heading atau teks di luar <ul>.\n" .
                        "- Setiap <li> berisi satu kalimat ringkasan yang utuh dan bermakna.\n\n" .

                        "ISI DOKUMEN SOP:\n" . $text;


            // Timeout 30 detik agar tidak hanging
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.4, // Lebih rendah agar lebih deterministik/faktual
                    'maxOutputTokens' => 1000,
                ]
            ]);

            // 6. Cek Response
            if ($response->successful()) {
                $data = $response->json();
                
                // Ambil teks candidate pertama
                $summary = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

                if (empty($summary)) {
                     // Fallback jika diblokir safety settings
                     Log::warning('Gemini Response Empty (Safety?): ' . json_encode($data));
                     return response()->json(['error' => 'AI tidak dapat menghasilkan ringkasan (Safety Block).'], 422);
                }

                // Bersihkan Markdown formatting jika masih ada sisa
                $summary = str_replace(['```html', '```'], '', $summary);
                $summary = trim($summary);

                return response()->json(['summary' => $summary]);
            } else {
                // PENTING: Log error asli dari Google ke storage/logs/laravel.log
                Log::error('Gemini API Error Status: ' . $response->status());
                Log::error('Gemini API Error Body: ' . $response->body());

                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? 'Unknown Error';

                return response()->json(['error' => 'Gagal: ' . $response->status() . ' - ' . $errorMessage], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Summarize Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan internal server.'], 500);
        }
    }
}
