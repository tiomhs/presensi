<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCommittee extends Model
{
    protected $guarded = [
        'id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }
}
