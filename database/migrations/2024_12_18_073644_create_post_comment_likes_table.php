<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('post_comment_id')
                ->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->boolean('value')->index();
            $table->timestamps();

            $table->index(['user_id', 'post_comment_id']);

            $table->index(['user_id', 'value']);
            $table->index(['value', 'post_comment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_comment_likes');
    }
};
