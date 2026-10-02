<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->orWhere('role_id', 1)->first();
        $staff = User::whereHas('role', fn($q) => $q->where('name', 'staff'))->orWhere('role_id', 2)->first();

        if ($admin && $staff) {
            $today = now();
            $weekStart = $today->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
            $weekEnd = $today->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();

            if (Todo::count() === 0) {
                Todo::create([
                    'title' => 'Verify driver registration records',
                    'description' => 'Review and cross-check newly submitted driver licenses and TODA clearance records.',
                    'assigned_to' => $staff->id,
                    'week_start' => $weekStart,
                    'week_end' => $weekEnd,
                    'deadline' => $today->copy()->addDays(3)->toDateString(),
                    'status' => 'pending',
                    'created_by' => $admin->id,
                ]);

                Todo::create([
                    'title' => 'Update vehicle registration records',
                    'description' => 'Ensure plate numbers and motor numbers match LTO registration files.',
                    'assigned_to' => $staff->id,
                    'week_start' => $weekStart,
                    'week_end' => $weekEnd,
                    'deadline' => $today->copy()->addDays(4)->toDateString(),
                    'status' => 'completed',
                    'completed_at' => now()->subDay(),
                    'created_by' => $admin->id,
                ]);

                Todo::create([
                    'title' => 'Process expiring franchise renewals',
                    'description' => 'Contact operators with franchises expiring within 30 days and assist in renewal submission.',
                    'assigned_to' => $staff->id,
                    'week_start' => $weekStart,
                    'week_end' => $weekEnd,
                    'deadline' => $today->copy()->addDays(5)->toDateString(),
                    'status' => 'pending',
                    'created_by' => $admin->id,
                ]);
            }
        }
    }
}
