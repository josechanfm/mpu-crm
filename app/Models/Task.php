<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'user_id',
        'title',
        'description',
        'action',
        'result',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Optional: Add scopes for common queries
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class)->orderBy('sort_order');
    }

    // Get primary image
    public function primaryImage()
    {
        return $this->files()->where('is_primary', true)->first();
    }

    // Get all images
    public function images()
    {
        return $this->files()->where('file_type', 'image');
    }

    // Get all documents
    public function documents()
    {
        return $this->files()->where('file_type', 'document');
    }    
}
