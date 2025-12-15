<?php

namespace App\Filament\Verifikator\Resources\DokumenSopResource\Pages;

use App\Filament\Verifikator\Resources\DokumenSopResource;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;

class CreateDokumenSop extends CreateRecord
{
    protected static string $resource = DokumenSopResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        if ($user) {
            $data['created_by'] = $user->id_user;
        }

        // Ambil data tanggal penting
        $tglBerlaku = isset($data['tgl_berlaku']) ? Carbon::parse($data['tgl_berlaku']) : null;
        $tglKadaluarsa = isset($data['tgl_kadaluarsa']) ? Carbon::parse($data['tgl_kadaluarsa']) : null;
        $today = now();

        // 1. CEK STATUS KADALUARSA (Basis Utama)
        if ($tglKadaluarsa && $tglKadaluarsa->isPast()) {
            $data['status'] = 'KADALUARSA';
            $data['tgl_review_berikutnya'] = null;
            return $data; // Stop di sini
        } else {
            $data['status'] = 'AKTIF';
        }

        // 2. LOGIKA "FAST FORWARD" TANGGAL REVIEW
        // Jika dokumen backdated (misal berlaku 2022), kita cari jadwal review yang relevan untuk SAAT INI.
        if ($tglBerlaku && $tglKadaluarsa) {
            // Start hitungan review pertama (+1 tahun dari berlaku)
            $nextReview = $tglBerlaku->copy()->addYear();

            // LOOP: Selama tanggal review masih di MASA LALU (sudah lewat hari ini)
            // Tambahkan terus 1 tahun
            while ($nextReview->isPast() && $nextReview->lessThan($tglKadaluarsa)) {
                $nextReview->addYear();
            }

            // FINAL CHECK:
            // Jika tanggal review hasil hitungan ternyata >= Tanggal Expired
            // Maka Review Date harus KOSONG (Tahun terakhir tidak ada review)
            if ($nextReview->greaterThanOrEqualTo($tglKadaluarsa)) {
                $data['tgl_review_berikutnya'] = null;
            } else {
                // Jika valid (Masa Depan & Sebelum Expired), simpan tanggalnya
                $data['tgl_review_berikutnya'] = $nextReview->format('Y-m-d');
            }
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Siapkan variabel untuk menampung Judul & Pesan Notifikasi
        $judulNotif = null;
        $pesanNotif = null;
        $tipeIcon = 'heroicon-o-document';

        // SKENARIO 1: Dokumen Terbit (AKTIF)
        if ($record->status === 'AKTIF') {
            $judulNotif = 'Dokumen SOP Baru Terbit';
            $pesanNotif = "SOP '{$record->judul_sop}' milik unit Anda telah diterbitkan dan Aktif.";
            $tipeIcon   = 'heroicon-o-check-circle';
        }
        // SKENARIO 2: Dokumen Langsung Kadaluarsa (Backdate)
        elseif ($record->status === 'KADALUARSA') {
            $judulNotif = 'Dokumen SOP Kadaluarsa';
            $pesanNotif = "PERHATIAN: SOP '{$record->judul_sop}' diupload dengan status KADALUARSA (Expired). Harap segera ajukan pembaruan.";
            $tipeIcon   = 'heroicon-o-exclamation-triangle';
        }

        // EKSEKUSI PENGIRIMAN NOTIFIKASI
        if ($judulNotif) {
            // 1. Ambil ID User dari tabel pivot 'tb_unit_user'
            $userIds = DB::table('tb_unit_user')
                ->where('id_unit', $record->id_unit_pemilik)
                ->pluck('id_user');

            // 2. Ambil data User
            $penerima = User::whereIn('id_user', $userIds)->get();

            // 3. Loop kirim notifikasi
            foreach ($penerima as $user) {
                Notifikasi::create([
                    'id_user'   => $user->id_user,
                    'judul'     => $judulNotif,
                    'pesan'     => $pesanNotif,
                    'is_read'   => false,
                    'id_sop'    => $record->id_sop,
                ]);
            }
        }
    }
}
