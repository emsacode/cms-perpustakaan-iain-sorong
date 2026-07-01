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
        Schema::create('service_hours', function (Blueprint $table) {
            $table->id();
            $table->string('day_name', 50)->unique(); // Senin, Selasa, dll.
            $table->string('open_time', 20)->default('08:00');
            $table->string('close_time', 20)->default('16:00');
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_hours');
    }
};
