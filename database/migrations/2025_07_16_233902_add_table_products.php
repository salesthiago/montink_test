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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 2, 0);
            $table->timestamps();
        });
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('price', 2, 0)->nullable();
            $table->timestamps();
        });
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('variation_id')->nullable()->constrained('variations')->onDelete('cascade');;
            $table->integer('quantity');
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('cep')->nullable();
            $table->string('address')->nullable();
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount', 10, 2);
            $table->enum('type', ['percent', 'amount'])->default('percent');
            $table->timestamp('deadline_at');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('variations');
        Schema::dropIfExists('products');
    }
};
