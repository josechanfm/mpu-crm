<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class InvitationGuest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invitation_guests';

    protected $fillable = [
        'uuid',
        'invitation_event_id',
        'name',
        'organization',
        'email',
        'phone',
        'token',
        'invitation_sent_at',
        'invitation_opened_at',
        'qr_code_path',
        'rsvp_status',
        'responded_at',
        'guests_count',
        'dietary_needs',
        'response_notes',
        'custom_fields',
    ];

    protected $casts = [
        'invitation_sent_at' => 'datetime',
        'invitation_opened_at' => 'datetime',
        'responded_at' => 'datetime',
        'guests_count' => 'integer',
        'custom_fields' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate a unique ID using Str::random or any other method
            $model->uuid =  (string) Str::uuid();
        });
    }

    public function getQrCodeBase64(){
        $rsvpLink = route('grp.invitation-rsvp', ['uuid' => $this->uuid]);
        try {
            $qrCode = QrCode::create($rsvpLink)
                ->setSize(150)
                ->setMargin(10);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            return '<img src="data:image/png;base64,' . base64_encode($result->getString()) . '">';
        } catch (\Exception $e) {
            return '';
        }
    }
    public function event()
    {
        return $this->belongsTo(InvitationEvent::class, 'invitation_event_id');
    }

    public function getRsvpUrlAttribute(): string
    {
        return route('grp.invitation-guests.invitation-token', ['uuid' => $this->uuid]);
    }

    public function updateRsvp(string $status, ?int $guestsCount = null, ?string $dietary = null, ?string $notes = null)
    {
        $this->rsvp_status = $status;
        $this->responded_at = now();
        
        if ($guestsCount !== null) {
            $this->guests_count = $guestsCount;
        }
        if ($dietary !== null) {
            $this->dietary_needs = $dietary;
        }
        if ($notes !== null) {
            $this->response_notes = $notes;
        }
        
        $this->save();
    }

    public function markAsSent()
    {
        $this->invitation_sent_at = now();
        $this->save();
    }

    public function markAsOpened()
    {
        if (is_null($this->invitation_opened_at)) {
            $this->invitation_opened_at = now();
            $this->save();
        }
    }

    public function scopePending($query)
    {
        return $query->where('rsvp_status', 'pending');
    }

    public function scopeAttending($query)
    {
        return $query->where('rsvp_status', 'yes');
    }

    public function scopeNotAttending($query)
    {
        return $query->where('rsvp_status', 'no');
    }

    public function scopeForEvent($query, $eventId)
    {
        return $query->where('invitation_event_id', $eventId);
    }
}