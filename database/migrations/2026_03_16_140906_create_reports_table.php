<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_create_reports_table.php
public function up(): void
{
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // qui signale
        $table->foreignId('post_id')->constrained()->onDelete('cascade'); // quel post
        $table->enum('reason', ['spam', 'inappropriate', 'harassment', 'misinformation']);
        $table->enum('status', ['pending', 'reviewed', 'dismissed'])->default('pending');
        $table->timestamps();

        // Un user ne peut signaler un post qu'une seule fois
        $table->unique(['user_id', 'post_id']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
