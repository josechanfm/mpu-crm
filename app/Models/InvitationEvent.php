<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class InvitationEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invitation_events';

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'location',
        'venue',
        'pdf_template',
        'email_template',
        'available_placeholders',
        'is_active',
        'max_guests_per_invitation',
        'rsvp_deadline',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'rsvp_deadline' => 'datetime',
        'available_placeholders' => 'array',
        'is_active' => 'boolean',
        'max_guests_per_invitation' => 'integer',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate a unique ID using Str::random or any other method
            $model->uuid =  (string) Str::uuid();
        });
    }

    public function guests()
    {
        return $this->hasMany(InvitationGuest::class, 'invitation_event_id');
    }

    public function pendingRsvpCount()
    {
        return $this->guests()->where('rsvp_status', 'pending')->count();
    }

    public function attendingCount()
    {
        return $this->guests()->where('rsvp_status', 'yes')->sum('guests_count');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDefaultPlaceholders(): array
    {
        return array_merge([
            'event_name',
            'event_date',
            'event_location',
            'rsvp_link',
            'qr_code_image',
            'guest_name',
            'guest_organization',
        ], $this->available_placeholders ?? []);
    }
}