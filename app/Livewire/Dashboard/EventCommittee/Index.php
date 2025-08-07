<?php

namespace App\Livewire\Dashboard\EventCommittee;

use Livewire\Component;

class Index extends Component
{
    use \Livewire\WithPagination;
    public $search = '';
    public $perPage = 10; // Default pagination
    public $isEdit = false;
    public $eventCommitteeId;
    public $eventId;
    public $userId;
    public $roleId;
    public $division;
    public $confirmingDeleteId = null;

    public function updatingPerPage()
    {
        $this->resetPage(); // reset to page 1 whenever per page count is changed
    }

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function resetForm()
    {
        $this->eventCommitteeId = null;
        $this->eventId = '';
        $this->userId = '';
        $this->roleId = '';
        $this->division = '';
        $this->resetExcept('search', 'perPage');
        $this->reset(['isEdit']);
    }

    public function render()
    {
        return view('livewire.dashboard.event-committee.index',
        [
            'eventCommittees' => $this->getEventCommittees(),
            'roles' => $this->getRoles(),
            'users' => $this->getUsers(),
            'events' => $this->getEvents(),
        ])
        ->layout('layouts.app', [
            'page' => 'Event Committee Management',
            'sidebar' => 'Event Committee',
        ]);
    }

    public function getEventCommittees()
    {
        return \App\Models\EventCommittee::when($this->search, function ($q) {
            $q->where('division', 'like', '%' . $this->search . '%')
                // cari dari relasi event -> name
                ->orWhereHas('event', function ($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%');
                })
                // cari dari relasi user -> name
                ->orWhereHas('user', function ($q3) {
                    $q3->where('name', 'like', '%' . $this->search . '%');
                })
                // cari dari relasi role -> name
                ->orWhereHas('role', function ($q4) {
                    $q4->where('name', 'like', '%' . $this->search . '%');
                });
        })->paginate($this->perPage);
    }



    public function getRoles()
    {
        return \App\Models\Role::all();
    }

    public function getUsers()
    {
        return \App\Models\User::all();
    }

    public function getEvents()
    {
        return \App\Models\Event::all();
    }

    public function create()
    {
        $this->resetForm(); // Reset form fields
        $this->dispatch('open-modal');
    }
    
    public function submit()
    {
        // dd($this->eventId, $this->userId, $this->roleId, $this->division);

        $this->validate([
            'eventId' => 'required',
            'userId' => 'required',
            'roleId' => 'required',
            'division' => 'required|string',
        ]);

        \App\Models\EventCommittee::Create(
            [
                'event_id' => $this->eventId,
                'user_id' => $this->userId,
                'role_id' => $this->roleId,
                'division' => $this->division,
            ]
        );

        $this->resetForm();
        $this->dispatch('close-modal');
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Event Committee saved successfully!'
        ]);
    }

    public function edit($id)
    {
        $eventCommittee = \App\Models\EventCommittee::findOrFail($id);
        $this->eventCommitteeId = $eventCommittee->id;
        $this->eventId = $eventCommittee->event_id;
        $this->userId = $eventCommittee->user_id;
        $this->roleId = $eventCommittee->role_id;
        $this->division = $eventCommittee->division;
        $this->isEdit = true;

        $this->dispatch('open-modal');
    }

    public function update()
    {
        $this->validate([
            'eventId' => 'required',
            'userId' => 'required',
            'roleId' => 'required',
            'division' => 'required|string',
        ]);

        $eventCommittee = \App\Models\EventCommittee::findOrFail($this->eventCommitteeId);
        $eventCommittee->update(
            [
                'event_id' => $this->eventId,
                'user_id' => $this->userId,
                'role_id' => $this->roleId,
                'division' => $this->division,
            ]
        );

        $this->resetForm();
        $this->dispatch('close-modal');
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Event Committee updated successfully!'
        ]);
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function delete()
    {
        $eventCommittee = \App\Models\EventCommittee::findOrFail($this->confirmingDeleteId);
        $eventCommittee->delete();

        $this->resetForm();
        $this->dispatch('close-delete-modal');
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Event Committee deleted successfully!'
        ]);
    }

    public function export()
    {
        return \App\Models\EventCommittee::export();
    }

}
