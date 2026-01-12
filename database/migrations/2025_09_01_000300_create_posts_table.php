<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // auteur du post
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // contenu du post
            $table->string('titre', 255);
            $table->text('texte')->nullable();

            // lien externe (YouTube, article, etc.)
            $table->string('url', 2048)->nullable();

            // timestamps Laravel classiques
            $table->timestamps();
            // optionnel : si tu veux une corbeille
            // $table->softDeletes();
            
        });
    }

    public function down(): void {
        Schema::dropIfExists('posts');
    }
};
