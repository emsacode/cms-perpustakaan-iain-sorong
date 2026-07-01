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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->integer('q1'); // Unsur 1
            $table->integer('q2'); // Unsur 2
            $table->integer('q3'); // Unsur 3
            $table->integer('q4'); // Unsur 4
            $table->integer('q5'); // Unsur 5
            $table->integer('q6'); // Unsur 6
            $table->integer('q7'); // Unsur 7
            $table->integer('q8'); // Unsur 8
            $table->integer('q9'); // Unsur 9
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
