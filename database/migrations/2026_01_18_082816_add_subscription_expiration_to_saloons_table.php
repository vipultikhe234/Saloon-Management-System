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
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_level');
            $table->string('stripe_id')->nullable()->after('subscription_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saloons', function (Blueprint $table) {
            $table->dropColumn(['subscription_expires_at', 'stripe_id']);
        });
    }
};
