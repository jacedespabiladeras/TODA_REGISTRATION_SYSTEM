<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Todo;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Franchise;
use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TodoAndDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $adminRole;
    protected $staffRole;
    protected $admin;
    protected $staff;
    protected $otherStaff;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['id' => 1], ['name' => 'admin']);
        $this->staffRole = Role::firstOrCreate(['id' => 2], ['name' => 'staff']);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role_id' => $this->adminRole->id,
        ]);

        $this->staff = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@test.com',
            'password' => Hash::make('password'),
            'role_id' => $this->staffRole->id,
        ]);

        $this->otherStaff = User::create([
            'name' => 'Other Staff',
            'email' => 'otherstaff@test.com',
            'password' => Hash::make('password'),
            'role_id' => $this->staffRole->id,
        ]);
    }

    public function test_admin_can_view_redesigned_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
        $response->assertSee('Total Drivers');
        $response->assertSee('Total Operators');
        $response->assertSee('Total Vehicles');
        $response->assertSee('Total Franchises');
        $response->assertSee("TO DO'S", false);
        $response->assertSee('Registration Activity');
    }

    public function test_staff_can_view_redesigned_dashboard()
    {
        $response = $this->actingAs($this->staff)->get('/staff');

        $response->assertStatus(200);
        $response->assertSee('Staff Dashboard');
        $response->assertSee('Total Drivers');
        $response->assertSee("MY TO DO'S", false);
        $response->assertDontSee('Add Task');
    }

    public function test_admin_can_create_todo_task()
    {
        $response = $this->actingAs($this->admin)->post('/todos', [
            'title' => 'Review new franchise applications',
            'description' => 'Check compliance with TODA routes',
            'assigned_to' => $this->staff->id,
            'week_start' => now()->startOfWeek()->toDateString(),
            'week_end' => now()->endOfWeek()->toDateString(),
            'deadline' => now()->addDays(2)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('todos', [
            'title' => 'Review new franchise applications',
            'assigned_to' => $this->staff->id,
            'created_by' => $this->admin->id,
            'status' => 'pending',
        ]);
    }

    public function test_staff_cannot_create_todo_task()
    {
        $response = $this->actingAs($this->staff)->post('/todos', [
            'title' => 'Staff trying to create task',
            'assigned_to' => $this->staff->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_todo_task()
    {
        $todo = Todo::create([
            'title' => 'Initial Title',
            'assigned_to' => $this->staff->id,
            'status' => 'pending',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->put("/todos/{$todo->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'assigned_to' => $this->otherStaff->id,
            'status' => 'completed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Updated Title',
            'assigned_to' => $this->otherStaff->id,
            'status' => 'completed',
        ]);
        $this->assertNotNull($todo->fresh()->completed_at);
    }

    public function test_staff_cannot_update_todo_task()
    {
        $todo = Todo::create([
            'title' => 'Original Task',
            'assigned_to' => $this->staff->id,
            'status' => 'pending',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->staff)->put("/todos/{$todo->id}", [
            'title' => 'Hacked Title',
            'status' => 'completed',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_todo_task()
    {
        $todo = Todo::create([
            'title' => 'Task to be deleted',
            'assigned_to' => $this->staff->id,
            'status' => 'pending',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->delete("/todos/{$todo->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    public function test_staff_cannot_delete_todo_task()
    {
        $todo = Todo::create([
            'title' => 'Important Task',
            'assigned_to' => $this->staff->id,
            'status' => 'pending',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->staff)->delete("/todos/{$todo->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
        ]);
    }

    public function test_assigned_staff_can_toggle_todo_completion()
    {
        $todo = Todo::create([
            'title' => 'Staff Task',
            'assigned_to' => $this->staff->id,
            'status' => 'pending',
            'created_by' => $this->admin->id,
        ]);

        // Toggle to completed
        $response = $this->actingAs($this->staff)->patch("/todos/{$todo->id}/toggle");
        $response->assertRedirect();
        $this->assertEquals('completed', $todo->fresh()->status);
        $this->assertNotNull($todo->fresh()->completed_at);

        // Toggle back to pending
        $response2 = $this->actingAs($this->staff)->patch("/todos/{$todo->id}/toggle");
        $response2->assertRedirect();
        $this->assertEquals('pending', $todo->fresh()->status);
        $this->assertNull($todo->fresh()->completed_at);
    }

    public function test_staff_cannot_toggle_other_staff_todo()
    {
        $todo = Todo::create([
            'title' => 'Other Staff Task',
            'assigned_to' => $this->otherStaff->id,
            'status' => 'pending',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->staff)->patch("/todos/{$todo->id}/toggle");
        $response->assertStatus(403);
    }
}
