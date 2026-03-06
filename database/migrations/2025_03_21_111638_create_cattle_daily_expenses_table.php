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
        Schema::create('cattle_daily_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cattle_id')->comment('Foreign key referencing cattle');
            $table->unsignedBigInteger('cattle_record_id')->comment('Foreign key referencing cattle record');
            $table->decimal('feeding_cost', 10, 2)->comment('Cost for feeding the cattle'); 
            $table->decimal('treatment_cost', 10, 2)->comment('Cost for cattle treatment');
            $table->decimal('other_cost', 10, 2)->default(0.00)->comment('Other miscellaneous costs');
            $table->date('date')->comment('Date of expense');
            $table->decimal('total_cost', 10, 2)->default(0.00)->comment('Total cost for this cattle on the given date');
            $table->timestamps();
        
            // Foreign Key Constraint
            $table->foreign('cattle_id')->references('id')->on('cattles')->onDelete('cascade');
            $table->foreign('cattle_record_id')->references('id')->on('cattle_records')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cattle_daily_expenses');
    }
};
