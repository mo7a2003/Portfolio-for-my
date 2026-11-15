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
            if (! Schema::hasColumn('projects', 'subtitle')) {
                $table->string('subtitle', 255)->nullable()->after('title');
            }
            if (! Schema::hasColumn('projects', 'category')) {
                $table->string('category', 100)->nullable()->after('description');
            }
            if (! Schema::hasColumn('projects', 'year')) {
                $table->string('year', 20)->nullable()->after('category');
            }
            if (! Schema::hasColumn('projects', 'accent_color')) {
                $table->string('accent_color', 50)->nullable()->after('year');
            }
            if (! Schema::hasColumn('projects', 'badge')) {
                $table->string('badge', 100)->nullable()->after('accent_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn([
                'subtitle',
                'category',
                'year',
                'accent_color',
                'badge',
            ]);
        });
    }
};
