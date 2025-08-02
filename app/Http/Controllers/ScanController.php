<?php

namespace App\Http\Controllers;

use App\Models\EventCommittee;
use App\Models\QrCode;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function scan($eventId, $token)
    {
        try{
            $qrToken = QrCode::where('event_id', $eventId)
                ->where('token', $token)
                ->first();
            
            $idUser = Auth()->user()->id;
    
            $eventCommittee = EventCommittee::where('event_id', $qrToken->event_id)->where('user_id', $idUser)->first();
    
            if ($eventCommittee) {
                $eventCommittee->status = 1;
                $eventCommittee->save();
            }
            
            return redirect()->route('user.event')
                ->with('success', 'Absensi berhasil!');
        } catch (\Exception $e) {
            return redirect()->route('user.event')
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
            }
        }


}
