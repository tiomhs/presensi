<?php

namespace App\Livewire\User\Event;

use App\Models\EventCommittee;
use Livewire\Component;

class Index extends Component
{
    public $userId = null;

    public function mount()
    {
        // Initialize the userId if needed, for example, from the authenticated user
        $this->userId = auth()->id();
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
            ->get();
    }
}
