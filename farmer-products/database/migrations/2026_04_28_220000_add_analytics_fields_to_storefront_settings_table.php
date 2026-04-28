<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('storefront_settings', function (Blueprint $table): void {
            $table->string('analytics_provider')->nullable()->after('storefront_promises');
            $table->string('ga_measurement_id')->nullable()->after('analytics_provider');
            $table->string('gtm_container_id')->nullable()->after('ga_measurement_id');
            $table->boolean('track_web_vitals')->nullable()->after('gtm_container_id');
            $table->boolean('analytics_debug')->nullable()->after('track_web_vitals');
        });
    }

    public function down(): void
    {
        Schema::table('storefront_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'analytics_provider',
                'ga_measurement_id',
                'gtm_container_id',
                'track_web_vitals',
                'analytics_debug',
            ]);
        });
    }
};
