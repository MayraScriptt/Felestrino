<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->string('banner_subtitle', 255)->nullable()->after('banner_path');
            $table->text('banner_description')->nullable()->after('banner_subtitle');
        });
    }

    public function down(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->dropColumn(['banner_subtitle', 'banner_description']);
        });
    }
};
