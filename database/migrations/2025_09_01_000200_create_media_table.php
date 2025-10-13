<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('media', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->string('disk',75)->default('public');
$table->string('path',255);
$table->string('mime',255)->nullable();
$table->unsignedBigInteger('size')->nullable();
$table->string('original_name',255)->nullable();
$table->timestamps('created_at')->nullable();
$table->timestamps('updated_at')->nullable();
});
}
public function down(): void { Schema::dropIfExists('media'); }
};