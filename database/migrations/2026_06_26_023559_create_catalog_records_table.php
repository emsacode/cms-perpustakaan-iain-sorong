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
        Schema::create('catalog_records', function (Blueprint $table) {
            $table->id();
            $table->string('source_id', 100)->unique();
            $table->enum('source_type', ['eprints', 'slims', 'ojs']);
            $table->text('title'); // TEXT untuk menghindari truncation error pada judul panjang
            $table->string('creator', 255)->nullable();
            $table->string('subject', 255)->nullable();
            $table->string('publisher', 255)->nullable();
            $table->string('year', 10)->nullable();
            $table->text('abstract')->nullable();
            $table->string('url_source', 500);
            $table->timestamps();
            
            $table->index('source_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_records');
    }
};
