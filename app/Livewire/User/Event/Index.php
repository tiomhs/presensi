<?php

namespace App\Livewire\User\Event;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.user.event.index')->layout('layouts.app', [
            'page' => 'User Events',
        ]);
    }
}
