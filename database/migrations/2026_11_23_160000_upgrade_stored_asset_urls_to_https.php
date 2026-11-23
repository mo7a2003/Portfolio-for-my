<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Profiles
        DB::statement("UPDATE profiles SET avatar_url = REPLACE(avatar_url, 'http://portfolio-for-my.onrender.com', 'https://portfolio-for-my.onrender.com') WHERE avatar_url LIKE 'http://portfolio-for-my.onrender.com%'");
        DB::statement("UPDATE profiles SET cv_url = REPLACE(cv_url, 'http://portfolio-for-my.onrender.com', 'https://portfolio-for-my.onrender.com') WHERE cv_url LIKE 'http://portfolio-for-my.onrender.com%'");

        // 2. Projects
        DB::statement("UPDATE projects SET image = REPLACE(image, 'http://portfolio-for-my.onrender.com', 'https://portfolio-for-my.onrender.com') WHERE image LIKE 'http://portfolio-for-my.onrender.com%'");
        DB::statement("UPDATE projects SET thumbnail_url = REPLACE(thumbnail_url, 'http://portfolio-for-my.onrender.com', 'https://portfolio-for-my.onrender.com') WHERE thumbnail_url LIKE 'http://portfolio-for-my.onrender.com%'");

        // 3. Certificates
        DB::statement("UPDATE certificates SET image = REPLACE(image, 'http://portfolio-for-my.onrender.com', 'https://portfolio-for-my.onrender.com') WHERE image LIKE 'http://portfolio-for-my.onrender.com%'");

        // 4. Skills
        DB::statement("UPDATE skills SET icon = REPLACE(icon, 'http://portfolio-for-my.onrender.com', 'https://portfolio-for-my.onrender.com') WHERE icon LIKE 'http://portfolio-for-my.onrender.com%'");

        // 5. Articles
        DB::statement("UPDATE articles SET image = REPLACE(image, 'http://portfolio-for-my.onrender.com', 'https://portfolio-for-my.onrender.com') WHERE image LIKE 'http://portfolio-for-my.onrender.com%'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
