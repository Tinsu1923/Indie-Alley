<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->decimal('price', 8, 2);
            $table->date('release_date')->nullable();
            $table->decimal('discount', 5, 2)->nullable();
            $table->enum('stock_status', ['available', 'sold out']);
            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('seller')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: If you want to explicitly drop the foreign key first
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['seller_id']); // Drop the foreign key constraint
        });

        Schema::dropIfExists('products'); // Drop the products table
    }
};