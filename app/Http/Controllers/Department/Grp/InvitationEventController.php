<?php

namespace App\Http\Controllers\Department\Grp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Department;
use App\Models\InvitationEvent;
use App\Models\InvitationGuest;
use App\Jobs\SendInvitationEventEmail;

use PDF;
use ZipArchive;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
// use Endroid\QrCode\Color\Color;
// use Endroid\QrCode\Encoding\Encoding;
// use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;

// use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
// use Barryvdh\DomPDF\Facade\Pdf;

class InvitationEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request->all());

        $query = InvitationEvent::withCount('guests');
        
        // Search by name or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        // Sort by column
        $sortField = $request->get('sort_field', 'start_date');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $events = $query->paginate($request->get('per_page', 10))
                        ->withQueryString(); // preserve query params
        return Inertia::render('Department/Grp/InvitationEvents', [
            'events' => $events,
            'filters' => [
                'search' => $request->get('search', ''),
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
                'per_page' => (int) $request->get('per_page', 10),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Department/Grp/InvitationEventForm', [
            'event' => new InvitationEvent(),
        ]);

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'email_template_html' => 'nullable|string',
            'email_template_text' => 'nullable|string',
            'is_active' => 'boolean',
            'max_guests_per_invitation' => 'integer|min:1|max:20',
            'rsvp_deadline' => 'nullable|date|before_or_equal:start_date',
        ]);

        // Convert dates to proper format if needed (already done by Laravel's date validation)
        // but ensure we store as datetime strings
        $event = InvitationEvent::create($validated);

        return redirect()->route('grp.invitation-events.index')
            ->with('success', 'Event created successfully.');
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
    public function edit(InvitationEvent $InvitationEvent)
    {
        //dd($InvitationEvent->load('guests'));
        return Inertia::render('Department/Grp/InvitationEventForm', [
            'event' => $InvitationEvent->load('guests'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvitationEvent $invitationEvent)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'pdf_template' => 'nullable|string',
            'email_template' => 'nullable|string',
        ]);
        $invitationEvent->update($request->all());
        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Helper for QR code (optional)
    private function generateQrCodeBase64($url)
    {
        try {
                $qrCode = QrCode::create($url)
                    ->setSize(150)
                    ->setMargin(10);
                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                return '<img src="data:image/png;base64,' . base64_encode($result->getString()) . '">';
            } catch (\Exception $e) {
                return '';
            }
    }


    public function generatePdf(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:invitation_events,id',
            'test' => 'nullable|boolean',
            'guest_id' => 'nullable|exists:invitation_guests,id',
        ]);

        $isTest = $request->boolean('test');
        $event = InvitationEvent::findOrFail($request->event_id);

        // --- REAL MODE: generate ZIP of all guests ---
        if($isTest){
            $guests = collect([(Object)[
                "id"=>1,
               "uuid" => "Wc5xrZijSVkN87vKmW4TalNJM0JqJSDe",
                "name" => "Prof. Fleta Ernser PhD",
                "organization" => "Bayer, Ernser and Ward",
                "email" => "fay.idella@example.org",
                "phone" => null,
                "invitation_sent_at" => "2026-05-23 09:32:32",
                "invitation_opened_at" => null,
                "qr_code_path" => null,
                "rsvp_status" => "maybe",
                "responded_at" => "2026-05-31 09:32:32",
                "guests_count" => 1,
                "dietary_needs" => null,
                "response_notes" => null,
                "custom_fields" => null
            ]] ); 
        }else{
            $guests = $event->guests;
        }
        if ($guests->isEmpty()) {
            return back()->withErrors('No guests found for this event.');
        }

        // Create a temporary directory
        $tempDir = storage_path('app/temp_pdfs_' . uniqid());
        if (!mkdir($tempDir, 0777, true)) {
            return back()->withErrors('Failed to create temporary directory.');
        }

        $pdfFiles = [];

        foreach ($guests as $guest) {
            $guestName = $guest->name ?? 'Valued Guest';
            $guestOrg = $guest->organization ?? '';
            $rsvpLink = route('grp.invitation-rsvp', ['uuid' => $guest->uuid]);
            //$qrCodeImage = $this->generateQrCodeBase64($rsvpLink);
            $qrCodeImage = $guest->getQrCodeBase64();
            
            $eventName = $event->name;
            $eventDate = $event->start_date ? $event->start_date->format('F j, Y, g:i a') : 'TBD';
            $eventLocation = trim(($event->venue ?? '') . ', ' . ($event->location ?? ''));
            
            $pdfBody = $event->pdf_template ?? '';
            if (empty($pdfBody)) {
                $plainText = $event->pdf_template ?? '';
                $emailBody = nl2br(e($plainText));
            }
            $placeholders = [
                '{{guest_name}}' => $guestName,
                '{{guest_organization}}' => $guestOrg,
                '{{event_name}}' => $eventName,
                '{{event_date}}' => $eventDate,
                '{{event_location}}' => $eventLocation,
                '{{rsvp_link}}' => $rsvpLink,
                '{{qr_code_image}}' => $qrCodeImage,
            ];
            $pdfBody = str_replace(array_keys($placeholders), array_values($placeholders), $pdfBody);
            
            $viewData = [
                'event_name' => $eventName,
                'guest_name' => $guestName,
                'guest_organization' => $guestOrg,
                'pdf_body' => $pdfBody,
                'rsvp_link' => $rsvpLink,
                'qr_code_image' => $qrCodeImage,
                'is_test' => false,
            ];
            //dd($guest, $qrCodeImage, $emailBody, $rsvpLink, $viewData);
            
            $pdf = Pdf::loadView('invitationPdf', $viewData);
            $pdf->setPaper('A4', 'portrait');
            
            // Safe filename: remove special characters
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $guestName);
            $filename = "invitation_{$event->id}_{$guest->id}_{$safeName}.pdf";
            $filepath = $tempDir . '/' . $filename;
            $pdf->save($filepath);
            $pdfFiles[] = $filepath;
        }

        // Create ZIP archive
        $zipFileName = "invitations_event_{$event->id}.zip";
        $zipPath = storage_path("app/temp_{$zipFileName}");
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            // Cleanup temp dir
            array_map('unlink', glob("$tempDir/*.*"));
            rmdir($tempDir);
            return back()->withErrors('Could not create ZIP archive.');
        }

        foreach ($pdfFiles as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();

        // Delete temporary PDF files and directory
        array_map('unlink', glob("$tempDir/*.*"));
        rmdir($tempDir);

        // Return ZIP download and delete after sending
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    public function sendBulkEmails(Request $request)
    {
        $request->validate([
            'guest_ids' => 'required|array',
            'guest_ids.*' => 'exists:invitation_guests,id',
            'event_id' => 'required|exists:invitation_events,id',
        ]);

        $event = InvitationEvent::findOrFail($request->event_id);
        $guests = InvitationGuest::whereIn('id', $request->guest_ids)->get();

        foreach ($guests as $guest) {
            SendInvitationEventEmail::dispatch($guest, $event);
        }

        return response()->json([
            'message' => "Emails are being queued for {$guests->count()} guest(s). You will receive a notification when done."
        ]);
    }


    
}
