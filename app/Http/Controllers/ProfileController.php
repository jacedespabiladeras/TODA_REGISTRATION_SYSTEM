<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show profile settings
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


    /**
     * Update profile
     */
    public function update(Request $request)
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


        /*
        |--------------------------------------------------------------------------
        | Update name and email
        |--------------------------------------------------------------------------
        */

        $user->name = $validated['name'];
        $user->email = $validated['email'];


        /*
        |--------------------------------------------------------------------------
        | Profile Picture
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_picture')) {

            // Delete old profile picture
            if ($user->profile_picture) {
                Storage::disk('public')->delete(
                    $user->profile_picture
                );
            }

            // Store new picture
            $path = $request
                ->file('profile_picture')
                ->store('profile-pictures', 'public');

            $user->profile_picture = $path;
        }


        $user->save();


        return back()->with(
            'status',
            'Profile updated successfully.'
        );
    }


    /**
     * Change password
     */
    public function updatePassword(Request $request)
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
        ]);


        $request->user()->update([
            'password' => Hash::make(
                $request->password
            ),
        ]);


        return back()->with(
            'status',
            'Password changed successfully.'
        );
    }
}