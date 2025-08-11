<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row); // Debugging line to inspect the row data

        return new User([
            'name' => $row['1'],
            'email' => $row['2'],
            'nim' => $row['3'],
            'password' => Hash::make($row['4']),
            'is_admin' => 0,
        ]);
    }
}
