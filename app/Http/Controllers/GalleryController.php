<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller
{
    /**
     * Menampilkan halaman daftar album galeri.
     */
    public function index(): Response
    {
        $albums = Album::withCount('photos')
            ->with('latestPhoto')
            ->latest()
            ->paginate(12); // Tampilkan misal 12 album per halaman

        return Inertia::render('Gallery/Index', [
            'albums' => $albums,
        ]);
    }

    /**
     * Menampilkan halaman detail album beserta foto-fotonya.
     */
    public function show(Request $request, Album $album): Response
    {
        // Increment view count for the album (per session)
        $sessionKey = 'album_viewed_' . $album->id;
        if (!$request->session()->has($sessionKey)) {
            $album->increment('views');
            $request->session()->put($sessionKey, true);
        }

        // Load foto-foto di dalam album
        $photos = $album->photos()->latest()->paginate(24); // Tampilkan misal 24 foto per halaman

        return Inertia::render('Gallery/Show', [
            'album' => $album,
            'photos' => $photos,
        ]);
    }
}