<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Driver;
use App\Models\Operator;
use App\Models\Vehicle;
use App\Models\Franchise;
use App\Models\FranchiseRenewal;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminRole = Role::create(['id' => 1, 'name' => 'admin']);
    $this->staffRole = Role::create(['id' => 2, 'name' => 'staff']);

    $this->adminUser = User::create([
        'name' => 'Admin User',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'role_id' => $this->adminRole->id,
    ]);

    $this->staffUser = User::create([
        'name' => 'Staff User',
        'email' => 'staff@test.com',
        'password' => bcrypt('password'),
        'role_id' => $this->staffRole->id,
    ]);

    $this->driver = Driver::create([
        'driver_id' => 'DRV-2026-0001',
        'first_name' => 'Pedro',
        'last_name' => 'Penduko',
        'address' => 'Bibincahan, Sorsogon City',
        'contact_number' => '09223456789',
        'license_number' => 'N02-23-456789',
        'status' => 'active',
    ]);

    $this->operator = Operator::create([
        'operator_id' => 'OPR-2026-0001',
        'first_name' => 'Juan',
        'last_name' => 'Dela Cruz',
        'address' => 'Sorsogon City',
        'contact_number' => '09123456789',
        'status' => 'active',
    ]);

    $this->vehicle = Vehicle::create([
        'vehicle_id' => 'VEH-2026-0001',
        'plate_number' => '123-ABC',
        'make' => 'Kawasaki',
        'model' => 'Barako 175',
        'color' => 'Black',
        'status' => 'active',
        'driver_id' => $this->driver->id,
        'operator_id' => $this->operator->id,
    ]);
});

test('staff and admin can view franchise management page', function () {
    $this->actingAs($this->staffUser)
        ->get(route('franchises.index'))
        ->assertStatus(200)
        ->assertSee('Franchise Management')
        ->assertSee('Register New Franchise');

    $this->actingAs($this->adminUser)
        ->get(route('franchises.index'))
        ->assertStatus(200);
});

test('staff and admin can register a new franchise', function () {
    $franchiseData = [
        'franchise_number' => 'FR-2026-0001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->toDateString(),
        'expiration_date' => now()->addYear()->toDateString(),
        'status' => 'active',
    ];

    $response = $this->actingAs($this->staffUser)
        ->post(route('franchises.store'), $franchiseData);

    $response->assertRedirect(route('franchises.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('franchises', [
        'franchise_number' => 'FR-2026-0001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
    ]);
});

test('franchise registration validates required fields and unique number', function () {
    Franchise::create([
        'franchise_number' => 'FR-2026-0001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->toDateString(),
        'expiration_date' => now()->addYear()->toDateString(),
        'status' => 'active',
    ]);

    // Attempt duplicate franchise number
    $response = $this->actingAs($this->staffUser)
        ->post(route('franchises.store'), [
            'franchise_number' => 'FR-2026-0001',
            'operator_id' => $this->operator->id,
            'vehicle_id' => $this->vehicle->id,
            'franchise_date' => now()->toDateString(),
            'expiration_date' => now()->addYear()->toDateString(),
            'status' => 'active',
        ]);

    $response->assertSessionHasErrors(['franchise_number']);
});

test('dynamic franchise status accurately reflects expiration dates', function () {
    // Active franchise (> 30 days)
    $activeFranchise = Franchise::create([
        'franchise_number' => 'FR-2026-0001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->subMonths(6)->toDateString(),
        'expiration_date' => now()->addDays(60)->toDateString(),
        'status' => 'active',
    ]);
    expect($activeFranchise->calculated_status)->toBe('Active');

    // Expiring franchise (within 30 days)
    $expiringFranchise = Franchise::create([
        'franchise_number' => 'FR-2026-0002',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->subMonths(11)->toDateString(),
        'expiration_date' => now()->addDays(15)->toDateString(),
        'status' => 'active',
    ]);
    expect($expiringFranchise->calculated_status)->toBe('Expiring');

    // Inactive / Expired franchise (past expiration date)
    $expiredFranchise = Franchise::create([
        'franchise_number' => 'FR-2026-0003',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->subYears(1)->toDateString(),
        'expiration_date' => now()->subDays(5)->toDateString(),
        'status' => 'active',
    ]);
    expect($expiredFranchise->calculated_status)->toBe('Inactive');
});

