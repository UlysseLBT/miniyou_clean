<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('media', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->string('disk')->default('public');
$table->string('path');
$table->string('mime')->nullable();
$table->unsignedBigInteger('size')->nullable();
$table->string('original_name')->nullable();
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('media'); }
};