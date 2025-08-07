<?php

namespace App\Livewire\User\Event;

use App\Models\QrCode;
use Livewire\Component;
use Illuminate\Http\Request;

class Scan extends Component
{
    public $eventId;
    public $token;

    public function mount($eventId)
    {
       
        $this->eventId = $eventId;
    }

    public function render()
    {
        // dd($this->token);
        return view('livewire.user.event.scan')->layout('layouts.app', [
            'page' => 'Scan Event',
            'sidebar' => 'Event',
        ]);
    }
}
