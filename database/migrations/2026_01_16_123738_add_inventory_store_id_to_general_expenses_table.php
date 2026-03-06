<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('general_expenses', function (Blueprint $table) {
            $table->foreignId('inventory_store_id')
                  ->nullable()
                  ->after('id');

          
        });
    }

    public function down(): void
    {
        Schema::table('general_expenses', function (Blueprint $table) {
            $table->dropForeign(['inventory_store_id']);
            $table->dropColumn('inventory_store_id');
        });
    }
};
