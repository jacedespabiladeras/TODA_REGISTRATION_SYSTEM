<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('member_id')->nullable()->unique()->after('id');
            $table->string('contact_number', 30)->nullable()->after('email');
            $table->string('address')->nullable()->after('contact_number');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('address');
        });

        // Backfill existing users with member_id and status
        $users = DB::table('users')->orderBy('id')->get();
        $counter = 1;
        $year = now()->year;

        foreach ($users as $user) {
            $memberId = sprintf('MEM-%d-%04d', $year, $counter++);
            DB::table('users')->where('id', $user->id)->update([
                'member_id' => $memberId,
                'status' => 'active',
                'address' => $user->address ?? 'Sorsogon City',
                'contact_number' => $user->contact_number ?? ('0912' . str_pad((string)$user->id, 7, '0', STR_PAD_LEFT)),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['member_id', 'contact_number', 'address', 'status']);
        });
    }
};
