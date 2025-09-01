<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('profiles', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->string('display_name')->nullable();
$table->string('avatar_path')->nullable();
$table->text('bio')->nullable();
$table->string('website')->nullable();
$table->string('twitter')->nullable();
$table->string('instagram')->nullable();
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('profiles'); }
};