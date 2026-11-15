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
        Schema::create('certificates', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 150);
            $table->string('issuer', 150);
            $table->string('date', 50)->default('2026');
            $table->string('badge', 100)->nullable();
            $table->string('accent', 50)->default('amber');
            $table->string('credential_id', 150)->nullable();
            $table->text('description');
            $table->json('skills')->nullable();
            $table->string('verify_url', 500)->nullable();
            $table->string('image', 500)->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
