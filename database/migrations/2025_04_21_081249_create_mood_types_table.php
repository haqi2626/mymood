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
        Schema::create('mood_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // contoh: Senang, Sedih
            $table->string('image_url')->nullable(); // bisa icon atau ilustrasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_types');
    }
};
