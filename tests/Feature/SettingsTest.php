<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $adminRole;
    protected $staffRole;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->adminRole = Role::firstOrCreate(['id' => 1], ['name' => 'admin']);
        $this->staffRole = Role::firstOrCreate(['id' => 2], ['name' => 'staff']);
    }

    public function test_guest_is_redirected_to_login_when_accessing_settings()
    {
        $response = $this->get('/settings');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_settings_page()
    {
        $admin = User::create([
            'name' => 'Admin Tester',
            'email' => 'admin_test@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->adminRole->id,
            'theme' => 'light'
        ]);

        $response = $this->actingAs($admin)->get('/settings');
        $response->assertStatus(200);
        $response->assertSee('Settings');
        $response->assertSee('Profile');
        $response->assertSee('Preferences');
    }

    public function test_staff_can_view_settings_page()
    {
        $staff = User::create([
            'name' => 'Staff Tester',
            'email' => 'staff_test@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->staffRole->id,
            'theme' => 'light'
        ]);

        $response = $this->actingAs($staff)->get('/settings');
        $response->assertStatus(200);
        $response->assertSee('Settings');
    }

    public function test_user_can_update_own_name_and_email()
    {
        $user = User::create([
            'name' => 'Original Name',
            'email' => 'orig_user@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->staffRole->id,
            'theme' => 'light'
        ]);

        $response = $this->actingAs($user)->patch('/settings/profile', [
            'name' => 'Updated Name',
            'email' => 'updated_email@example.com',
        ]);

        $response->assertRedirect(route('settings', ['tab' => 'profile']));
        $response->assertSessionHas('status', 'Profile updated successfully.');

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated_email@example.com', $user->email);
    }

    public function test_user_can_upload_and_replace_profile_photo()
    {
        $user = User::create([
            'name' => 'Photo User',
            'email' => 'photo_user@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->adminRole->id,
            'theme' => 'light'
        ]);

        $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        $response = $this->actingAs($user)->patch('/settings/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'profile_picture' => $file,
        ]);

        $response->assertRedirect(route('settings', ['tab' => 'profile']));
        $user->refresh();

        $this->assertNotNull($user->profile_picture);
        Storage::disk('public')->assertExists($user->profile_picture);

        $oldPhoto = $user->profile_picture;

        // Replace with new photo
        $newFile = UploadedFile::fake()->image('avatar_new.png', 300, 300);
        $this->actingAs($user)->patch('/settings/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'profile_picture' => $newFile,
        ]);

        $user->refresh();
        $this->assertNotEquals($oldPhoto, $user->profile_picture);
        Storage::disk('public')->assertMissing($oldPhoto);
        Storage::disk('public')->assertExists($user->profile_picture);
    }

    public function test_user_can_remove_profile_photo()
    {
        $user = User::create([
            'name' => 'Remove Photo User',
            'email' => 'remove_photo@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->adminRole->id,
            'theme' => 'light'
        ]);

        $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);
        $path = $file->store('profile-pictures', 'public');
        $user->profile_picture = $path;
        $user->save();

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($user)->delete('/settings/profile/photo');
        $response->assertRedirect(route('settings', ['tab' => 'profile']));
        $response->assertSessionHas('status', 'Profile photo removed successfully.');

        $user->refresh();
        $this->assertNull($user->profile_picture);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_user_cannot_modify_role_via_profile_update()
    {
        $user = User::create([
            'name' => 'Staff Tamper',
            'email' => 'staff_tamper@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->staffRole->id,
            'theme' => 'light'
        ]);

        $this->actingAs($user)->patch('/settings/profile', [
            'name' => 'Staff Tamper',
            'email' => 'staff_tamper@example.com',
            'role_id' => $this->adminRole->id, // Attempted promotion
        ]);

        $user->refresh();
        $this->assertEquals($this->staffRole->id, $user->role_id);
    }

    public function test_password_cannot_be_changed_with_invalid_current_password()
    {
        $user = User::create([
            'name' => 'Pwd User',
            'email' => 'pwd_test@example.com',
            'password' => Hash::make('correct_password'),
            'role_id' => $this->staffRole->id,
            'theme' => 'light'
        ]);

        $response = $this->actingAs($user)->patch('/settings/password', [
            'current_password' => 'wrong_password',
            'password' => 'new_secure_password',
            'password_confirmation' => 'new_secure_password',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('correct_password', $user->fresh()->password));
    }

    public function test_password_can_be_changed_successfully()
    {
        $user = User::create([
            'name' => 'Pwd Success User',
            'email' => 'pwd_success@example.com',
            'password' => Hash::make('old_password123'),
            'role_id' => $this->adminRole->id,
            'theme' => 'light'
        ]);

        $response = $this->actingAs($user)->patch('/settings/password', [
            'current_password' => 'old_password123',
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
        ]);

        $response->assertRedirect(route('settings', ['tab' => 'profile']));
        $response->assertSessionHas('status', 'Password changed successfully.');

        $this->assertTrue(Hash::check('new_password123', $user->fresh()->password));
    }

    public function test_user_can_save_theme_preference_independently()
    {
        $admin = User::create([
            'name' => 'Admin Theme',
            'email' => 'admin_theme@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->adminRole->id,
            'theme' => 'light'
        ]);

        $staff = User::create([
            'name' => 'Staff Theme',
            'email' => 'staff_theme@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->staffRole->id,
            'theme' => 'light'
        ]);

        // Admin selects dark mode
        $response = $this->actingAs($admin)->patch('/settings/preferences', [
            'theme' => 'dark',
        ]);
        $response->assertRedirect(route('settings', ['tab' => 'preferences']));
        $response->assertSessionHas('status', 'Preferences saved successfully.');

        // Staff selects light mode
        $this->actingAs($staff)->patch('/settings/preferences', [
            'theme' => 'light',
        ]);

        $admin->refresh();
        $staff->refresh();

        $this->assertEquals('dark', $admin->theme);
        $this->assertEquals('light', $staff->theme);
    }
}
