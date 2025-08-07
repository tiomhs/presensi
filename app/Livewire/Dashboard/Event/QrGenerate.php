<?php

namespace App\Livewire\Dashboard\Event;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;

class QrGenerate extends Component
{
    public $eventId;
    public $token;
    public $qrCode;
    public $expiresAt;
    public $event;
    public $isEdit = false;
    public $confirmingDeleteId = null;
    public $qrTokens;
    public $dataUri;

    public function mount($eventId)
    {
        $this->eventId = $eventId;
        $this->event = \App\Models\Event::find($eventId);
        $this->qrTokens = \App\Models\QrCode::where('event_id', $eventId)->first();
        // dd($this->qrTokens);
    }

    public function render()
    {
        return view('livewire.dashboard.event.qr-generate',
        [
            'event' => $this->event,
            'qrTokens' => $this->qrTokens,
            'qrCodeBase64' => $this->getQrCodeBase64($this->eventId),
            'dataUri' => $this->dataUri,
        ])->layout('layouts.app', [
            'page' => 'Generate QR Code',
            'sidebar' => 'Event',
        ]);
    }

    public function getQrCodeBase64($eventId)
    {

         $qrCode = new \Endroid\QrCode\QrCode($this->qrTokens->token);
         $writer = new \Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);
        $this->dataUri = $result->getDataUri();
        
    }
}
