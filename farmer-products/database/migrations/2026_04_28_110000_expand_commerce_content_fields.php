<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->string('producer_name')->nullable()->after('description');
            $table->string('origin_location')->nullable()->after('producer_name');
            $table->string('seasonality', 80)->nullable()->after('origin_location');
            $table->string('taste_notes')->nullable()->after('seasonality');
            $table->string('storage_info')->nullable()->after('taste_notes');
            $table->string('shelf_life', 120)->nullable()->after('storage_info');
            $table->string('delivery_note')->nullable()->after('shelf_life');
            $table->string('badge', 80)->nullable()->after('delivery_note');
            $table->text('ingredients')->nullable()->after('badge');
            $table->json('highlights')->nullable()->after('ingredients');
            $table->json('gallery')->nullable()->after('highlights');
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->string('fulfillment_method', 20)->default('delivery')->after('address');
            $table->string('delivery_window', 120)->nullable()->after('fulfillment_method');
            $table->string('substitution_preference', 120)->nullable()->after('delivery_window');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn([
                'fulfillment_method',
                'delivery_window',
                'substitution_preference',
            ]);
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn([
                'producer_name',
                'origin_location',
                'seasonality',
                'taste_notes',
                'storage_info',
                'shelf_life',
                'delivery_note',
                'badge',
                'ingredients',
                'highlights',
                'gallery',
            ]);
        });
    }
};
