<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // database/migrations/xxxx_add_is_private_to_communities_table.php
   public function up(): void
{
    Schema::table('communities', function (Blueprint $table) {
        $table->boolean('is_private')->default(false)->after('description'); // adapte "after"
    });
}

};
