<?php

namespace App\Livewire\Dashboard\Role;

use Livewire\Component;

class Index extends Component
{
    use \Livewire\WithPagination;
    public $search = '';
    public $perPage = 10; // Default pagination
    public $isEdit = false;
    public $name;
    public $roleId;
    public $confirmingDeleteId = null;


    public function updatingPerPage()
    {
        $this->resetPage(); // reset to page 1 whenever per page count is changed
    }

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function getRoles()
    {
        return \App\Models\Role::when($this->search, function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%');
        })->paginate($this->perPage);
    }

    public function data()
    {
        return [
            'name' => $this->name,
        ];
    }

   public function create()
    {
        $this->resetForm(); // isEdit udah ke-reset di sini
        $this->dispatch('open-modal'); // show modal
    }


    public function resetForm()
    {
        // dd('resetForm called');
        $this->name = '';
        $this->resetExcept('search', 'perPage');
        $this->reset(['name', 'roleId', 'isEdit']);

    }

    public function edit($id)
    {
        $role = \App\Models\Role::findOrFail($id);
        $this->roleId = $id;
        $this->name = $role->name;
        $this->isEdit = true;
        $this->dispatch('open-modal');
    }


    public function update()
    {
        // dd($this->roleId, $this->name);
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = \App\Models\Role::findOrFail($this->roleId);
        $role->update($this->data());

        $this->dispatch('close-modal');
        $this->resetForm();
        $this->isEdit = false;
        $this->roleId = null; // Reset roleId after update
        session()->flash('message', 'Role updated successfully.');
    }


    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        \App\Models\Role::create($this->data());
        $this->dispatch('close-modal');
        session()->flash('message', 'Role created successfully.');
    }

    public function render()
    {
        return view('livewire.dashboard.role.index', [
            'roles' => $this->getRoles(),
        ])->layout('layouts.app', [
            'page' => 'Role Management',
        ]);
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function delete()
    {
        $role = \App\Models\Role::findOrFail($this->confirmingDeleteId);
        $role->delete();
        $this->confirmingDeleteId = null;
        session()->flash('message', 'Role deleted successfully.');
    }


}
