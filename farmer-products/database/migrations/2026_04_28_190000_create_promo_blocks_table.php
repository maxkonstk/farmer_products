<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_blocks', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('eyebrow', 80)->nullable();
            $table->string('badge', 80)->nullable();
            $table->string('title');
            $table->text('body');
            $table->string('cta_label', 80)->nullable();
            $table->string('cta_url')->nullable();
            $table->string('image')->nullable();
            $table->string('theme', 30)->default('wheat');
            $table->string('placement', 30)->default('home');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('collection_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_blocks');
    }
};
