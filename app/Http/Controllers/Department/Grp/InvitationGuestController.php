<?php

namespace App\Http\Controllers\Department\Grp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvitationGuest;
use App\Models\InvitationEvent;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Imports\InvitationGuestsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class InvitationGuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invitationGuests = InvitationGuest::where('invitation_event_id', $request->event_id)
            ->when($request->sort_column, fn($q) => $q->orderBy($request->sort_column, $request->sort_order ?? 'asc'))
            ->paginate($request->per_page ?? 10);
        return response()->json($invitationGuests);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'invitation_event_id' => 'required|exists:invitation_events,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = InvitationGuest::where('email', $value)
                        ->where('invitation_event_id', $request->invitation_event_id)
                        ->whereNull('deleted_at')   // only check non‑deleted records
                        ->exists();
                    if ($exists) {
                        $fail('This email is already invited to this event (active guest).');
                    }
                },
            ],
            'organization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'rsvp_status' => 'nullable|in:pending,yes,no,maybe',
            'guests_count' => 'nullable|integer|min:1',
            'dietary_needs' => 'nullable|string',
            'response_notes' => 'nullable|string',
        ]);
        //dd($request->all(), $validated);

        $guest = InvitationGuest::create($validated);

        // For Inertia, redirect back with success message
        return redirect()->back()->with('success', 'Guest created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvitationGuest $invitationGuest)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $invitationGuest) {
                    $exists = InvitationGuest::where('email', $value)
                        ->where('invitation_event_id', $request->invitation_event_id)
                        ->whereNull('deleted_at')
                        ->where('id', '!=', $invitationGuest->id)
                        ->exists();
                    if ($exists) {
                        $fail('This email is already used by another active guest in this event.');
                    }
                },
            ],
            'organization' => 'nullable|string',
            'phone' => 'nullable|string',
            'rsvp_status' => 'nullable|string',
            'guests_count' => 'nullable|integer|min:1',
            'dietary_needs' => 'nullable|string',
            'response_notes' => 'nullable|string',
        ]);

        $invitationGuest->update($validated);
        // dd($validated, $invitationGuest);
        // For Inertia requests, redirect back with success
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Guest updated successfully', 'guest' => $invitationGuest]);
        }
        
        return redirect()->back()->with('success', 'Guest updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvitationGuest $invitationGuest)
    {
        $invitationGuest->delete();
        return redirect()->back();
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt|max:2048',
            'event_id' => 'required|exists:invitation_events,id',
        ]);

        $import = new InvitationGuestsImport($request->event_id);
        
        try {
            Excel::import($import, $request->file('csv'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $failedRows = [];
            foreach ($failures as $failure) {
                $failedRows[$failure->row()] = implode(', ', $failure->errors());
            }
            return response()->json([
                'success' => false,
                'summary' => 'Import failed due to validation errors.',
                'imported' => 0,
                'failed_rows' => $failedRows,
            ], 422);
        }

        $failures = $import->getFailures();
        $importedCount = $import->getRowCount() - count($failures);

        $failedRows = [];
        foreach ($failures as $failure) {
            $failedRows[$failure['row']] = $failure['errors'];
        }

        $summary = "Import completed: {$importedCount} guest(s) imported.";
        if (!empty($failedRows)) {
            $summary .= " " . count($failedRows) . " row(s) failed.";
        }

        return response()->json([
            'success' => empty($failedRows),
            'summary' => $summary,
            'imported' => $importedCount,
            'failed_rows' => $failedRows,
        ], empty($failedRows) ? 200 : 207);
    }

    public function export(InvitationEvent $event)
    {
        $guests = $event->guests()->orderBy('name')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="guests_' . $event->id . '_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($guests) {
            $file = fopen('php://output', 'w');
            // Add CSV headers
            fputcsv($file, [
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
            ]);

            foreach ($guests as $guest) {
                fputcsv($file, [
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
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }



    public function invitationRsvp(Request $request){
        $uuid = $request->input('uuid');
        $guest = InvitationGuest::where('uuid', $uuid)->firstOrFail();
        $event = InvitationEvent::find($guest->invitation_event_id);
        return Inertia::render('Department/Grp/InvitationRsvp', [
            'event'=>$event,
            'guest' => $guest
        ]);
    }
}
