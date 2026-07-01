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
        Schema::table('clearances', function (Blueprint $table) {
            $table->index('nim_nidn');
            $table->index('status');
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('desiderata', function (Blueprint $table) {
            $table->index('isbn');
            $table->index('status');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->index('slug');
            $table->index('is_published');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->index('slug');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['pages_slug_index']);
            $table->dropIndex(['pages_is_active_index']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['posts_slug_index']);
            $table->dropIndex(['posts_is_published_index']);
        });

        Schema::table('desiderata', function (Blueprint $table) {
            $table->dropIndex(['desiderata_isbn_index']);
            $table->dropIndex(['desiderata_status_index']);
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->dropIndex(['memberships_status_index']);
        });

        Schema::table('clearances', function (Blueprint $table) {
            $table->dropIndex(['clearances_nim_nidn_index']);
            $table->dropIndex(['clearances_status_index']);
        });
    }
};
