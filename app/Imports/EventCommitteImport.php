<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\User;
use App\Models\EventCommittee;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class EventCommitteImport implements ToModel
{
    public $eventId;
    public $successCount = 0;
    public $duplicateCount = 0;
    public $invalidCount = 0;

    public $user, $nim, $role, $division;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function model(array $row)
    {
        $user = User::where('nim', $row[1])->first();
        $role = Role::where('name', $row[2])->first();
        $division = $row[3];

        // Data tidak valid
        if (!$user || !$role || !$division) {
            $this->invalidCount++;
            return null;
        }

        // Data duplikat
        $exists = EventCommittee::where('event_id', $this->eventId)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            $this->duplicateCount++;
            return null;
        }

        // Data berhasil
        $this->successCount++;

        return new EventCommittee([
            'event_id' => $this->eventId,
            'user_id'  => $user->id,
            'role_id'  => $role->id,
            'division' => $division,
        ]);
    }
}
