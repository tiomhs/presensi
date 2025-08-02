<?php

namespace App\Livewire\User\Event;

use App\Models\EventCommittee;
use App\Models\QrCode;
use Livewire\Component;

class Index extends Component
{
    public $userId = null;
    public $search = '';
    public $perPage = 10; // Default pagination
    public $isEdit = false;


    public function updatingPerPage()
    {
        $this->resetPage(); // reset to page 1 whenever per page count is changed
    }
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function mount()
    {
        $this->userId = auth()->id();

        // Ambil session flash
        $success = session('success');
        $error = session('error');

        // Hapus session setelah diambil
        session()->forget(['success', 'error']);

        // Tampilkan SweetAlert sesuai kondisi
        if ($success) {
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => $success ?? 'Berhasil Melakukan Absensi',
            ]);
        }

        if ($error) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => $error ?? 'Terjadi kesalahan. Silakan coba lagi.',
            ]);
        }
    }


    public function render()
    {
        // dd($this->getUserEvents()->event);
        return view('livewire.user.event.index',
            [
                'events' => $this->getUserEvents(),
            ]
        )->layout('layouts.app', [
            'page' => 'User Events',
        ]);
    }

    public function getUserEvents()
    {
        return EventCommittee::where('user_id', $this->userId)
            ->when($this->search, function ($query) {
                $query->whereHas('event', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->with('event')
            ->paginate($this->perPage);
    }

    public function absen($eventId)
    {
        $qr = QrCode::where('event_id', $eventId)
            ->first();
        
        if ($qr) {
            // Redirect to the QR code scan page with the event ID
            return redirect()->route('user.event.scan', ['eventId' => $eventId]);
        } else {
            // Handle the case where no QR code is found for the event
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'QR Code tidak ditemukan untuk acara ini. Silakan hubungi panitia acara.',
            ]);
            return redirect()->back();      
        }
    }
}
