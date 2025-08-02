<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    public $table = 'qr_tokens';
    
    protected $guarded = ['id'];

    /**
     * Get the event that owns the QR code.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);  
    }    
}
