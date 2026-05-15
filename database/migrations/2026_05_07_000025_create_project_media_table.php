<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('type', 20)->default('image')->index();
            $table->string('image_path')->nullable();
            $table->string('youtube_id', 32)->nullable();
            $table->text('youtube_url')->nullable();
            $table->string('description', 255)->nullable();
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        if (Schema::hasTable('project_images')) {
            $rows = DB::table('project_images')->select([
                'project_id',
                'image_path',
                'description',
                'display_order',
                'is_active',
                'created_at',
                'updated_at',
            ])->get();

            foreach ($rows as $row) {
                DB::table('project_media')->insert([
                    'project_id' => $row->project_id,
                    'type' => 'image',
                    'image_path' => $row->image_path,
                    'youtube_id' => null,
                    'youtube_url' => null,
                    'description' => $row->description,
                    'display_order' => $row->display_order,
                    'is_active' => $row->is_active,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_media');
    }
};