test('franchise details page displays profile, operator, vehicle, driver, and renewal history', function () {
    $franchise = Franchise::create([
        'franchise_number' => 'FR-2026-0001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->subYears(2)->toDateString(),
        'expiration_date' => now()->addMonths(6)->toDateString(),
        'status' => 'active',
    ]);

    // Create a renewal history record
    FranchiseRenewal::create([
        'franchise_id' => $franchise->id,
        'previous_expiration_date' => now()->subYear()->toDateString(),
        'new_expiration_date' => now()->addMonths(6)->toDateString(),
        'renewal_date' => now()->subYear()->addDays(5)->toDateString(),
        'reference_number' => 'OR-2025-11111',
        'renewal_fee' => 500.00,
        'remarks' => 'First renewal',
        'processed_by' => $this->staffUser->id,
    ]);

    $this->actingAs($this->staffUser)
        ->get(route('franchises.show', $franchise->id))
        ->assertStatus(200)
        ->assertSee('FR-2026-0001')
        ->assertSee('Juan Dela Cruz')
        ->assertSee('123-ABC')
        ->assertSee('Pedro Penduko')
        ->assertSee('OR-2025-11111')
        ->assertSee('Staff User');
});

test('franchise can be edited and updated', function () {
    $franchise = Franchise::create([
        'franchise_number' => 'FR-2026-0001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->toDateString(),
        'expiration_date' => now()->addYear()->toDateString(),
        'status' => 'active',
    ]);

    $response = $this->actingAs($this->staffUser)
        ->put(route('franchises.update', $franchise->id), [
            'franchise_number' => 'FR-2026-0001-MOD',
            'operator_id' => $this->operator->id,
            'vehicle_id' => $this->vehicle->id,
            'franchise_date' => now()->toDateString(),
            'expiration_date' => now()->addYear()->toDateString(),
            'status' => 'active',
        ]);

    $response->assertRedirect(route('franchises.show', $franchise->id));
    $response->assertSessionHas('success');

    $franchise->refresh();
    expect($franchise->franchise_number)->toBe('FR-2026-0001-MOD');
});

test('franchise search and filters work properly', function () {
    Franchise::create([
        'franchise_number' => 'FR-ALPHA-001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->toDateString(),
        'expiration_date' => now()->addMonths(6)->toDateString(),
        'status' => 'active',
    ]);

    Franchise::create([
        'franchise_number' => 'FR-BETA-002',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->subYear()->toDateString(),
        'expiration_date' => now()->subMonth()->toDateString(),
        'status' => 'active',
    ]);

    // Search by franchise number
    $this->actingAs($this->staffUser)
        ->get(route('franchises.index', ['search' => 'ALPHA']))
        ->assertStatus(200)
        ->assertSee('FR-ALPHA-001')
        ->assertDontSee('FR-BETA-002');

    // Filter by Active status
    $this->actingAs($this->staffUser)
        ->get(route('franchises.index', ['status' => 'active']))
        ->assertStatus(200)
        ->assertSee('FR-ALPHA-001')
        ->assertDontSee('FR-BETA-002');

    // Filter by Inactive status
    $this->actingAs($this->staffUser)
        ->get(route('franchises.index', ['status' => 'inactive']))
        ->assertStatus(200)
        ->assertSee('FR-BETA-002')
        ->assertDontSee('FR-ALPHA-001');
});

