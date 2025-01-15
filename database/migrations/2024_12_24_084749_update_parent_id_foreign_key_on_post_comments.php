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
        Schema::table('post_comments', function (Blueprint $table) {
            // Drop the existing foreign key and index for parent_id
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id']);

            // Add the new foreign key constraint referencing post_comments
            $table->foreign('parent_id')
                ->references('id')
                ->on('post_comments')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            // Drop the updated foreign key and index for parent_id
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id']);

            // Restore the original foreign key constraint referencing comments
            $table->foreign('parent_id')
                ->references('id')
                ->on('comments')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
};
