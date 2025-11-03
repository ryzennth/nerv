<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            
            // Siapa yang nge-like
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Apa yang di-like (ini polymorphic magic-nya)
            $table->morphs('likeable'); // Ini bakal bikin 'likeable_id' (unsignedBigInteger) dan 'likeable_type' (string)

            $table->timestamps(); // Kapan di-like

            // Kunci unik biar user gak bisa nge-like 2x
            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};