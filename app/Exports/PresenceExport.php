<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PresenceExport implements FromView
{

    public $event;
    public $presences;

    public function __construct($event, $presences)
    {
       $this->event = $event;
       $this->presences = $presences;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('report.excel', [
            'event' => $this->event,
            'presences' => $this->presences,
        ]);
    }
}
