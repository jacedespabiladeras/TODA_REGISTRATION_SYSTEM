<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected Role $adminRole;
    protected Role $staffRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['id' => 1], ['name' => 'admin']);
        $this->staffRole = Role::firstOrCreate(['id' => 2], ['name' => 'staff']);

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role_id' => $this->adminRole->id,
            'member_id' => 'MEM-2026-0001',
            'status' => 'active',
            'address' => 'Sorsogon City',
            'contact_number' => '09123456789',
        ]);

        $this->staff = User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@test.com',
            'role_id' => $this->staffRole->id,
            'member_id' => 'MEM-2026-0002',
            'status' => 'active',
            'address' => 'Piot, Sorsogon City',
            'contact_number' => '09223456789',
        ]);
    }

    public function test_admin_can_access_member_registration_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('members.index'));

        $response->assertStatus(200);
        $response->assertSee('Member Registration');
        $response->assertSee('Registered Members List');
        $response->assertSee('Admin User');
        $response->assertSee('Staff User');
    }

    public function test_staff_cannot_access_member_registration_page(): void
    {
        $response = $this->actingAs($this->staff)->get(route('members.index'));

        $response->assertStatus(403);
    }

    public function test_staff_cannot_create_member(): void
    {
        $response = $this->actingAs($this->staff)->post(route('members.store'), [
            'name' => 'Unauthorized Member',
            'email' => 'unauth@test.com',
            'role_id' => $this->staffRole->id,
            'status' => 'active',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_member_with_auto_generated_member_id(): void
    {
        $response = $this->actingAs($this->admin)->post(route('members.store'), [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@test.com',
            'contact_number' => '09333456789',
            'address' => 'Bibincahan, Sorsogon City',
            'role_id' => $this->staffRole->id,
            'status' => 'active',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('members.index'));
        $response->assertSessionHas('success', 'Member registered successfully.');

        $this->assertDatabaseHas('users', [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@test.com',
            'role_id' => $this->staffRole->id,
            'status' => 'active',
        ]);

        $user = User::where('email', 'juan@test.com')->first();
        $this->assertNotNull($user->member_id);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_admin_can_view_member_details(): void
    {
        $response = $this->actingAs($this->admin)->get(route('members.show', $this->staff->id));

        $response->assertStatus(200);
        $response->assertSee('Staff User');
        $response->assertSee($this->staff->member_id);

        // JSON / AJAX request
        $jsonResponse = $this->actingAs($this->admin)->getJson(route('members.show', $this->staff->id));
        $jsonResponse->assertStatus(200);
        $jsonResponse->assertJsonFragment([
            'name' => 'Staff User',
            'email' => 'staff@test.com',
        ]);
    }

    public function test_admin_can_update_member_information(): void
    {
        $response = $this->actingAs($this->admin)->put(route('members.update', $this->staff->id), [
            'name' => 'Updated Staff Name',
            'email' => 'updatedstaff@test.com',
            'member_id' => 'MEM-2026-0002',
            'contact_number' => '09999999999',
            'address' => 'Cabid-an, Sorsogon City',
            'role_id' => $this->staffRole->id,
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('members.index'));
        $response->assertSessionHas('success', 'Member information updated successfully.');

        $this->assertDatabaseHas('users', [
            'id' => $this->staff->id,
            'name' => 'Updated Staff Name',
            'email' => 'updatedstaff@test.com',
            'contact_number' => '09999999999',
            'address' => 'Cabid-an, Sorsogon City',
            'status' => 'inactive',
        ]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('members.destroy', $this->admin->id));

        $response->assertSessionHas('error', 'You cannot delete your own account while logged in.');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_cannot_delete_or_demote_the_last_admin(): void
    {
        // $this->admin is the only admin
        $secondAdmin = User::factory()->create([
            'name' => 'Second Admin',
            'email' => 'secondadmin@test.com',
            'role_id' => $this->adminRole->id,
            'status' => 'active',
        ]);

        // Attempting to demote admin when only one would remain
        // First delete second admin
        $this->actingAs($this->admin)->delete(route('members.destroy', $secondAdmin->id));
        $this->assertDatabaseMissing('users', ['id' => $secondAdmin->id]);

        // Now attempt to demote $this->admin to staff
        $response = $this->actingAs($this->admin)->put(route('members.update', $this->admin->id), [
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'member_id' => 'MEM-2026-0001',
            'role_id' => $this->staffRole->id, // Demote to staff
            'status' => 'active',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals($this->adminRole->id, $this->admin->fresh()->role_id);

        // Attempt to deactivate last admin
        $response2 = $this->actingAs($this->admin)->put(route('members.update', $this->admin->id), [
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'member_id' => 'MEM-2026-0001',
            'role_id' => $this->adminRole->id,
            'status' => 'inactive', // Deactivate
        ]);

        $response2->assertSessionHas('error');
        $this->assertEquals('active', $this->admin->fresh()->status);
    }

    public function test_admin_can_delete_staff_member(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('members.destroy', $this->staff->id));

        $response->assertRedirect(route('members.index'));
        $response->assertSessionHas('success', 'Member deleted successfully.');
        $this->assertDatabaseMissing('users', ['id' => $this->staff->id]);
    }

    public function test_member_search_and_status_filtering(): void
    {
        // Search by name
        $response = $this->actingAs($this->admin)->get(route('members.index', ['search' => 'Staff User']));
        $response->assertStatus(200);
        $response->assertSee('Staff User');

        // Filter by inactive (currently both are active)
        $responseInactive = $this->actingAs($this->admin)->get(route('members.index', ['status' => 'inactive']));
        $responseInactive->assertStatus(200);
        $responseInactive->assertSee('No member records found.');
    }
}
