<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


    return new class extends Migration {
        public function up(): void {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('titre',255);
                $table->text('texte')->nullable();

                $table->string('media_disk',75)->default('public');
                $table->string('media_url',255);
                $table->string('media_mime',255)->nullable();
                $table->unsignedBigInteger('media_size')->nullable();
                $table->string('media_original_name',255)->nullable();

                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        public function down(): void {
            Schema::dropIfExists('posts');
        }
    };