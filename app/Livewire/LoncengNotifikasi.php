<?php

namespace App\Livewire;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoncengNotifikasi extends Component
{
    public $limit = 5; // Load awal 5 saja agar ringan
    public $selectedNotification = null; // <--- Untuk menampung data Modal

    // Menggunakan Computed Property agar reaktif
    public function getNotificationsProperty()
    {
        return Notifikasi::where('id_user', Auth::user()->id_user)
            ->orderBy('created_at', 'desc')
            ->take($this->limit)
            ->get();
    }

    public function getUnreadCountProperty()
    {
        return Notifikasi::where('id_user', Auth::user()->id_user)
            ->where('is_read', false)
            ->count();
    }

    public function getTotalCountProperty()
    {
        return Notifikasi::where('id_user', Auth::user()->id_user)->count();
    }

    public function loadMore()
    {
        $this->limit += 5; // Load 5 lagi saat discroll/klik
    }

    // Fungsi Baru: Buka Modal Detail
    public function openDetail($id)
    {
        $notif = Notifikasi::find($id);

        if ($notif && $notif->id_user === Auth::user()->id_user) {
            // 1. Simpan data ke variabel public agar bisa dibaca Modal
            $this->selectedNotification = $notif;

            // 2. Otomatis tandai sudah dibaca
            if (!$notif->is_read) {
                $notif->update(['is_read' => true]);
            }

            // 3. Perintahkan browser buka modal 'detail-notifikasi'
            $this->dispatch('open-modal', id: 'detail-notifikasi');
        }
    }

    public function markAsRead($id)
    {
        $notif = Notifikasi::find($id);
        if ($notif && $notif->id_user === Auth::user()->id_user) {
            $notif->update(['is_read' => true]);

            // Redirect jika ada ID SOP
            if ($notif->id_sop) {
                // Sesuaikan redirect sesuai role (Pengusul/Verifikator)
                // Ini contoh default, bisa disesuaikan nanti
                return redirect()->back();
            }
        }
    }

    public function markAllRead()
    {
        Notifikasi::where('id_user', Auth::user()->id_user)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function render()
    {
        return view('livewire.lonceng-notifikasi');
    }
}
