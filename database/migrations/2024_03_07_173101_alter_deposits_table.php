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
        Schema::table('deposits', function (Blueprint $table) {
            $table->unsignedInteger('campaign_id')->after('id');
            $table->unsignedTinyInteger('donor_type')->after('user_id')->comment('1 -> known donor, 0 -> anonymous donor');
            $table->string('full_name', 255)->nullable()->after('donor_type');
            $table->string('email', 40)->nullable()->after('full_name');
            $table->string('phone', 40)->nullable()->after('email');
            $table->string('country', 40)->nullable()->after('phone');
            $table->unsignedInteger('receiver_id')->after('country');
            $table->renameColumn('final_amo', 'final_amount');
            $table->renameColumn('detail', 'details');
            $table->renameColumn('btc_amo', 'btc_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn([
                'campaign_id',
                'donor_type',
                'full_name',
                'email',
                'phone',
                'country',
                'receiver_id',
            ]);
            $table->renameColumn('final_amount', 'final_amo');
            $table->renameColumn('details', 'detail');
            $table->renameColumn('btc_amount', 'btc_amo');
        });
    }
};
