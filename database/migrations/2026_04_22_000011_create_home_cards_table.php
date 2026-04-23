<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('icon', 60)->nullable();
            $table->string('link_url', 2048)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_cards');
    }
};
