<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Rend post_id nullable pour supporter les signalements de commentaires
            $table->foreignId('comment_id')->nullable()->after('post_id')
                  ->constrained()->cascadeOnDelete();
            $table->change('post_id', function (Blueprint $col) {});
        });

        // On doit recréer la contrainte unique pour inclure comment_id
        Schema::table('reports', function (Blueprint $table) {
            $table->dropUnique(['post_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['comment_id']);
            $table->dropColumn('comment_id');
            $table->unique(['post_id', 'user_id']);
        });
    }
};