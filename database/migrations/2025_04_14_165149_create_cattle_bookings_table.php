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
        Schema::create('cattle_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("cattle_id")->comment('Foreign key referencing cattle')->nullable();
            $table->unsignedBigInteger("customer_id")->comment('Foreign key referencing customer');
            $table->string("booking_number",100)->comment('Booking number');
            $table->tinyInteger('booking_type')->default(1)->comment('Booking type of the booking (1 for instant booking, 2 for eid booking)');
            $table->decimal('sale_price', 10, 2)->comment('Customer booking cattle sale price');
            $table->decimal('due_price', 10, 2)->comment('Customer booking cattle due price');
            $table->timestamp('delivery_date')->comment('Cattle delivery date');
            $table->tinyInteger('status')->default(1)->comment('Booking for status of the cattle (1 for pending, 2 for delivered, 3 for canceled)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cattle_bookings');
    }
};
