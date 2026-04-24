<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_cards', function (Blueprint $table) {
            $table->boolean('detail_enabled')->default(false)->after('description')->index();
        });
    }

    public function down(): void
    {
        Schema::table('home_cards', function (Blueprint $table) {
            $table->dropColumn('detail_enabled');
        });
    }
};
