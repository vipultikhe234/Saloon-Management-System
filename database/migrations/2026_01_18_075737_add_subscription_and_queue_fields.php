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
        Schema::table('saloons', function (Blueprint $table) {
            // Subscription Levels for visibility priority
            $table->enum('subscription_level', ['free', 'silver', 'gold', 'platinum'])->default('free')->after('is_verified');
            // Store wait time per customer calculation (e.g. average 15 mins)
            $table->integer('avg_wait_time_per_customer')->default(20)->after('closing_time'); 
        });

        Schema::table('appointments', function (Blueprint $table) {
            // Token/Queue number
            $table->integer('token_number')->nullable()->after('appointment_number');
            // Make time nullable if it's purely queue based
            $table->time('appointment_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saloons', function (Blueprint $table) {
            $table->dropColumn(['subscription_level', 'avg_wait_time_per_customer']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('token_number');
             // Cannot easily revert nullable change without raw statement, skipping strictly for safety
        });
    }
};
