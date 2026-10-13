<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 5, 2)->default(0.00);
            $table->integer('stock')->default(0);
            $table->string('sku')->unique()->nullable();
            $table->json('images')->nullable();
            $table->enum('availability', ['in_stock', 'out_of_stock', 'pre_order'])->default('in_stock');
            $table->json('tags')->nullable();
            $table->json('specifications')->nullable();
            $table->enum('status', ['active', 'draft', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
