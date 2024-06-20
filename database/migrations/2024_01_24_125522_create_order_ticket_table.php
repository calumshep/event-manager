<?php

use App\Models\Order;
use App\Models\TicketType;
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
        Schema::create('order_ticket', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Order::class);
            $table->foreignIdFor(TicketType::class);

            $table->string('ticket_holder_name');
            $table->json('metadata');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_ticket');
    }
};
