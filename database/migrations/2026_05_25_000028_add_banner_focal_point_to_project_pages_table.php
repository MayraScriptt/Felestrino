<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_pages', function (Blueprint $table) {
            $table->unsignedTinyInteger('banner_position_x')->default(50)->after('banner_path');
            $table->unsignedTinyInteger('banner_position_y')->default(50)->after('banner_position_x');
        });
    }

    public function down(): void
    {
        Schema::table('project_pages', function (Blueprint $table) {
            $table->dropColumn(['banner_position_x', 'banner_position_y']);
        });
    }
};

