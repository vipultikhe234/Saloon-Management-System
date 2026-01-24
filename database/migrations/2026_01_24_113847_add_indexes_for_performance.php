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
            $table->index(['is_active', 'is_verified', 'subscription_expires_at'], 'idx_saloon_visibility');
            $table->index('state');
            $table->index('city');
            $table->index('subscription_level');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->index('status');
            $table->index('appointment_date');
            $table->index('user_id');
            $table->index('saloon_id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('saloon_id');
            $table->index('category_id');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('saloon_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saloons', function (Blueprint $table) {
            $table->dropIndex('idx_saloon_visibility');
            $table->dropIndex(['state']);
            $table->dropIndex(['city']);
            $table->dropIndex(['subscription_level']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['appointment_date']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['saloon_id']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['saloon_id']);
            $table->dropIndex(['category_id']);
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['saloon_id']);
        });
    }
};