test('franchise renewal updates expiration, preserves history and logs processor', function () {
    $initialExpiration = now()->addDays(10)->toDateString();

    $franchise = Franchise::create([
        'franchise_number' => 'FR-2026-0001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->subYear()->toDateString(),
        'expiration_date' => $initialExpiration,
        'status' => 'active',
    ]);

    $newExpiration = now()->addYear()->toDateString();
    $renewalDate = now()->toDateString();

    $response = $this->actingAs($this->staffUser)
        ->post(route('franchises.renew.process', $franchise->id), [
            'renewal_date' => $renewalDate,
            'new_expiration_date' => $newExpiration,
            'reference_number' => 'OR-2026-99999',
            'renewal_fee' => 750.50,
            'remarks' => 'Annual registration renewal paid in full.',
        ]);

    $response->assertRedirect(route('franchises.show', $franchise->id));
    $response->assertSessionHas('success');

    // Check that Franchise expiration date was updated
    $franchise->refresh();
    expect($franchise->expiration_date->toDateString())->toBe($newExpiration);
    expect($franchise->status)->toBe('active');

    // Check that Renewal History record was created and attributes match
    $renewal = FranchiseRenewal::first();
    expect($renewal)->not->toBeNull();
    expect($renewal->franchise_id)->toBe($franchise->id);
    expect($renewal->previous_expiration_date->toDateString())->toBe($initialExpiration);
    expect($renewal->new_expiration_date->toDateString())->toBe($newExpiration);
    expect($renewal->renewal_date->toDateString())->toBe($renewalDate);
    expect($renewal->reference_number)->toBe('OR-2026-99999');
    expect((float) $renewal->renewal_fee)->toBe(750.50);
    expect($renewal->processed_by)->toBe($this->staffUser->id);

    expect($franchise->renewals->count())->toBe(1);
    expect($franchise->renewals->first()->processedBy->id)->toBe($this->staffUser->id);
});

test('franchise renewal hub displays due franchises and audit logs', function () {
    $dueFranchise = Franchise::create([
        'franchise_number' => 'FR-DUE-001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->subYear()->toDateString(),
        'expiration_date' => now()->addDays(5)->toDateString(),
        'status' => 'active',
    ]);

    FranchiseRenewal::create([
        'franchise_id' => $dueFranchise->id,
        'previous_expiration_date' => now()->subYear()->toDateString(),
        'new_expiration_date' => now()->addDays(5)->toDateString(),
        'renewal_date' => now()->toDateString(),
        'reference_number' => 'OR-LOG-8888',
        'renewal_fee' => 600.00,
        'processed_by' => $this->staffUser->id,
    ]);

    $this->actingAs($this->staffUser)
        ->get(route('renewals.index'))
        ->assertStatus(200)
        ->assertSee('Franchise Renewal Management')
        ->assertSee('FR-DUE-001')
        ->assertSee('OR-LOG-8888');
});

test('staff cannot delete franchise but admin can delete franchise', function () {
    $franchise = Franchise::create([
        'franchise_number' => 'FR-2026-0001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->toDateString(),
        'expiration_date' => now()->addYear()->toDateString(),
        'status' => 'active',
    ]);

    // Staff tries to delete -> 403 Forbidden
    $this->actingAs($this->staffUser)
        ->delete(route('franchises.destroy', $franchise->id))
        ->assertStatus(403);

    $this->assertDatabaseHas('franchises', ['id' => $franchise->id]);

    // Admin tries to delete -> Success
    $this->actingAs($this->adminUser)
        ->delete(route('franchises.destroy', $franchise->id))
        ->assertRedirect(route('franchises.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('franchises', ['id' => $franchise->id]);
});

test('dashboard summary accurately reflects franchise counts and expiration statuses', function () {
    // 1 Active
    Franchise::create([
        'franchise_number' => 'FR-ACT-001',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->toDateString(),
        'expiration_date' => now()->addMonths(6)->toDateString(),
        'status' => 'active',
    ]);

    // 1 Expiring (10 days)
    Franchise::create([
        'franchise_number' => 'FR-EXP-002',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->subYear()->toDateString(),
        'expiration_date' => now()->addDays(10)->toDateString(),
        'status' => 'active',
    ]);

    // 1 Inactive (expired 10 days ago)
    Franchise::create([
        'franchise_number' => 'FR-INA-003',
        'operator_id' => $this->operator->id,
        'vehicle_id' => $this->vehicle->id,
        'franchise_date' => now()->subYears(2)->toDateString(),
        'expiration_date' => now()->subDays(10)->toDateString(),
        'status' => 'active',
    ]);

    $response = $this->actingAs($this->adminUser)
        ->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $response->assertViewHas('stats', function ($stats) {
        return $stats['franchises']['total'] === 3
            && $stats['franchises']['active'] === 1
            && $stats['franchises']['expiring'] === 1
            && $stats['franchises']['inactive'] === 1;
    });
});
