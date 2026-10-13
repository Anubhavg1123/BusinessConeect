<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->tinyInteger('rating'); // 1-5
            $table->text('review_text');
            $table->json('images')->nullable();
            $table->unsignedInteger('likes_count')->default(0);
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('approved');
            $table->boolean('reported')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
