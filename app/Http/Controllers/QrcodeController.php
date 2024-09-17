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

        $presence = Presence::orderBy('date','desc')->where('code',$data)->first();

        if (!$presence) {
            // $presence->update([
            //     'is_present' => 1,
            //     'date' => now(),
            // ]);
            Presence::create([
                'code' => $data,
                'is_present' => 1,
                'date' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'presences' => Presence::orderBy('date','desc')->where('is_present', 1)->get(),
            'code' => $data,
        ]);
    }
}
