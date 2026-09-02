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
        Schema::create('franchise_renewals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('franchise_id')
                ->constrained('franchises')
                ->cascadeOnDelete();

            $table->date('previous_expiration_date');

            $table->date('new_expiration_date');

            $table->date('renewal_date');

            $table->string('reference_number')->nullable();

            $table->decimal('renewal_fee', 10, 2)->nullable();

            $table->text('remarks')->nullable();

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_renewals');
    }
};
