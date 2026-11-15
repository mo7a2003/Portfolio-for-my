<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 200);
            $table->string('subtitle', 255)->nullable();
            $table->string('slug', 200)->unique();
            $table->text('description');
            $table->string('category', 100)->nullable();
            $table->string('year', 20)->nullable();
            $table->string('accent_color', 50)->nullable();
            $table->string('badge', 100)->nullable();
            $table->string('thumbnail_url', 500)->nullable();
            $table->string('image', 500)->nullable();
            $table->string('live_url', 500)->nullable();
            $table->string('github_url', 500)->nullable();
            $table->json('technologies')->nullable();
            $table->string('status', 50)->default('completed')->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
