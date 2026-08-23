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
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();

        $table->string('vehicle_id')->unique();

        $table->string('plate_number')->unique();

        $table->string('make')->nullable();

        $table->string('model')->nullable();

        $table->string('color')->nullable();

        $table->string('motor_number')->nullable();

        $table->string('chassis_number')->nullable();

        $table->string('vehicle_type')->nullable();

        $table->date('registration_expiration')->nullable();

        $table->enum('status', [
            'active',
            'inactive'
        ])->default('active');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
