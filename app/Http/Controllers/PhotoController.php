<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{

    public function incrementHit(Request $request, Photo $photo)
    {
        

        $sessionKey = 'photo_hit_' . $photo->id;
        if (!$request->session()->has($sessionKey)) {
           
            $photo->increment('hits');
            $request->session()->put($sessionKey, true);
        } 

        return response()->noContent(); // HTTP Status 204 No Content
    }

    // Jika Anda memindahkan destroyPhoto ke sini, tambahkan juga:
    // public function destroy(Photo $photo) { ... }
}