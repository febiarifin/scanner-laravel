<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Presence;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\IsFalse;

class QrcodeController extends Controller
{
    public function index()
    {
        return view('qrcode.index', [
            'presences' => Presence::orderBy('date', 'desc')->where('is_present', 1)->limit(10)->get(),
        ]);
    }

    public function post(Request $request)
    {
        $code = base64_decode($request->input('code'));
        $event_id = $request->input('event_id');
        $event = Event::find($request->input('event_id'));

        $presence = Presence::where('code', $code)->where('event_id', $event_id)->first();

        if ($presence) {
            if (!$presence->is_present) {
                $presence->update([
                    'is_present' => 1,
                    'date' => now(),
                ]);
                $status = true;
                $message = "Presensi berhasil";
            } else {
                $status = false;
                $message = "Anda sudah melakukan absen jam " . $presence->date;
            }
        } else {
            $status = false;
            $message = "Data tidak ditemukan";
        }
        return response()->json([
            'success' => $status,
            'presences' => $event->presences()->orderBy('date', 'desc')->where('is_present', 1)->limit(10)->get(),
            'detail' => $presence ? $presence : null,
            'message' => $message,
            'status' => $status,
            'counter' => $event->presences()->orderBy('date', 'desc')->where('is_present', 1)->count(),
        ]);
    }
}
