<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('profiles', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->string('display_name', 255)->nullable();
$table->string('avatar_path', 255)->nullable();
$table->text('bio')->nullable();
$table->string('website', 255)->nullable();
$table->string('twitter', 255)->nullable();
$table->string('instagram', 255)->nullable();
$table->timestamps('created_at')->nullable();
$table->timestamps('updated_at')->nullable();
});
}
public function down(): void { Schema::dropIfExists('profiles'); }
};