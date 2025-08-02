<?php

namespace App\Exports;

use App\Models\EventCommittee;
use Maatwebsite\Excel\Concerns\FromCollection;

class EventCommitteeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return EventCommittee::select('event_id', 'user_id', 'role_id', 'division')->get();
    }

    public function headings(): array
    {
        return [
            'Event ID',
            'User ID',
            'Role ID',
            'Division',
        ];
    }


}
