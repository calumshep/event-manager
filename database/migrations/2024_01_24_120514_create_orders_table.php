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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Order details
            $table->string('checkout_id');
            $table->integer('total_amount');
            $table->boolean('paid')->default(0);

            // One-to-many polymorphic relationship
            // Orderable types: User/Guest
            $table->foreignId('orderable_id');
            $table->string('orderable_type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
