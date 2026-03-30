<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Rendre post_id nullable
        DB::statement('ALTER TABLE reports MODIFY post_id BIGINT UNSIGNED NULL');

        Schema::table('reports', function (Blueprint $table) {
            // 2. Drop la foreign key sur post_id d'abord (elle bloque l'index unique)
            $table->dropForeign(['post_id']);

            // 3. Maintenant on peut drop l'index unique
            $table->dropUnique(['post_id', 'user_id']);

            // 4. Remettre la foreign key sur post_id (sans l'index unique)
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();

            // 5. Ajouter comment_id
            $table->foreignId('comment_id')->nullable()->after('post_id')
                  ->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['comment_id']);
            $table->dropColumn('comment_id');
            $table->dropForeign(['post_id']);
            $table->unique(['post_id', 'user_id']);
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();
        });

        DB::statement('ALTER TABLE reports MODIFY post_id BIGINT UNSIGNED NOT NULL');
    }
};