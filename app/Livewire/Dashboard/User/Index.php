<?php

namespace App\Livewire\Dashboard\User;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $users;

    public function mount()
    {
        $this->users = User::all();
    }
    public function render()
    {
        return view('livewire.dashboard.user.index', [
            'users' => $this->users
        ])->layout('layouts.app');
    }
}
