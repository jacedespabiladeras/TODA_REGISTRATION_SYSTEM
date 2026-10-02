<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TodoController extends Controller
{
    /**
     * Store a newly created task (Admin only).
     */
    public function store(Request $request)
    {
        if (auth()->user()->role?->name !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can create tasks.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
            'week_start' => 'nullable|date',
            'week_end' => 'nullable|date|after_or_equal:week_start',
            'deadline' => 'nullable|date',
        ]);

        // If week_start and week_end are not provided, default to current week
        if (empty($validated['week_start'])) {
            $validated['week_start'] = now()->startOfWeek(Carbon::MONDAY)->toDateString();
        }
        if (empty($validated['week_end'])) {
            $validated['week_end'] = now()->endOfWeek(Carbon::SUNDAY)->toDateString();
        }

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'pending';

        Todo::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Task created successfully!']);
        }

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    /**
     * Update an existing task (Admin only).
     */
    public function update(Request $request, Todo $todo)
    {
        if (auth()->user()->role?->name !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can edit tasks.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
            'week_start' => 'nullable|date',
            'week_end' => 'nullable|date|after_or_equal:week_start',
            'deadline' => 'nullable|date',
            'status' => 'required|in:pending,completed',
        ]);

        if ($validated['status'] === 'completed' && $todo->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] === 'pending') {
            $validated['completed_at'] = null;
        }

        $todo->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Task updated successfully!']);
        }

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task (Admin only).
     */
    public function destroy(Request $request, Todo $todo)
    {
        if (auth()->user()->role?->name !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can delete tasks.');
        }

        $todo->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Task deleted successfully!']);
        }

        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    /**
     * Toggle completion status of a task (Admin or Assigned Staff).
     */
    public function toggleComplete(Request $request, Todo $todo)
    {
        $user = auth()->user();
        $isAdmin = $user->role?->name === 'admin';
        $isAssignedStaff = ($user->role?->name === 'staff') && ($todo->assigned_to === $user->id);

        if (!$isAdmin && !$isAssignedStaff) {
            abort(403, 'Unauthorized action. You can only update tasks assigned to you.');
        }

        $isCurrentlyCompleted = $todo->status === 'completed';
        $newStatus = $isCurrentlyCompleted ? 'pending' : 'completed';
        $completedAt = $isCurrentlyCompleted ? null : now();

        $todo->update([
            'status' => $newStatus,
            'completed_at' => $completedAt,
        ]);

        $message = $newStatus === 'completed'
            ? 'Task marked as completed! Great job.'
            : 'Task marked as pending.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'completed_at' => $completedAt ? $completedAt->format('M. d, Y h:i A') : null,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
