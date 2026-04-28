<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storefront_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('brand_name')->nullable();
            $table->string('brand_tagline')->nullable();
            $table->string('brand_city')->nullable();
            $table->string('brand_address')->nullable();
            $table->string('brand_phone')->nullable();
            $table->string('brand_email')->nullable();
            $table->text('hero_note')->nullable();
            $table->json('brand_hours')->nullable();
            $table->string('delivery_cutoff')->nullable();
            $table->string('pickup_address')->nullable();
            $table->json('delivery_windows')->nullable();
            $table->json('delivery_zones')->nullable();
            $table->json('delivery_promises')->nullable();
            $table->json('storefront_promises')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storefront_settings');
    }
};
