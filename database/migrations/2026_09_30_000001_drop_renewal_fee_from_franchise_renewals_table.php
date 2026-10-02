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
        if (Schema::hasColumn('franchise_renewals', 'renewal_fee')) {
            Schema::table('franchise_renewals', function (Blueprint $table) {
                $table->dropColumn('renewal_fee');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('franchise_renewals', 'renewal_fee')) {
            Schema::table('franchise_renewals', function (Blueprint $table) {
                $table->decimal('renewal_fee', 10, 2)->nullable();
            });
        }
    }
};
