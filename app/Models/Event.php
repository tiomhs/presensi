<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [
       'id',
    ];

    /**
     * Get the committees for the event.
     */
    public function committees()
    {
        return $this->hasMany(EventCommittee::class);       
    }

    /**
     * Get the QR codes associated with the event.
     */
    public function qrCodes()
    {
        return $this->belongsTo(QrCode::class);
    }
}
