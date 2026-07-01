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
        Schema::create('desiderata', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('author', 255)->nullable();
            $table->string('publisher', 255)->nullable();
            $table->integer('year')->nullable(); // Tahun Terbit
            $table->string('isbn', 50)->nullable();
            $table->string('reference_url', 255)->nullable(); // URL Referensi
            $table->string('proposer_name', 255);
            $table->string('proposer_status', 100)->nullable(); // Status Pengusul (Dosen, Mahasiswa, Tendik)
            $table->string('proposer_email', 255);
            $table->string('course', 255)->nullable(); // Mata Kuliah Terkait
            $table->integer('estimated_students')->nullable(); // Estimasi Mahasiswa
            $table->text('reason')->nullable(); // Alasan Pengusulan
            $table->text('notes')->nullable(); // Catatan Admin
            $table->string('status', 50)->default('pending'); // Status (pending, approved, purchased)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desiderata');
    }
};
