<?php

namespace App\Livewire\Dashboard\Event;

use Livewire\Component;

class Detail extends Component
{
    use \Livewire\WithPagination;

    public $eventId;
    public $eventCommitteeId;
    public $userId;
    public $roleId;
    public $division;
    public $status;
    public $eventCommittees;
    public $confirmingDeleteId = null;
    public $search = '';
    public $perPage = 10; // Default pagination
    public $isEdit = false;
    public $event;

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function mount($eventId)
    {
        // dd('Mounting Detail Event ID: ' . $eventId);
        $this->eventId = $eventId;
        $this->event = \App\Models\Event::find($eventId);
        // dd($this->event);
    }

    public function render()
    {
        // dd($this->getEvent());
        return view('livewire.dashboard.event.detail', [
            'events' => $this->getEvent(),
            'event' => $this->event,
            'roles' => $this->getRoles(),
            'users' => $this->getUsers(),
            // 'eventCommittees' => $this->getEventCommittees(),
        ])->layout('layouts.app', [
            'page' => 'Event Detail',
        ]);
    }

    public function getEvent()
    {
        return \App\Models\EventCommittee::where('event_id', $this->eventId)->get();
    }

    public function getRoles()
    {
        return \App\Models\Role::all();
    }

    public function getUsers()
    {
        return \App\Models\User::all();
    }

    public function resetForm()
    {
        $this->userId = '';
        $this->roleId = '';
        $this->division = '';
        $this->reset(['isEdit']);
    }

    public function create()
    {
        $this->resetForm(); // Reset form fields
        $this->dispatch('open-modal');
    }

    // public function presence($eventId)
    // {
    //     // dd('Presence method called with event ID: ' . $eventId);
    //     return redirect()->route('dashboard.events.attendances', ['eventId' => $eventId]);
    // }

    public function submit()
    {
        // dd($this->eventId, $this->userId, $this->roleId, $this->division);

        $this->validate([
            'eventId' => 'required',
            'userId' => 'required',
            'roleId' => 'required',
            'division' => 'required',

        ]);

        // dd($this->eventId, $this->userId, $this->roleId, $this->division);

        \App\Models\EventCommittee::create(
            [
                'event_id' => $this->eventId,
                'user_id' => $this->userId,
                'role_id' => $this->roleId,
                'division' => $this->division,
                'status' => 0,
            ]
        );

        $this->resetForm();
        $this->dispatch('close-modal');
         $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Event created successfully!'
        ]);
    }

    public function edit($id)
    {
        $eventCommittee = \App\Models\EventCommittee::findOrFail($id);
        $this->userId = $eventCommittee->user_id;
        $this->roleId = $eventCommittee->role_id;
        $this->division = $eventCommittee->division;
        $this->eventId = $eventCommittee->event_id;
        $this->eventCommitteeId = $eventCommittee->id;
        $this->isEdit = true;

        $this->dispatch('open-modal');
    }

    public function update()
    {
        $this->validate([
            'userId' => 'required',
            'roleId' => 'required',
            'division' => 'required',
        ]);

        $eventCommittee = \App\Models\EventCommittee::findOrFail($this->eventCommitteeId);
        $eventCommittee->update(
            [
                'user_id' => $this->userId,
                'role_id' => $this->roleId,
                'division' => $this->division,
                'status' => $this->status, // Assuming status is a field in EventCommittee
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
        $this->dispatch('close-modal');
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Event Committee deleted successfully!'
        ]);
    }

}
