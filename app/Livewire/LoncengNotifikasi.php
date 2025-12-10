<?php

namespace App\Livewire;

use App\Models\Notifikasi;
use App\Models\DokumenSop;
use App\Filament\Pengusul\Resources\DokumenSopResource;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Infolist;

class LoncengNotifikasi extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $limit = 5;
    public $selectedNotification = null;

    // ... (kode getNotificationsProperty, count, loadMore biarkan sama) ...

    public function getNotificationsProperty()
    {
        return Notifikasi::where('id_user', Auth::user()->id_user)
            ->orderBy('created_at', 'desc')
            ->take($this->limit)
            ->get();
    }
    
    public function getUnreadCountProperty()
    {
        return Notifikasi::where('id_user', Auth::user()->id_user)->where('is_read', false)->count();
    }

    public function getTotalCountProperty()
    {
        return Notifikasi::where('id_user', Auth::user()->id_user)->count();
    }
    
    public function loadMore()
    {
        $this->limit += 5;
    }

    public function openDetail($id)
    {
        $notif = Notifikasi::find($id);
        if ($notif && $notif->id_user === Auth::user()->id_user) {
            $this->selectedNotification = $notif;
            
            if (!$notif->is_read) {
                $notif->update(['is_read' => true]);
            }
            $this->dispatch('open-modal', id: 'detail-notifikasi');
        }
    }

    public function markAllRead()
    {
        Notifikasi::where('id_user', Auth::user()->id_user)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    // --- WAJIB DITAMBAHKAN: ACTION UNTUK TOMBOL 'LIHAT DOKUMEN' ---
    public function viewSopAction(): Action
    {
        return Action::make('viewSop')
            ->label('Lihat Dokumen SOP')
            ->button() // Tampil sebagai tombol
            ->color('primary')
            ->modalHeading('Detail Dokumen SOP')
            ->modalWidth('4xl')
            ->modalSubmitAction(false) 
            ->modalCancelActionLabel('Tutup')
            ->record(fn (array $arguments) => DokumenSop::find($arguments['dokumen_id']))
            ->infolist(fn ($record) => 
                Infolist::make()
                    ->record($record)
                    ->schema(DokumenSopResource::getInfolistSchema()) 
            );
    }

    public function render()
    {
        return view('livewire.lonceng-notifikasi');
    }
}