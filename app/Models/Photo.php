<?php // app/Models/Photo.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photo extends Model {
    use HasFactory;
    protected $fillable = ['album_id', 'file_path', 'caption', 'hits'];

    // Relasi: Satu foto milik satu album
    public function album() {
        return $this->belongsTo(Album::class);
    }
}