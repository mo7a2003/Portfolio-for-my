<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('social_links', function (Blueprint $table): void {
            if (! Schema::hasColumn('social_links', 'platform')) {
                $table->string('platform', 100)->nullable()->after('id');
            }
            if (! Schema::hasColumn('social_links', 'handle')) {
                $table->string('handle', 100)->nullable()->after('platform');
            }
            if (! Schema::hasColumn('social_links', 'category')) {
                $table->string('category', 100)->nullable()->after('handle');
            }
            if (! Schema::hasColumn('social_links', 'accent_color')) {
                $table->string('accent_color', 50)->default('amber')->after('icon');
            }
            if (! Schema::hasColumn('social_links', 'is_direct_contact')) {
                $table->boolean('is_direct_contact')->default(false)->index()->after('accent_color');
            }
            if (! Schema::hasColumn('social_links', 'sort_order')) {
                $table->integer('sort_order')->default(0)->index()->after('is_direct_contact');
            }
        });

        // Copy platform_name to platform if platform is null
        if (Schema::hasColumn('social_links', 'platform') && Schema::hasColumn('social_links', 'platform_name')) {
            DB::table('social_links')
                ->whereNull('platform')
                ->update(['platform' => DB::raw('platform_name')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_links', function (Blueprint $table): void {
            $dropColumns = [];
            foreach (['platform', 'handle', 'category', 'accent_color', 'is_direct_contact', 'sort_order'] as $col) {
                if (Schema::hasColumn('social_links', $col)) {
                    $dropColumns[] = $col;
                }
            }
            if (! empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
