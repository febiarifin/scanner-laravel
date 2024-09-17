<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'kelas',
        'father_name',
        'mother_name',
        'address',
        'is_registered',
        'is_present',
        'date',
        'event_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
