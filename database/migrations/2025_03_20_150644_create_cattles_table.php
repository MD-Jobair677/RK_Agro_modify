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
        Schema::create('cattles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->comment('Foreign key category id');
            $table->string('tag_number',40)->unique()->comment('Unique tag or code number');
            $table->string('name',30)->comment('Cattle name');
            $table->timestamp('purchase_date')->comment('Purchase_date');
            $table->decimal('purchase_price', 10, 2)->default(0.00)->comment('Purchase price of the cattle');
            $table->decimal('purchase_weight', 10, 2)->default(0.00)->comment('Purchase weight of the cattle in kg');
            $table->string('row_number',30)->nullable()->comment('Row number of the cattle record');
            $table->string('stall_number',30)->nullable()->comment('Stall number of the cattle record');
            $table->string('breed', 30)->nullable()->comment('Breed of the cattle');
            $table->enum('gender', ['Male', 'Female', 'Other', 'Unknown'])->nullable()->comment('Gender of the cattle (Male/Female/Other/Unknown)');
            $table->text('description')->nullable()->comment('Description of the cattle');
            $table->tinyInteger('status')->default(1)->comment('Status of the cattle (1 for active, 0 for inactive)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cattles');
    }
};
