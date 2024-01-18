<?php

use App\Models\Ticket;
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
        Schema::create('user_ticket', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->json('details')->nullable();

            $table->string('buyer_email');
            $table->foreignIdFor(Ticket::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ticket');
    }
};
