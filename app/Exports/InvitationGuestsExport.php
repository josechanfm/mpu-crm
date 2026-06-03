// app/Exports/InvitationGuestsExport.php
<?php

namespace App\Exports;

use App\Models\InvitationGuest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvitationGuestsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function query()
    {
        return InvitationGuest::where('invitation_event_id', $this->eventId)
            ->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Organization',
            'Phone',
            'RSVP Status',
            'Guests Count',
            'Responded At',
            'Dietary Needs',
            'Notes',
            'Invitation Sent At',
            'Invitation Opened At',
            'Token',
        ];
    }

    public function map($guest): array
    {
        return [
            $guest->name,
            $guest->email,
            $guest->organization,
            $guest->phone,
            $guest->rsvp_status,
            $guest->guests_count,
            $guest->responded_at ? $guest->responded_at->format('Y-m-d H:i:s') : '',
            $guest->dietary_needs,
            $guest->response_notes,
            $guest->invitation_sent_at ? $guest->invitation_sent_at->format('Y-m-d H:i:s') : '',
            $guest->invitation_opened_at ? $guest->invitation_opened_at->format('Y-m-d H:i:s') : '',
            $guest->token,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}