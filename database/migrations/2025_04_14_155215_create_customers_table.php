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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string("first_name",20)->comment('Customer first name');
            $table->string("last_name",20)->nullable()->comment('Customer last name');
            $table->string("image_path",255)->nullable()->comment('Customer image');
            $table->string("email",20)->unique()->nullable()->comment('Customer email');
            $table->integer("phone")->comment('Customer phone');
            $table->string("nid_number")->nullable()->comment('Customer national id card number');
            $table->longText("address")->comment('Customer address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
