<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the settings view with profile and preferences.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $activeTab = $request->query('tab', 'profile');

        if (!in_array($activeTab, ['profile', 'preferences'])) {
            $activeTab = 'profile';
        }

        return view('settings.index', [
            'user' => $user,
            'activeTab' => $activeTab,
        ]);
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists in storage
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture in storage/app/public/profile-pictures
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->route('settings', ['tab' => 'profile'])->with('status', 'Profile updated successfully.');
    }

    /**
     * Remove the authenticated user's profile photo.
     */
    public function removePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->profile_picture = null;
        $user->save();

        return redirect()->route('settings', ['tab' => 'profile'])->with('status', 'Profile photo removed successfully.');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ], [
            'current_password.current_password' => 'The provided current password does not match our records.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The new password must be at least 8 characters.',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('settings', ['tab' => 'profile'])->with('status', 'Password changed successfully.');
    }

    /**
     * Update the authenticated user's system preferences (theme).
     */
    public function updatePreferences(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'theme' => [
                'required',
                'string',
                'in:light,dark',
            ],
        ]);

        $user = $request->user();
        $user->theme = $validated['theme'];
        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'theme' => $user->theme,
                'message' => 'Preferences saved successfully.',
            ]);
        }

        return redirect()->route('settings', ['tab' => 'preferences'])->with('status', 'Preferences saved successfully.');
    }
}
