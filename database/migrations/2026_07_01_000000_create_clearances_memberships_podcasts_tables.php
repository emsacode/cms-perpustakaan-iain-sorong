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
        // 1. Clearances (Bebas Pustaka) Table
        Schema::create('clearances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nim_nidn');
            $table->string('program_studi');
            $table->string('phone');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('thesis_file')->nullable(); // Uploaded file path
            $table->string('receipt_file')->nullable(); // Certificate PDF path
            $table->timestamps();
        });

        // 2. Memberships (Kartu Anggota Online) Table
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nim_nip')->unique();
            $table->enum('member_type', ['mahasiswa', 'dosen', 'staff', 'umum'])->default('mahasiswa');
            $table->string('email');
            $table->string('phone');
            $table->string('photo_path')->nullable();
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');
            $table->timestamps();
        });

        // 3. Podcasts Table
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('audio_url')->nullable();
            $table->string('video_url')->nullable(); // YouTube embed/watch url
            $table->text('description');
            $table->string('duration')->default('00:00');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcasts');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('clearances');
    }
};
