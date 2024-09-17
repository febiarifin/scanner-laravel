<?php

namespace App\Http\Controllers;

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
        $code = $request->input('code');
        $event_id = $request->input('event_id');

        $presence = Presence::where('code', $code)->first();

        if ($presence) {
            $presence->update([
                'is_present' => 1,
                'date' => now(),
                'event_id' => $event_id,
            ]);
            $status = true;
            $message = "Presensi berhasil";
        } else {
            $status = false;
            $message = "Data tidak ditemukan";
        }
        return response()->json([
            'success' => true,
            'presences' => Presence::orderBy('date', 'desc')->where('is_present', 1)->limit(10)->get(),
            'detail' => $presence ? $presence : null,
            'message' => $message,
            'status' => $status,
            'counter' => Presence::orderBy('date', 'desc')->where('is_present', 1)->count(),
        ]);
    }
}
