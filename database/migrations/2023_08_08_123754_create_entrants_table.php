<?php

use App\Enums\Gender;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Enum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entrants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->date('dob');
            $table->enum('gender', array_column(Gender::cases(), 'value'));
            $table->string('reg_id')->nullable();

            $table->foreignIdFor(User::class);

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrants');
    }
};
