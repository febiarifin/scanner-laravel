<?php

namespace App\Imports;

use App\Models\Presence;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PresenceImport implements ToModel, WithHeadingRow
{
    public $event_id;

    public function __construct($event_id)
    {
        $this->event_id = $event_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $check = Presence::where('code', $row['code'])->where('event_id', $this->event_id)->first();
        if (!$check) {
            return new Presence([
                'code'    => $row['code'],
                'name'     => $row['name'],
                'kelas'     => $row['kelas'],
                'father_name'     => $row['father_name'],
                'mother_name'     => $row['mother_name'],
                'address'     => $row['address'],
                'is_registered'     => $row['is_registered'] ? $row['is_registered'] : 0,
                'event_id' => $this->event_id,
            ]);
        }
    }
}
