<?php

namespace App\Livewire\Dashboard\Event;

use Livewire\Component;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;
use App\Models\EventCommittee;
use App\Exports\EventCommitteeExport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

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

    public $token;

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
        $sheet = $spreadsheet->getSheetByName('Absensi'); // Pastikan sesuai nama sheet

        $data = EventCommittee::where('event_id', $this->eventId)->get();

        $startRow = 2; // mulai setelah header
        foreach ($data as $index => $item) {
            // dd($item);
            $row = $startRow + $index;
            $sheet->setCellValue("A{$row}", $index + 1); // NO
            $sheet->setCellValue("B{$row}", $item->user->name); // NAMA
            $sheet->setCellValue("C{$row}", $item->role->name); // JABATAN
            // dd($item->status);
            $sheet->getStyle("A{$row}:D{$row}")->getAlignment()->setVertical('center');
            $sheet->getRowDimension($row)->setRowHeight(40); // Tinggikan row buat nampung ttd

            if ($item->status) {
                // Path ke gambar tanda tangan (bisa dari storage/public)
                $imagePath = storage_path('app/templates/ttd.jpg'); // Ganti path sesuai lokasi gambar

                if (file_exists($imagePath)) {
                    $drawing = new Drawing();
                    $drawing->setPath($imagePath);
                    $drawing->setCoordinates("D{$row}");
                    $drawing->setHeight(35); // Sesuaikan tinggi gambar
                    $drawing->setWorksheet($sheet);
                }
            }
        }
        // dd($this->event);
        $filename = 'laporan_' . $this->event->name. '-' .now()->format('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }


}
