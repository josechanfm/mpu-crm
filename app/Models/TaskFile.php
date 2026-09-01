<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'file_path',
        'filename',
        'original_name',
        'mime_type',
        'file_size',
        'file_type',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    protected $appends = ['file_url', 'formatted_size', 'icon'];

    // Relationships
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // Accessors
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('/' . $this->file_path) : null;
    }

    public function getFormattedSizeAttribute()
    {
        if (!$this->file_size) return null;

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIconAttribute()
    {
        $icons = [
            'image' => '📷',
            'pdf' => '📄',
            'word' => '📝',
            'excel' => '📊',
            'powerpoint' => '📽️',
            'zip' => '📦',
            'video' => '🎬',
            'audio' => '🎵',
            'other' => '📎',
        ];

        $mimeToIcon = [
            'image' => 'image',
            'application/pdf' => 'pdf',
            'application/msword' => 'word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word',
            'application/vnd.ms-excel' => 'excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel',
            'application/vnd.ms-powerpoint' => 'powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'powerpoint',
            'application/zip' => 'zip',
            'video/mp4' => 'video',
            'audio/mpeg' => 'audio',
        ];

        $iconKey = $mimeToIcon[$this->mime_type] ?? 'other';
        return $icons[$iconKey] ?? '📎';
    }

    // Helper methods
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    public function isDocument(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'application/');
    }

    // Upload method
    public static function uploadFile($file, Task $task, $isPrimary = false, $sortOrder = null)
    {
        // Store file with auto-generated name
        $path = $file->store('tasks/' . $task->id, 'public');
        $filename = basename($path);

        // Determine file type
        $mimeType = $file->getMimeType();
        $fileType = str_starts_with($mimeType, 'image/') ? 'image' : 'document';

        // Create file record
        return self::create([
            'task_id' => $task->id,
            'file_path' => $path,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $mimeType,
            'file_size' => $file->getSize(),
            'file_type' => $fileType,
            'is_primary' => $isPrimary,
            'sort_order' => $sortOrder ?? 0,
        ]);
    }

    // Delete file
    public function deleteFile()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
        return $this->delete();
    }

    // Boot method for cleanup
    protected static function booted()
    {
        static::deleting(function ($file) {
            if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
        });
    }
}