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
        Schema::table('pages', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['draft', 'published', 'scheduled', 'trash'])->default('published');
            $table->enum('seo_score', ['bad', 'ok', 'good', 'none'])->default('none');
            $table->enum('readability_score', ['bad', 'ok', 'good', 'none'])->default('none');
            $table->string('page_builder_type', 50)->default('custom');
            $table->integer('views_count')->default(0);
            $table->timestamp('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn([
                'author_id',
                'status',
                'seo_score',
                'readability_score',
                'page_builder_type',
                'views_count',
                'published_at'
            ]);
        });
    }
};
