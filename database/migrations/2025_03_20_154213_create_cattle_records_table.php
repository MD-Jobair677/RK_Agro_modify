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
        Schema::create('cattle_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cattle_id')->comment('Foreign key cattle id');
            $table->decimal('total_weight', 10, 2)->comment('Total weight of the cattle in kg');
            $table->decimal('price_for_weight', 10, 2)->comment('Total price for a specific weight in BDT');
            $table->decimal('weight_for_price', 10, 2)->comment('Total weight for a specific price in kg');
            $table->decimal('growth_weight', 10, 2)->default(0.00)->comment('Growth weight of the cattle in kg or gm');
            $table->timestamp('purchase_date')->comment('Purchase date');
            $table->timestamp('valid_until_date')->nullable()->comment('Valid until this date');
            $table->tinyInteger('is_opening')->default(0)->comment('Indicates if the cattle entry first time is part of the opening (0 for no, 1 for yes)');
            $table->timestamps();

            // define foreign key explanation
            $table->foreign('cattle_id')->references('id')->on('cattles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cattle_records');
    }
};
