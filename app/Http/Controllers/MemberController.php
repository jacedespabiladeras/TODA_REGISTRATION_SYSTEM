<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $selectedRole = $request->query('role');

        $query = User::with('role');

        // Search by Member ID, Name, Email, Contact Number, Address
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by Role
        if ($selectedRole && $selectedRole !== 'all') {
            $query->whereHas('role', function ($q) use ($selectedRole) {
                $q->where('name', $selectedRole);
            });
        }

        $members = $query->latest('id')->paginate(10)->withQueryString();

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
        ];

        $roles = Role::orderBy('id')->get();

        return view('members.index', compact('members', 'stats', 'roles', 'search', 'status', 'selectedRole'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        $roles = Role::orderBy('id')->get();
        return view('members.create', compact('roles'));
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'member_id' => 'nullable|string|max:50|unique:users,member_id',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Auto-generate member_id if not provided: MEM-YYYY-XXXX
        $memberId = $request->member_id;
        if (empty($memberId)) {
            $year = now()->year;
            $prefix = "MEM-{$year}-";
            $latest = User::where('member_id', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($latest && preg_match('/-(\d+)$/', $latest->member_id, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = User::count() + 1;
            }
            $memberId = $prefix . str_pad((string)$nextNumber, 4, '0', STR_PAD_LEFT);
        }

        // Handle profile photo upload
        $photoPath = null;
        if ($request->hasFile('profile_picture')) {
            $photoPath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        User::create([
            'member_id' => $memberId,
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'role_id' => $request->role_id,
            'status' => $request->status,
            'password' => Hash::make($request->password),
            'profile_picture' => $photoPath,
            'theme' => 'light',
        ]);

        return redirect()->route('members.index')->with('success', 'Member registered successfully.');
    }

    /**
     * Display the specified member.
     */
    public function show(Request $request, User $member)
    {
        $member->load('role');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id' => $member->id,
                'member_id' => $member->member_id ?? ('MEM-' . str_pad((string)$member->id, 4, '0', STR_PAD_LEFT)),
                'name' => $member->name,
                'email' => $member->email,
                'contact_number' => $member->contact_number ?? 'N/A',
                'address' => $member->address ?? 'N/A',
                'role_id' => $member->role_id,
                'role_name' => $member->role ? ucfirst($member->role->name) : 'User',
                'status' => $member->status ?? 'active',
                'profile_picture_url' => $member->profile_picture ? asset('storage/' . $member->profile_picture) : null,
                'membership_date' => $member->created_at ? $member->created_at->format('M d, Y') : 'N/A',
                'updated_at_formatted' => $member->updated_at ? $member->updated_at->format('M d, Y h:i A') : 'N/A',
            ]);
        }

        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(Request $request, User $member)
    {
        $member->load('role');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id' => $member->id,
                'member_id' => $member->member_id ?? ('MEM-' . str_pad((string)$member->id, 4, '0', STR_PAD_LEFT)),
                'name' => $member->name,
                'email' => $member->email,
                'contact_number' => $member->contact_number ?? '',
                'address' => $member->address ?? '',
                'role_id' => $member->role_id,
                'status' => $member->status ?? 'active',
                'profile_picture_url' => $member->profile_picture ? asset('storage/' . $member->profile_picture) : null,
            ]);
        }

        $roles = Role::orderBy('id')->get();
        return view('members.edit', compact('member', 'roles'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, User $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $member->id,
            'member_id' => 'required|string|max:50|unique:users,member_id,' . $member->id,
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Protection: Prevent demoting or deactivating the last active Admin
        if ($member->role?->name === 'admin') {
            $activeAdminCount = User::whereHas('role', function ($q) {
                $q->where('name', 'admin');
            })->where('status', 'active')->count();

            if ($activeAdminCount <= 1) {
                $targetRole = Role::find($request->role_id);
                if ($targetRole && $targetRole->name !== 'admin') {
                    return back()->with('error', 'Cannot change role to Staff. At least one active Administrator account must remain in the system.');
                }
                if ($request->status === 'inactive') {
                    return back()->with('error', 'Cannot deactivate this account. At least one active Administrator account must remain in the system.');
                }
            }
        }

        $member->name = $request->name;
        $member->email = $request->email;
        $member->member_id = $request->member_id;
        $member->contact_number = $request->contact_number;
        $member->address = $request->address;
        $member->role_id = $request->role_id;
        $member->status = $request->status;

        // Update password if provided
        if ($request->filled('password')) {
            $member->password = Hash::make($request->password);
        }

        // Handle profile picture update
        if ($request->hasFile('profile_picture')) {
            if ($member->profile_picture && Storage::disk('public')->exists($member->profile_picture)) {
                Storage::disk('public')->delete($member->profile_picture);
            }
            $member->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $member->save();

        return redirect()->route('members.index')->with('success', 'Member information updated successfully.');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(User $member)
    {
        // Protection: Cannot delete own logged-in account
        if (auth()->id() === $member->id) {
            return back()->with('error', 'You cannot delete your own account while logged in.');
        }

        // Protection: Cannot delete last Admin account
        if ($member->role?->name === 'admin') {
            $adminCount = User::whereHas('role', function ($q) {
                $q->where('name', 'admin');
            })->count();

            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot delete this account. At least one Administrator account must remain in the system.');
            }
        }

        try {
            if ($member->profile_picture && Storage::disk('public')->exists($member->profile_picture)) {
                Storage::disk('public')->delete($member->profile_picture);
            }

            $member->delete();

            return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the member record: ' . $e->getMessage());
        }
    }
}
