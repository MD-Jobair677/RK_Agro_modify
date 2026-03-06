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
        Schema::create('booking_print_cattle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_print_id')
                  ->constrained('booking_prints')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();

            $table->foreignId('cattle_id')
                  ->constrained('cattles')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_print_cattle');
    }
};
