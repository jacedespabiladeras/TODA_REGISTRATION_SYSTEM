<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Driver;
use App\Models\Operator;
use App\Models\Vehicle;
use App\Models\Franchise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks to safely truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clean tables (excluding users and roles)
        Driver::truncate();
        Franchise::truncate();
        Vehicle::truncate();
        Operator::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // -------------------------------------------------------------
        // SEED ROLES AND USERS
        // -------------------------------------------------------------
        Role::firstOrCreate(['id' => 1], ['name' => 'admin']);
        Role::firstOrCreate(['id' => 2], ['name' => 'staff']);

        $adminUser = User::firstOrNew(['email' => 'despabiladeras@gmail.com']);
        $adminUser->name = 'Jace';
        $adminUser->password = bcrypt('password');
        $adminUser->role_id = 1;
        $adminUser->save();

        $staffUser = User::firstOrNew(['email' => 'staff@gmail.com']);
        $staffUser->name = 'System Staff';
        $staffUser->password = bcrypt('password');
        $staffUser->role_id = 2;
        $staffUser->save();

        $today = now();

        // -------------------------------------------------------------
        // SEED DRIVERS
        // -------------------------------------------------------------

        // 1. Active Driver (No expiration warning)
        Driver::create([
            'driver_id' => 'DRV-2026-0001',
            'first_name' => 'Juan',
            'middle_name' => 'Reyes',
            'last_name' => 'Dela Cruz',
            'address' => 'Piot, Sorsogon City',
            'contact_number' => '09123456789',
            'license_number' => 'N01-12-345678',
            'license_expiration' => $today->copy()->addDays(45)->toDateString(),
            'status' => 'active',
        ]);

        // 2. Expiring Driver (Expiration within 30 days)
        Driver::create([
            'driver_id' => 'DRV-2026-0002',
            'first_name' => 'Pedro',
            'middle_name' => 'Santos',
            'last_name' => 'Penduko',
            'address' => 'Bibincahan, Sorsogon City',
            'contact_number' => '09223456789',
            'license_number' => 'N02-23-456789',
            'license_expiration' => $today->copy()->addDays(15)->toDateString(),
            'status' => 'active',
        ]);

        // 3. Inactive Driver (Expired license date)
        Driver::create([
            'driver_id' => 'DRV-2026-0003',
            'first_name' => 'Maria',
            'middle_name' => 'Alim',
            'last_name' => 'Clara',
            'address' => 'Talisan, Sorsogon City',
            'contact_number' => '09333456789',
            'license_number' => 'N03-34-567890',
            'license_expiration' => $today->copy()->subDays(5)->toDateString(),
            'status' => 'active',
        ]);

        // 4. Inactive Driver (Explicitly marked inactive, even with valid license)
        Driver::create([
            'driver_id' => 'DRV-2026-0004',
            'first_name' => 'Jose',
            'middle_name' => 'Mercado',
            'last_name' => 'Rizal',
            'address' => 'Salog, Sorsogon City',
            'contact_number' => '09443456789',
            'license_number' => 'N04-45-678901',
            'license_expiration' => $today->copy()->addDays(60)->toDateString(),
            'status' => 'inactive',
        ]);

        // 5. Active Driver (Null license expiration - no expiration warning)
        Driver::create([
            'driver_id' => 'DRV-2026-0005',
            'first_name' => 'Andres',
            'middle_name' => 'Castro',
            'last_name' => 'Bonifacio',
            'address' => 'Balogo, Sorsogon City',
            'contact_number' => '09553456789',
            'license_number' => 'N05-56-789012',
            'license_expiration' => null,
            'status' => 'active',
        ]);


        // -------------------------------------------------------------
        // SEED OPERATORS
        // -------------------------------------------------------------

        // 1. Active Operator 1
        $operator1 = Operator::create([
            'operator_id' => 'OPR-2026-0001',
            'first_name' => 'Emilio',
            'middle_name' => 'Famy',
            'last_name' => 'Aguinaldo',
            'address' => 'Bacon, Sorsogon City',
            'contact_number' => '09171234567',
            'email' => 'emilio@gmail.com',
            'status' => 'active',
        ]);

        // 2. Active Operator 2
        $operator2 = Operator::create([
            'operator_id' => 'OPR-2026-0002',
            'first_name' => 'Manuel',
            'middle_name' => 'Luis',
            'last_name' => 'Quezon',
            'address' => 'Pangpang, Sorsogon City',
            'contact_number' => '09181234567',
            'email' => 'manuel@gmail.com',
            'status' => 'active',
        ]);

        // 3. Inactive Operator
        Operator::create([
            'operator_id' => 'OPR-2026-0003',
            'first_name' => 'Apolinario',
            'middle_name' => 'Maranan',
            'last_name' => 'Mabini',
            'address' => 'Cabid-an, Sorsogon City',
            'contact_number' => '09191234567',
            'email' => 'apolinario@gmail.com',
            'status' => 'inactive',
        ]);


        // -------------------------------------------------------------
        // SEED VEHICLES
        // -------------------------------------------------------------

        $vehicle1 = Vehicle::create([
            'vehicle_id' => 'VEH-2026-0001',
            'plate_number' => '123-ABC',
            'make' => 'Kawasaki',
            'model' => 'Barako 175',
            'color' => 'Black',
            'status' => 'active',
        ]);

        $vehicle2 = Vehicle::create([
            'vehicle_id' => 'VEH-2026-0002',
            'plate_number' => '456-DEF',
            'make' => 'Honda',
            'model' => 'TMX 125',
            'color' => 'Red',
            'status' => 'active',
        ]);

        $vehicle3 = Vehicle::create([
            'vehicle_id' => 'VEH-2026-0003',
            'plate_number' => '789-GHI',
            'make' => 'Yamaha',
            'model' => 'YTX 125',
            'color' => 'Blue',
            'status' => 'active',
        ]);

        $vehicle4 = Vehicle::create([
            'vehicle_id' => 'VEH-2026-0004',
            'plate_number' => '012-JKL',
            'make' => 'Suzuki',
            'model' => 'GD110',
            'color' => 'Gray',
            'status' => 'active',
        ]);


        // -------------------------------------------------------------
        // SEED FRANCHISES
        // -------------------------------------------------------------

        // 1. Active Franchise (More than 30 days remaining)
        Franchise::create([
            'franchise_number' => 'FR-2026-0001',
            'operator_id' => $operator1->id,
            'vehicle_id' => $vehicle1->id,
            'franchise_date' => $today->copy()->subMonths(10)->toDateString(),
            'expiration_date' => $today->copy()->addDays(60)->toDateString(),
            'status' => 'active',
        ]);

        // 2. Expiring Franchise (Within 30 days remaining)
        Franchise::create([
            'franchise_number' => 'FR-2026-0002',
            'operator_id' => $operator2->id,
            'vehicle_id' => $vehicle2->id,
            'franchise_date' => $today->copy()->subMonths(11)->toDateString(),
            'expiration_date' => $today->copy()->addDays(20)->toDateString(),
            'status' => 'active',
        ]);

        // 3. Inactive Franchise (Expiration date passed, status is active)
        Franchise::create([
            'franchise_number' => 'FR-2026-0003',
            'operator_id' => $operator1->id,
            'vehicle_id' => $vehicle3->id,
            'franchise_date' => $today->copy()->subYears(1)->toDateString(),
            'expiration_date' => $today->copy()->subDays(5)->toDateString(),
            'status' => 'active',
        ]);

        // 4. Inactive Franchise (Explicitly cancelled)
        Franchise::create([
            'franchise_number' => 'FR-2026-0004',
            'operator_id' => $operator2->id,
            'vehicle_id' => $vehicle4->id,
            'franchise_date' => $today->copy()->subMonths(6)->toDateString(),
            'expiration_date' => $today->copy()->addDays(40)->toDateString(),
            'status' => 'cancelled',
        ]);
    }
}
