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
        Schema::create('cattle_treatments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cattle_id')->comment('Foreign key referencing cattle');
            $table->string('medicine_name', 255)->comment('Medicine name'); 
            $table->string('treatment_name', 255)->comment('Treatment name');
            $table->timestamp('treatment_date')->comment('Treatment date');
            $table->decimal('cost', 10, 2)->default(0.00)->comment('Total cost for this treatment');
            $table->text('doctor_info', 255)->nullable()->comment('Doctor information'); 
            $table->text('description')->nullable()->comment('Treatment description');
            $table->timestamps();
        
            // Foreign Key Constraint
            $table->foreign('cattle_id')->references('id')->on('cattles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cattle_treatments');
    }
};
