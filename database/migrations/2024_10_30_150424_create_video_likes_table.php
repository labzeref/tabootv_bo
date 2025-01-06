<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('video_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('video_id')
                ->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->boolean('value')->index();
            $table->timestamps();

            $table->index(['user_id', 'video_id']);

            $table->index(['user_id', 'value']);
            $table->index(['value', 'video_id']);

            $table->index(['value', 'video_id', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_likes');
    }
};
