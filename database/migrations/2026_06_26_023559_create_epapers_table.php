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
        Schema::create('epapers', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->date('published_date');
            $table->string('pdf_file', 255);
            $table->string('cover_image', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epapers');
    }
};
