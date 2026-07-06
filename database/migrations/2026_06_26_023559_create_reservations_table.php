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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('nim_nip', 100);
            $table->string('email', 255);
            $table->string('room_name', 255);
            $table->date('booking_date');
            $table->string('session_time', 255);
            $table->enum('status', ['pending', 'approved', 'rejected', 'key_picked_up', 'returned', 'overdue', 'cancelled'])->default('pending');
            $table->string('link_surat')->nullable(); // Reference/Request letter URL
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->text('notes_inventory')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            // Composite unique index untuk mengunci pencegahan double-booking di level database
            $table->unique(['room_name', 'booking_date', 'session_time'], 'uq_room_booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
