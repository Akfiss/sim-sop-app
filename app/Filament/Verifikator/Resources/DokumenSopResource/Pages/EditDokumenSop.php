<?php

namespace App\Filament\Verifikator\Resources\DokumenSopResource\Pages;

use App\Filament\Verifikator\Resources\DokumenSopResource;
use App\Models\RiwayatSop;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Notifikasi;
use Carbon\Carbon;

class EditDokumenSop extends EditRecord
{
    protected static string $resource = DokumenSopResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Hook yang berjalan setelah data berhasil di-update (Simpan)
     */
    protected function afterSave(): void
    {
        $record = $this->record;

        // Kita hanya kirim notifikasi edit JIKA statusnya menjadi KADALUARSA
        // (Agar tidak spam notifikasi setiap kali edit typo judul dll)
        if ($record->status === 'KADALUARSA') {

            // 1. Ambil ID User dari tabel pivot 'tb_unit_user'
            $userIds = DB::table('tb_unit_user')
                ->where('id_unit', $record->id_unit_pemilik)
                ->pluck('id_user');

            // 2. Ambil data User
            $penerima = User::whereIn('id_user', $userIds)->get();

            // 3. Kirim Notifikasi
            foreach ($penerima as $user) {
                Notifikasi::create([
                    'id_user'   => $user->id_user,
                    'judul'     => 'Status SOP Menjadi Kadaluarsa',
                    'pesan'     => "SOP '{$record->judul_sop}' kini berstatus KADALUARSA. Mohon segera tinjau dan lakukan pembaruan jika diperlukan.",
                    'is_read'   => false,
                    'id_sop'    => $record->id_sop,
                ]);
            }
        }
    }

    /**
     * LOGIKA BARU: Manipulasi data sebelum disimpan ke database.
     * Di sini kita mengatur logika Tanggal Review dan Status secara otomatis.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $tglKadaluarsa = isset($data['tgl_kadaluarsa']) ? Carbon::parse($data['tgl_kadaluarsa']) : null;
        $tglReviewLama = isset($data['tgl_review_berikutnya']) ? Carbon::parse($data['tgl_review_berikutnya']) : null;
        $today = now();

        // 1. CEK STATUS KADALUARSA
        if ($tglKadaluarsa && $tglKadaluarsa->isPast()) {
            $data['status'] = 'KADALUARSA';
            $data['tgl_review_berikutnya'] = null;
            return $data;
        }
        elseif ($this->record->status === 'KADALUARSA') {
            $data['status'] = 'AKTIF';
        }

        // 2. LOGIKA "FAST FORWARD" TANGGAL REVIEW (Sama seperti Create)
        // Kita hanya proses jika Tgl Review ada isinya (tidak null)
        if ($tglReviewLama && $tglKadaluarsa) {
            $nextReview = $tglReviewLama->copy();

            // Jika tanggal review yang tersimpan SUDAH LEWAT
            if ($nextReview->isPast()) {
                // LOOP: Majukan terus pertahun sampai ketemu tanggal masa depan atau mentok expired
                while ($nextReview->isPast() && $nextReview->lessThan($tglKadaluarsa)) {
                    $nextReview->addYear();
                }

                // FINAL CHECK:
                // Jika hasil hitungan >= Tanggal Expired -> HAPUS
                if ($nextReview->greaterThanOrEqualTo($tglKadaluarsa)) {
                    $data['tgl_review_berikutnya'] = null;
                } else {
                    // Jika valid, update tanggalnya
                    $data['tgl_review_berikutnya'] = $nextReview->format('Y-m-d');
                }
            }
        }

        return $data;
    }

    /**
     * Override fungsi update bawaan Filament untuk menyisipkan
     * logika pencatatan riwayat perubahan (Audit Log).
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Ambil data lama sebelum diupdate
        $originalData = $record->replicate();

        // LOGIC TAMBAHAN: Update status otomatis berdasarkan tanggal baru
        if (isset($data['tgl_kadaluarsa'])) {
            $expiredDate = Carbon::parse($data['tgl_kadaluarsa']);
            if ($expiredDate->isPast()) {
                $data['status'] = 'KADALUARSA';
            } else {
                // Kembalikan ke AKTIF hanya jika sebelumnya KADALUARSA
                // (agar tidak menimpa status ARCHIVED jika nanti ada)
                if ($record->status === 'KADALUARSA') {
                    $data['status'] = 'AKTIF';
                }
            }
        }

        // Update Record dengan data yang sudah diproses di mutateFormDataBeforeSave
        $record->update($data);

        // Catat Perubahan ke Riwayat
        $this->logChanges($originalData, $record);

        return $record;
    }

    /**
     * Fungsi custom untuk membandingkan field dan membuat kalimat log
     */
    protected function logChanges(Model $old, Model $new)
    {
        $changes = [];

        // Daftar field yang ingin dipantau perubahannya
        $fieldsToCheck = [
            'judul_sop' => 'Judul Dokumen',
            'nomor_sk' => 'Nomor SK',
            'kategori_sop' => 'Kategori',
            'tgl_pengesahan' => 'Tanggal Pengesahan',
            'tgl_berlaku' => 'Tanggal Berlaku',
            'tgl_kadaluarsa' => 'Tanggal Kadaluarsa',
            'tgl_review_berikutnya' => 'Jadwal Review',
            'file_path' => 'File Dokumen',
            'id_unit_pemilik' => 'Unit Pemilik',
        ];

        foreach ($fieldsToCheck as $field => $label) {
            $oldValue = $old->$field;
            $newValue = $new->$field;

            // Jika ada perbedaan nilai
            if ($oldValue != $newValue) {
                // Format khusus untuk Tanggal
                if (in_array($field, ['tgl_pengesahan', 'tgl_berlaku', 'tgl_kadaluarsa'])) {
                    $oldDisplay = $oldValue ? Carbon::parse($oldValue)->format('d/m/Y') : '-';
                    $newDisplay = $newValue ? Carbon::parse($newValue)->format('d/m/Y') : '-';
                    $changes[] = "$label diperbarui dari '$oldDisplay' menjadi '$newDisplay'.";
                }
                // Format khusus untuk File (karena nama filenya panjang/hash)
                elseif ($field === 'file_path') {
                    $changes[] = "File dokumen telah diperbarui dengan versi baru.";
                }
                // Format Standar
                else {
                    $changes[] = "$label diperbarui dari '$oldValue' menjadi '$newValue'.";
                }
            }
        }

        // Jika ada perubahan, simpan ke database Riwayat
        if (count($changes) > 0) {
            // Gabungkan semua perubahan menjadi satu paragraf
            $catatanLog = "Verifikator melakukan perubahan data: " . implode(" ", $changes);

            RiwayatSop::create([
                'id_sop' => $new->id_sop,
                'id_user' => Auth::user()->id_user,
                'status_sop' => 'AKTIF', // Status tetap aktif karena hanya edit
                'catatan' => $catatanLog,
                'dokumen_path' => $new->file_path, // Snapshot file terbaru
            ]);
        }
    }
}
