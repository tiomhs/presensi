<?php

namespace App\Livewire\Dashboard\User;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    use \Livewire\WithPagination;

    public $search = '';
    public $name;
    public $email;
    public $nim;
    public $password;

    public $perPage = 10; // Default pagination
    public $isEdit = false;
    public $userId;
    public $confirmingDeleteId = null;
        
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function updatingPerPage()
    {
        $this->resetPage(); // reset ke halaman 1 tiap kali jumlah per page diubah
    }

    public function render()
    {
        return view('livewire.dashboard.user.index', [
            'users' => $this->getUsers(),
        ])->layout('layouts.app', [
            'page' => 'User Management',
        ]);
    }

    public function getUsers()
    {
        return User::when($this->search, function ($q) {
            $q->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('nim', 'like', '%' . $this->search . '%');
            });
        })->paginate($this->perPage);
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
        $this->reset(['name', 'email', 'nim', 'password']);
        $this->name = '';
        $this->email = '';
        $this->nim = '';
        $this->password = '';
        $this->isEdit = false;
    }

    public function create()
    {
        $this->resetForm(); // Reset form sebelum membuka modal
        $this->dispatch('open-modal'); // Tampilkan modal
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

    //    $this->reset(['name', 'email', 'nim', 'password']); // ✅ Reset field aja
        $this->dispatch('close-modal');
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'User berhasil ditambahkan!'
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nim = $user->nim;
        $this->password = ''; // Password tidak diisi saat edit
        $this->isEdit = true;

        $this->dispatch('open-modal');
    }

    public function update()
    {
        // $this->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     'nim' => 'required|string|max:20|unique:users',
        //     'password' => 'nullable|string|min:8', // Password boleh kosong saat update
        // ]);

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'nim' => $this->nim,
            'password' => $this->password ? bcrypt($this->password) : $user->password, // Update password only if provided
        ]);

        $this->dispatch('close-modal');
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'User berhasil diperbarui!'
        ]);
    }

     public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function delete()
    {
        $user = \App\Models\User::findOrFail($this->confirmingDeleteId);
        $user->delete();
        $this->confirmingDeleteId = null;
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'User berhasil dihapus!'
        ]);
    }




}
