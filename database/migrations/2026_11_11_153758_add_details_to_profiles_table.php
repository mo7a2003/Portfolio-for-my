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
        Schema::table('profiles', function (Blueprint $table): void {
            if (! Schema::hasColumn('profiles', 'detailed_bio')) {
                $table->text('detailed_bio')->nullable()->after('bio');
            }
            if (! Schema::hasColumn('profiles', 'experience_years')) {
                $table->unsignedTinyInteger('experience_years')->default(1)->after('detailed_bio');
            }
            if (! Schema::hasColumn('profiles', 'projects_completed')) {
                $table->unsignedInteger('projects_completed')->default(3)->after('experience_years');
            }
            if (! Schema::hasColumn('profiles', 'milestones')) {
                $table->json('milestones')->nullable()->after('projects_completed');
            }
            if (! Schema::hasColumn('profiles', 'education')) {
                $table->string('education', 255)->nullable()->after('milestones');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table): void {
            $table->dropColumn([
                'detailed_bio',
                'experience_years',
                'projects_completed',
                'milestones',
                'education',
            ]);
        });
    }
};
