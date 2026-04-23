<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_carousel_items', function (Blueprint $table) {
            $table->string('subtitle', 255)->nullable()->after('title');
            $table->string('button_text', 80)->nullable()->after('link_url');
            $table->string('button_url', 2048)->nullable()->after('button_text');
        });
    }

    public function down(): void
    {
        Schema::table('home_carousel_items', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'button_text', 'button_url']);
        });
    }
};
