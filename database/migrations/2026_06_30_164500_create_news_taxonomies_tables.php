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
        // 1. Drop old pivot and articles table if they exist to avoid constraint conflicts
        Schema::dropIfExists('articles');

        // 2. Re-create articles table with WordPress-like status enums & scores
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('status', ['draft', 'published', 'scheduled', 'trash'])->default('draft');
            $table->integer('views_count')->default(0);
            $table->enum('seo_score', ['bad', 'ok', 'good', 'none'])->default('none');
            $table->enum('readability_score', ['bad', 'ok', 'good', 'none'])->default('none');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 3. Categories Table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->timestamps();
        });

        // 4. Tags Table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->timestamps();
        });

        // 5. SDGs Tags Table
        Schema::create('sdgs_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // e.g. "SDGs 4"
            $table->string('slug', 100)->unique();
            $table->timestamps();
        });

        // 6. Pivot: article_category
        Schema::create('article_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->unique(['article_id', 'category_id']);
        });

        // 7. Pivot: article_tag
        Schema::create('article_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->unique(['article_id', 'tag_id']);
        });

        // 8. Pivot: article_sdgs_tag
        Schema::create('article_sdgs_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('sdgs_tag_id')->constrained('sdgs_tags')->onDelete('cascade');
            $table->unique(['article_id', 'sdgs_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_sdgs_tag');
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('article_category');
        Schema::dropIfExists('sdgs_tags');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('articles');
    }
};
