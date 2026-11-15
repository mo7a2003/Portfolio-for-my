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
        Schema::create('profiles', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name', 150);
            $table->string('title', 150);
            $table->text('bio');
            $table->text('detailed_bio')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(1);
            $table->unsignedInteger('projects_completed')->default(3);
            $table->json('milestones')->nullable();
            $table->string('education', 255)->nullable();
            $table->string('avatar_url', 500)->nullable();
            $table->string('cv_url', 500)->nullable();
            $table->string('email', 150);
            $table->string('phone', 50)->nullable();
            $table->string('availability_status', 50)->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
