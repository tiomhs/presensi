<?php

namespace App\Livewire\Dashboard\Event;

use Livewire\Component;

class Index extends Component
{
    use \Livewire\WithPagination;
    public $search = '';
    public $perPage = 10; // Default pagination
    public $isEdit = false;
    public $name;
    public $location;
    public $date;
    public $eventId;
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
        $this->name = '';
        $this->location = '';
        $this->date = '';
        $this->resetExcept('search', 'perPage');
        $this->reset(['name', 'eventId', 'isEdit']);
    }

    public function detail($id)
    {
        // dd('Detail Event ID: ' . $id);
        // Redirect to the event committee page with the event ID
        return redirect()->route('dashboard.events.detail', ['eventId' => $id]);
    }

    public function create()
    {
        // dd('Create Event');
        $this->resetForm(); // Reset form fields
        $this->dispatch('open-modal');
    }

    public function getEvents()
    {
        return \App\Models\Event::when($this->search, function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%');
        })->paginate($this->perPage);
    }
    public function render()
    {
        return view('livewire.dashboard.event.index',
            [
                'events' => $this->getEvents(),
            ]
        )->layout('layouts.app', [
            'page' => 'Event Management',
            'sidebar' => 'Event',
        ]);
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'required',
            'date' => 'required|date',
        ]);

        \App\Models\Event::create(
            [
                'name' => $this->name,
                'location' => $this->location,
                'date' => $this->date,
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
        $event = \App\Models\Event::findOrFail($id);
        $this->name = $event->name;
        $this->location = $event->location;
        $this->date = $event->date;
        $this->eventId = $event->id;
        $this->isEdit = true;

        $this->dispatch('open-modal');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'required',
            'date' => 'required|date',
        ]);

        $event = \App\Models\Event::findOrFail($this->eventId);
        $event->update(
            [
                'name' => $this->name,
                'location' => $this->location,
                'date' => $this->date,
            ]
        );

        $this->resetForm();
        $this->dispatch('close-modal');
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Event updated successfully!'
        ]);
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function delete()
    {
        $event = \App\Models\Event::findOrFail($this->confirmingDeleteId);
        $event->delete();

        $this->confirmingDeleteId = null;
        $this->dispatch('close-delete-modal');
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Event deleted successfully!'
        ]);
    }
}
