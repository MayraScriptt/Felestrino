<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_cards', function (Blueprint $table) {
            $table->string('detail_title', 140)->nullable()->after('description');
            $table->string('detail_subtitle', 255)->nullable()->after('detail_title');
            $table->text('detail_body')->nullable()->after('detail_subtitle');
            $table->string('detail_image_path', 2048)->nullable()->after('detail_body');
            $table->string('detail_image_caption', 160)->nullable()->after('detail_image_path');
            $table->string('detail_button_text', 80)->nullable()->after('detail_image_caption');
        });
    }

    public function down(): void
    {
        Schema::table('home_cards', function (Blueprint $table) {
            $table->dropColumn([
                'detail_title',
                'detail_subtitle',
                'detail_body',
                'detail_image_path',
                'detail_image_caption',
                'detail_button_text',
            ]);
        });
    }
};
