<?php

namespace App\Http\Controllers;

use App\Exports\PresenceExport;
use App\Imports\PresenceImport;
use App\Models\Event;
use App\Models\Presence;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EventCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'events' => Event::orderBy('date', 'desc')->paginate(10),
        ];
        return view('event.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required'],
            'date' => ['required'],
        ]);
        Event::create($validatedData);
        toastr()->success('Event berhasil ditambahkan');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event->load('presences');
        $data = [
            'event' => $event,
            'presences' => $event->presences()->orderBy('date', 'desc')->where('is_present', 1)->limit(10)->get(),
            'count_presences' => $event->presences()->where('is_present', 1)->get(),
        ];
        return view('qrcode.index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $event->load('presences');
        $data = [
            'title' => 'Detail Event',
            'event' => $event,
            'presences' => $event->presences,
        ];
        return view('event.detail', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->load('presences');
        if (count($event->presences) != 0) {
            toastr()->warning('Event sudah ada data presensi');
            return back();
        }
        $event->delete();
        toastr()->success('Event berhasil dihapus');
        return back();
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new PresenceImport($request->event_id), request()->file('file'));
            toastr()->success('Import data presensi berhasil');
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function export(Event $event)
    {
        $event->load('presences');
        // $data = [
        //     'title' => 'REKAP PRESENSI EVENT #'. $event->name,
        //     'presences' => $event->presences,
        //     'event' => $event,
        // ];
        // return view('report.excel', $data);
        return Excel::download(new PresenceExport($event, $event->presences), 'EXPORT_PRESENSI_EVENT_' . $event->name . '.xlsx');
    }
}
