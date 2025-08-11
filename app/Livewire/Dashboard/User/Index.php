<?php

namespace App\Livewire\Dashboard\User;

use App\Models\User;
use Livewire\Component;
use App\Imports\UsersImport;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $file;

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

    public function showImportModal()
    {
        $this->dispatch('open-import-modal');
    }


    public function import()
    {
         $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new UsersImport, $this->file->getRealPath());

            $this->reset('file');
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'User berhasil diimport!',
            ]);
        } catch (QueryException $e) {
            // Cek kode error duplikat (MySQL: 23000)
            if ($e->getCode() == '23000') {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Import gagal: Email atau NIM sudah ada yang duplikat!',
                ]);
            } else {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Terjadi kesalahan saat import!',
                ]);
            }
        }
    }

    private function alertSuccess($message)
    {
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => $message
        ]);
    }
}
