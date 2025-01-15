<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temp_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('uuid');
            $table->integer('chunk_count')->default(0);
            $table->string('file_extension');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temp_media');
    }
};
