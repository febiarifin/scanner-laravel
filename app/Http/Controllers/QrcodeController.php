<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Illuminate\Http\Request;

class QrcodeController extends Controller
{
    public function index()
    {
        return view('qrcode.index');
    }

    public function post(Request $request)
    {
        $data = $request->input('data');

        $presence = Presence::find($data);

        if (!$presence) {
            $presence->create([
                'code' => $data,
                'is_present' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'presences' => Presence::where('is_present', 1)->get(),
        ]);
    }
}
