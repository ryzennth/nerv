<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function incrementHit(Request $request, Photo $photo)
    {
        // Gunakan session untuk mencegah hit berulang dalam satu sesi
        $sessionKey = 'photo_hit_' . $photo->id;
        if (!$request->session()->has($sessionKey)) {
            $photo->increment('hits');
            $request->session()->put($sessionKey, true);
        }

        // Kirim respons OK (tidak perlu redirect)
        return response()->json(['message' => 'Hit recorded']);
    }

    // Jika Anda memindahkan destroyPhoto ke sini, tambahkan juga:
    // public function destroy(Photo $photo) { ... }
}