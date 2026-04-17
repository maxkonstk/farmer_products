<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('order_number')->nullable()->after('id');
        });

        DB::table('orders')->orderBy('id')->get()->each(function (object $order): void {
            DB::table('orders')
                ->where('id', $order->id)
                ->update([
                    'order_number' => 'FL-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                ]);
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->unique('order_number');
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->index('name');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->index('name');
            $table->index('price');
            $table->index(['category_id', 'is_active']);
            $table->index(['is_active', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropIndex(['name']);
            $table->dropIndex(['price']);
            $table->dropIndex(['category_id', 'is_active']);
            $table->dropIndex(['is_active', 'is_featured']);
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->dropIndex(['name']);
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->dropUnique(['order_number']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropColumn('order_number');
        });
    }
};
