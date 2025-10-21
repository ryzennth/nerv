<?php // app/Models/Album.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str; // Tambahkan ini

class Album extends Model {
    use HasFactory;
    protected $fillable = ['title', 'slug', 'description', 'views'];

    // Otomatis buat slug saat membuat album
    protected static function booted() {
        static::creating(function ($album) {
            $album->slug = Str::slug($album->title);
            // Logika cek duplikat slug jika perlu
        });
    }

    // Relasi: Satu album punya banyak foto
    public function photos() {
        return $this->hasMany(Photo::class);
    }

    // Gunakan slug untuk route model binding
    public function getRouteKeyName(): string 
    {
        return 'slug'; 
    }

    public function latestPhoto()
    {
        // hasOne relasi ke Photo, diurutkan descending berdasarkan ID (atau created_at), ambil yang pertama
        return $this->hasOne(Photo::class)->latestOfMany();
    }
}