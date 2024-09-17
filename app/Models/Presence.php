<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'is_present',
        'date',
        'event_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
