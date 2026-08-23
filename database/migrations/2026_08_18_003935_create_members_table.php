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
    Schema::create('members', function (Blueprint $table) {
        $table->id();

        $table->string('member_id')->unique();

        $table->string('first_name');
        $table->string('middle_name')->nullable();
        $table->string('last_name');

        $table->string('address');

        $table->string('contact_number');

        $table->date('membership_date');

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
        Schema::dropIfExists('members');
    }
};
