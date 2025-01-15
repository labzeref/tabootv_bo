<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('value')->unique()->index(); // Unique device token
            $table->timestamps();
            $table->index(['user_id', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};
