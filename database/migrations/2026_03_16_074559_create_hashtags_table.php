<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_create_hashtags_table.php
    public function up(): void
    {
    Schema::create('hashtags', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique(); // ex: "laravel" (sans le #)
        $table->timestamps();
    });

    Schema::create('hashtag_post', function (Blueprint $table) {
        $table->foreignId('hashtag_id')->constrained()->onDelete('cascade');
        $table->foreignId('post_id')->constrained()->onDelete('cascade');
        $table->primary(['hashtag_id', 'post_id']);
    });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hashtag_post');
        Schema::dropIfExists('hashtags');
    }
};
