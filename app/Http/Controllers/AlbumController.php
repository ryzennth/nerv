<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use Inertia\Inertia;
use Inertia\Response;

class AlbumController extends Controller
{
    /**
     * Menampilkan daftar album (untuk admin).
     */
    public function index(): Response
    {
        $albums = Album::latest()->paginate(10);
        return Inertia::render('Admin/Albums/Index', [ // Tentukan path view admin
            'albums' => $albums,
        ]);
    }

    /**
     * Menampilkan form untuk membuat album baru.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Albums/Create'); // Tentukan path view admin
    }

    /**
     * Menyimpan album baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:albums',
            'description' => 'nullable|string',
        ]);

        // Slug dibuat otomatis oleh model
        Album::create($validated);

        return to_route('albums.index')->with('message', 'Album created successfully.');
    }

    /**
     * Menampilkan form untuk mengedit album.
     */
    public function edit(Album $album): Response
    {
        return Inertia::render('Admin/Albums/Edit', [ // Tentukan path view admin
            'album' => $album,
        ]);
    }

    /**
     * Mengupdate album.
     */
    public function update(Request $request, Album $album): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('albums')->ignore($album->id)],
            'description' => 'nullable|string',
        ]);

        $album->update([
            ...$validated,
            'slug' => Str::slug($validated['title']) // Update slug juga jika judul berubah
        ]);

        return to_route('albums.index')->with('message', 'Album updated successfully.');
    }

    /**
     * Menghapus album.
     */
    public function destroy(Album $album): RedirectResponse
    {
        // onDelete('cascade') di migrasi photos akan menghapus foto-foto terkait
        $album->delete();

        return to_route('albums.index')->with('message', 'Album deleted successfully.');
    }

    public function showPhotos(Album $album): Response
    {
        // Load foto-foto yang berelasi dengan album ini
        $album->load('photos');

        return Inertia::render('Admin/Albums/Photos', [ // Tentukan path view admin
            'album' => $album,
        ]);
    }

    /**
     * Mengunggah satu atau lebih foto ke dalam album.
     */
    public function uploadPhotos(Request $request, Album $album): RedirectResponse
    {
        $request->validate([
            // Validasi untuk array file gambar
            'photos' => 'required|array',
            'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // Maks 2MB per foto
            'captions' => 'nullable|array', // Opsional: array caption sesuai urutan foto
            'captions.*' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                // Buat path penyimpanan unik, misal: galleries/nama-album-slug/namaunik.jpg
                $path = $file->store('galleries/' . $album->slug, 'public');

                // Dapatkan caption untuk foto ini (jika ada)
                $caption = $request->input('captions.' . $index, null);

                // Simpan data foto ke database
                $album->photos()->create([
                    'file_path' => $path,
                    'caption' => $caption,
                ]);
            }
        }

        return back()->with('message', 'Photos uploaded successfully.');
    }

    /**
     * Menghapus foto spesifik.
     */
    public function destroyPhoto(Photo $photo): RedirectResponse
    {
        // Hapus file dari storage
        if (Storage::disk('public')->exists($photo->file_path)) {
            Storage::disk('public')->delete($photo->file_path);
        }

        // Hapus record dari database
        $photo->delete();

        return back()->with('message', 'Photo deleted successfully.');
    }
}