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
        // 1. Projects Table Updates
        $projectCols = Schema::getColumnListing('projects');
        Schema::table('projects', function (Blueprint $table) use ($projectCols): void {
            if (! in_array('title_ar', $projectCols, true)) {
                $table->string('title_ar')->nullable()->after('title');
            }
            if (! in_array('subtitle_ar', $projectCols, true)) {
                $table->string('subtitle_ar')->nullable()->after('subtitle');
            }
            if (! in_array('tagline_ar', $projectCols, true)) {
                $table->string('tagline_ar')->nullable()->after('tagline');
            }
            if (! in_array('description_ar', $projectCols, true)) {
                $table->text('description_ar')->nullable()->after('description');
            }
            if (! in_array('category_ar', $projectCols, true)) {
                $table->string('category_ar')->nullable()->after('category');
            }
            if (! in_array('start_date', $projectCols, true)) {
                $table->string('start_date')->nullable()->after('year');
            }
            if (! in_array('end_date', $projectCols, true)) {
                $table->string('end_date')->nullable()->after('start_date');
            }
            if (! in_array('completion_date', $projectCols, true)) {
                $table->string('completion_date')->nullable()->after('end_date');
            }
        });

        // 2. Profiles Table Updates
        $profileCols = Schema::getColumnListing('profiles');
        Schema::table('profiles', function (Blueprint $table) use ($profileCols): void {
            if (! in_array('full_name_ar', $profileCols, true)) {
                $table->string('full_name_ar')->nullable()->after('full_name');
            }
            if (! in_array('title_ar', $profileCols, true)) {
                $table->string('title_ar')->nullable()->after('title');
            }
            if (! in_array('bio_ar', $profileCols, true)) {
                $table->text('bio_ar')->nullable()->after('bio');
            }
            if (! in_array('detailed_bio_ar', $profileCols, true)) {
                $table->text('detailed_bio_ar')->nullable()->after('detailed_bio');
            }
            if (! in_array('education_ar', $profileCols, true)) {
                $table->string('education_ar')->nullable()->after('education');
            }
            if (! in_array('milestones_ar', $profileCols, true)) {
                $table->json('milestones_ar')->nullable()->after('milestones');
            }
            if (! in_array('birth_date', $profileCols, true)) {
                $table->string('birth_date')->nullable()->after('education_ar');
            }
        });

        // 3. Certificates Table Updates
        $certCols = Schema::getColumnListing('certificates');
        Schema::table('certificates', function (Blueprint $table) use ($certCols): void {
            if (! in_array('title_ar', $certCols, true)) {
                $table->string('title_ar')->nullable()->after('title');
            }
            if (! in_array('issuer_ar', $certCols, true)) {
                $table->string('issuer_ar')->nullable()->after('issuer');
            }
            if (! in_array('description_ar', $certCols, true)) {
                $table->text('description_ar')->nullable()->after('description');
            }
            if (! in_array('issue_date', $certCols, true)) {
                $table->string('issue_date')->nullable()->after('date');
            }
            if (! in_array('expiry_date', $certCols, true)) {
                $table->string('expiry_date')->nullable()->after('issue_date');
            }
        });

        // 4. Articles Table Updates
        $articleCols = Schema::getColumnListing('articles');
        Schema::table('articles', function (Blueprint $table) use ($articleCols): void {
            if (! in_array('title_ar', $articleCols, true)) {
                $table->string('title_ar')->nullable()->after('title');
            }
            if (! in_array('excerpt_ar', $articleCols, true)) {
                $table->text('excerpt_ar')->nullable()->after('excerpt');
            }
            if (! in_array('content_ar', $articleCols, true)) {
                $table->longText('content_ar')->nullable()->after('content');
            }
            if (! in_array('category_ar', $articleCols, true)) {
                $table->string('category_ar')->nullable()->after('category');
            }
            if (! in_array('published_at', $articleCols, true)) {
                $table->timestamp('published_at')->nullable()->after('read_time');
            }
        });

        // 5. Skills Table Updates
        $skillCols = Schema::getColumnListing('skills');
        Schema::table('skills', function (Blueprint $table) use ($skillCols): void {
            if (! in_array('name_ar', $skillCols, true)) {
                $table->string('name_ar')->nullable()->after('name');
            }
            if (! in_array('category_ar', $skillCols, true)) {
                $table->string('category_ar')->nullable()->after('category');
            }
            if (! in_array('description_ar', $skillCols, true)) {
                $table->text('description_ar')->nullable()->after('proficiency_level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $skillCols = Schema::getColumnListing('skills');
        Schema::table('skills', function (Blueprint $table) use ($skillCols): void {
            $cols = array_values(array_intersect(['name_ar', 'category_ar', 'description_ar'], $skillCols));
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        $articleCols = Schema::getColumnListing('articles');
        Schema::table('articles', function (Blueprint $table) use ($articleCols): void {
            $cols = array_values(array_intersect(['title_ar', 'excerpt_ar', 'content_ar', 'category_ar'], $articleCols));
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        $certCols = Schema::getColumnListing('certificates');
        Schema::table('certificates', function (Blueprint $table) use ($certCols): void {
            $cols = array_values(array_intersect(['title_ar', 'issuer_ar', 'description_ar', 'issue_date', 'expiry_date'], $certCols));
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        $profileCols = Schema::getColumnListing('profiles');
        Schema::table('profiles', function (Blueprint $table) use ($profileCols): void {
            $cols = array_values(array_intersect(['full_name_ar', 'title_ar', 'bio_ar', 'detailed_bio_ar', 'education_ar', 'milestones_ar', 'birth_date'], $profileCols));
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        $projectCols = Schema::getColumnListing('projects');
        Schema::table('projects', function (Blueprint $table) use ($projectCols): void {
            $cols = array_values(array_intersect(['title_ar', 'subtitle_ar', 'tagline_ar', 'description_ar', 'category_ar', 'start_date', 'end_date', 'completion_date'], $projectCols));
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
