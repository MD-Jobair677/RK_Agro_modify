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
        Schema::create('cattle_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cattle_id')->comment('Foreign key referencing cattle');
            $table->string('image_path', 255)->comment('Path of the cattle image');
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
        Schema::dropIfExists('cattle_images');
    }
};
