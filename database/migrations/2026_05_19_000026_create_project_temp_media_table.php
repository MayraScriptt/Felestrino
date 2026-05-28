<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_temp_media', function (Blueprint $table) {
            $table->id();
            $table->string('draft_token', 64)->index();
            $table->string('type', 20)->default('image')->index();
            $table->string('image_path')->nullable();
            $table->string('youtube_id', 32)->nullable();
            $table->text('youtube_url')->nullable();
            $table->string('description', 255)->nullable();
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_temp_media');
    }
};

