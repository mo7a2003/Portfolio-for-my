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
        Schema::table('projects', function (Blueprint $table): void {
            if (! Schema::hasColumn('projects', 'image')) {
                $table->string('image', 500)->nullable()->after('thumbnail_url');
            }
        });

        Schema::table('skills', function (Blueprint $table): void {
            if (! Schema::hasColumn('skills', 'image')) {
                $table->string('image', 500)->nullable()->after('icon');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            if (Schema::hasColumn('projects', 'image')) {
                $table->dropColumn('image');
            }
        });

        Schema::table('skills', function (Blueprint $table): void {
            if (Schema::hasColumn('skills', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
