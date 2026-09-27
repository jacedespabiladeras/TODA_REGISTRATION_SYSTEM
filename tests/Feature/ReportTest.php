<?php

use App\Models\User;
use App\Models\Role;
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
});

test('admin and staff can access reports index page', function () {
    $this->actingAs($this->adminUser)
        ->get(route('reports.index'))
        ->assertStatus(200);

    $this->actingAs($this->staffUser)
        ->get(route('reports.index'))
        ->assertStatus(200);
});

test('admin can access reports output page', function () {
    $this->actingAs($this->adminUser)
        ->get(route('reports.output'))
        ->assertStatus(200);
});

test('staff cannot access admin reports output page', function () {
    $this->actingAs($this->staffUser)
        ->get(route('reports.output'))
        ->assertStatus(403);
});

test('admin and staff can access tracking page', function () {
    $this->actingAs($this->adminUser)
        ->get(route('tracking.index'))
        ->assertStatus(200);

    $this->actingAs($this->staffUser)
        ->get(route('tracking.index'))
        ->assertStatus(200);
});
