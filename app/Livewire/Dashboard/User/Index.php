<?php

namespace App\Livewire\Dashboard\User;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $name = '';
    public $email = '';
    public $nim = '';
    public $password = '';

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function render()
    {
        return view('livewire.dashboard.user.index', [
            'users' => $this->getUsers(),
        ])->layout('layouts.app');
    }

    public function getUsers()
    {
        return User::when($this->search, function ($q) {
            $q->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('nim', 'like', '%' . $this->search . '%');
            });
        })->get();
    }

    public function data()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'nim' => $this->nim,
            'password' => $this->password,
        ];
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'nim' => 'required|string|max:20|unique:users,nim',
        'password' => 'required|string|min:8',
    ];

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->nim = '';
        $this->password = '';
    }

    public function submit()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'nim' => $this->nim,
            'password' => bcrypt($this->password),
        ]);

       $this->reset(['name', 'email', 'nim', 'password']); // ✅ Reset field aja
        $this->dispatch('close-modal');
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'User berhasil dibuat!'
        ]);
    }




}
