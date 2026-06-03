<?php

namespace App\Imports;

use App\Models\InvitationGuest;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;

class InvitationGuestsImport implements ToModel, WithValidation, WithHeadingRow, SkipsOnFailure
{
    use Importable;

    private $eventId;
    private $failures = [];
    private $rowCount = 0;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function model(array $row)
    {
        $this->rowCount++;
        
        return new InvitationGuest([
            'invitation_event_id' => $this->eventId,
            'name' => $row['name'],
            'email' => $row['email'],
            'organization' => $row['organization'] ?? null,
            'phone' => $row['phone'] ?? null,
            'rsvp_status' => 'pending',
            'guests_count' => 1,
            'token' => Str::random(32),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('invitation_guests', 'email')
                    ->where('invitation_event_id', $this->eventId)
            ],
            'organization' => 'nullable|string|max:255',
            'phone' => 'nullable|max:20',

        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.unique' => 'This email already exists for this event.',
            'organization.string' => 'The organization must be a string.',
            'organization.max' => 'The organization may not be greater than 255 characters.',
            'phone.max' => 'The phone may not be greater than 20 characters.',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            // Format error message nicely
            $errors = implode(', ', $failure->errors());
            $this->failures[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $errors,
            ];
        }
    }

    public function getFailures()
    {
        return $this->failures;
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }
}