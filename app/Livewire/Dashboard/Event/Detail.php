<?php

namespace App\Livewire\Dashboard\Event;

use App\Models\Role;
use App\Models\User;
use App\Models\Event;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\EventCommittee;
use App\Imports\EventCommitteImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Detail extends Component
{
    use \Livewire\WithPagination;
    use \Livewire\WithFileUploads;

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

    public $token;
    public $file;

    public $importErrors = [];

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
            'sidebar' => 'Event',
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

   public function generateQrCode($eventId)
    {
        $this->token = Str::random(10);

        $existingQr = \App\Models\QrCode::where('event_id', $eventId)->first();

        if (!$existingQr || $existingQr->expires_at <= now()) {
            $this->addQrCode($eventId);
        }

        return redirect()->route('dashboard.qr', ['eventId' => $eventId]);
    }

    public function addQrCode($eventId)
    {
        \App\Models\QrCode::create([
            'event_id'   => $eventId,
            'token'      => $this->token,
            'expires_at' => now()->addDay(),
        ]);

        $this->dispatch('show-alert', [
            'type'    => 'success',
            'message' => 'QR Code berhasil ditambahkan!',
        ]);
    }

    public function export()
    {
        $templatePath = storage_path('app/templates/template.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getSheetByName('Absensi');

        $data = EventCommittee::where('event_id', $this->eventId)->get();
        // dd($data);

        $startRow = 2; // mulai setelah header

        foreach ($data as $index => $item) {
            $row = $startRow + $index;

            // Isi data text
            $sheet->setCellValue("A{$row}", $index + 1); 
            $sheet->setCellValue("B{$row}", $item->user->name);
            $sheet->setCellValue("C{$row}", $item->role->name);



            // Style row
            $sheet->getStyle("A{$row}:D{$row}")
                ->getAlignment()->setVertical('center');
            $sheet->getRowDimension($row)->setRowHeight(40);

            // Kalau status = hadir, kasih gambar
           if ($item->status === 1) {
                $sheet->setCellValue("D{$row}", "✓");
                $sheet->getStyle("D{$row}")->getFont()->setItalic(true)->setSize(12);
                $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }

        // Output file
        $filename = 'laporan_' . $this->event->name . '-' . now()->format('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    public function showImportModal()
    {
        $this->dispatch('open-import-modal');
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        $importer = new EventCommitteImport($this->eventId);
        Excel::import($importer, $this->file->getRealPath());

        $this->reset('file');
        $this->dispatch('close-import-modal');

        $message = "{$importer->successCount} data berhasil diinput. <br>"
                . "{$importer->duplicateCount} data duplikat. <br>"
                . "{$importer->invalidCount} data gagal / tidak valid.";

        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => $message,
        ]);
    }



}
