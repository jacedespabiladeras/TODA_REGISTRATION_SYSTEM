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
    Schema::create('franchises', function (Blueprint $table) {
        $table->id();

        $table->string('franchise_number')->unique();

        $table->foreignId('operator_id')
            ->constrained('operators')
            ->cascadeOnDelete();

        $table->foreignId('vehicle_id')
            ->constrained('vehicles')
            ->cascadeOnDelete();

        $table->date('franchise_date');

        $table->date('expiration_date');

        $table->enum('status', [
            'active',
            'expired',
            'cancelled'
        ])->default('active');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchises');
    }
};
