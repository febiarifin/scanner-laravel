<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Illuminate\Http\Request;

class QrcodeController extends Controller
{
    public function index()
    {
        return view('qrcode.index', [
            'presences' => Presence::where('is_present', 1)->get(),
        ]);
    }

    public function post(Request $request)
    {
        $data = $request->input('data');

        $presence = Presence::orderBy('date', 'desc')->where('code', $data)->first();

        if ($presence) {
            $presence->update([
                'is_present' => 1,
                'date' => now(),
                'event_id' => 1,
            ]);
            if ($presence->terdaftar) {
                $message = "Presensi berhasil";
            }else{
                $message = "Presensi berhasil, anak anda tidak mendaftar";
            }
        }else {
            // $presence = Presence::create([
            //     'code' => $data,
            //     'is_present' => 1,
            //     'date' => now(),
            //     'event_id' => 1,
            //     'terdaftar' => 0,
            // ]);
            $message = "Data tidak ditemukan";
        }


        return response()->json([
            'success' => true,
            'presences' => Presence::orderBy('date', 'desc')->where('is_present', 1)->get(),
            'code' => $data,
            'message' => $message,
        ]);
    }
}
