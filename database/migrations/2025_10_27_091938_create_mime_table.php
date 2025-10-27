<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mime', function (Blueprint $table) {
            $table->id();

            // ex: image / video / audio / application
            $table->string('type', 50);

            // ex: jpeg / png / mp4 / pdf
            $table->string('subtype', 100);

            // ex: image/jpeg (unique)
            $table->string('full', 150)->unique();

            // extensions possibles: ["jpg","jpeg"] (nullable)
            $table->json('extensions')->nullable();

            // autorisé à l’upload ?
            $table->boolean('is_allowed')->default(true);

            // limite indicative en Mo (pour la validation)
            $table->unsignedInteger('max_size_mb')->default(50);

            $table->timestamps();

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mime');
    }
};
