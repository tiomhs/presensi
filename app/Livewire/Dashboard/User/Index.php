<?php

namespace App\Livewire\Dashboard\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public $name, $email, $nim, $password;
    public $userId;
    public $isEdit = false;
    public $confirmingDeleteId = null;

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function render()
    {
        return view('livewire.dashboard.user.index', [
            'users' => $this->getUsers(),
        ])->layout('layouts.app', [
            'page' => 'User Management',
            'sidebar' => 'User',
        ]);
    }

    private function getUsers()
    {
        return User::when($this->search, function ($q) {
            $q->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('nim', 'like', "%{$this->search}%");
            });
        })->paginate($this->perPage);
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->reset(['name', 'email', 'nim', 'password', 'isEdit', 'userId']);
    }

    private function getValidationRules($isEdit = false)
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($isEdit ? ',' . $this->userId : ''),
            'nim' => 'required|string|max:20|unique:users,nim' . ($isEdit ? ',' . $this->userId : ''),
            'password' => $isEdit ? 'nullable|string|min:8' : 'required|string|min:8',
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('open-modal');
    }

    public function store()
    {
        $this->validate($this->getValidationRules());

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'nim' => $this->nim,
            'password' => bcrypt($this->password),
        ]);

        $this->resetForm();
        $this->dispatch('close-modal');
        $this->alertSuccess('User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nim = $user->nim;
        $this->password = '';
        $this->isEdit = true;

        $this->dispatch('open-modal');
    }

    public function update()
    {
        $this->validate($this->getValidationRules(true));

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'nim' => $this->nim,
            'password' => $this->password ? bcrypt($this->password) : $user->password,
        ]);

        $this->resetForm();
        $this->dispatch('close-modal');
        $this->alertSuccess('User berhasil diperbarui!');
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function delete()
    {
        User::findOrFail($this->confirmingDeleteId)->delete();
        $this->confirmingDeleteId = null;

        $this->alertSuccess('User berhasil dihapus!');
    }

    // ===== HELPER ALERT =====
    private function alertSuccess($message)
    {
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => $message
        ]);
    }
}
