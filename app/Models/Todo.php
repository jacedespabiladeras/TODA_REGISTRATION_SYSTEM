<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'week_start',
        'week_end',
        'deadline',
        'status',
        'completed_at',
        'created_by',
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'deadline' => 'date',
        'completed_at' => 'datetime',
    ];

    /**
     * Assigned staff user.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Administrator who created the task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for pending tasks.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed tasks.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for tasks assigned to a specific user.
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Check if task is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Format week range as readable string.
     */
    public function getWeekRangeDisplayAttribute(): string
    {
        if ($this->week_start && $this->week_end) {
            return $this->week_start->format('M. d') . ' – ' . $this->week_end->format('M. d, Y');
        } elseif ($this->week_start) {
            return 'Week of ' . $this->week_start->format('M. d, Y');
        } elseif ($this->deadline) {
            return 'Due ' . $this->deadline->format('M. d, Y');
        }
        return 'Ongoing';
    }
}
