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
    public $errors = []; // buat nyimpan error per row
    private $rowNumber = 1;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function model(array $row)
    {
        $this->rowNumber++;

        $nim      = $row['nim'] ?? null;
        $roleName = $row['role'] ?? null;
        $division = $row['division'] ?? null;

        $user = User::where('nim', $nim)->first();
        if (!$user) {
            $this->errors[] = "Baris {$this->rowNumber}: NIM {$nim} tidak ditemukan";
            return null;
        }

        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->errors[] = "Baris {$this->rowNumber}: Role {$roleName} tidak ditemukan";
            return null;
        }

        return new EventCommittee([
            'event_id' => $this->eventId,
            'user_id'  => $user->id,
            'role_id'  => $role->id,
            'division' => $division,
        ]);
    }
}
