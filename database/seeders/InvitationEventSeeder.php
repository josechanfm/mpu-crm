<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvitationEvent;
use App\Models\InvitationGuest;
use Faker\Factory as Faker;
use Carbon\Carbon;

class InvitationEventSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Define 3 events with realistic data
        $events = [
            [
                'name' => 'Annual Tech Conference 2025',
                'description' => 'Join industry leaders for a day of innovation and networking.',
                'start_date' => Carbon::now()->addMonths(2)->setTime(9, 0),
                'end_date' => Carbon::now()->addMonths(2)->setTime(18, 0),
                'location' => 'San Francisco, CA',
                'venue' => 'Moscone Center',
                'email_template_html' => '<h1>Dear {{guest_name}},</h1><p>You are invited to <strong>{{event_name}}</strong> on {{event_date}} at {{event_location}}.</p><p>Please confirm your attendance by clicking <a href="{{rsvp_link}}">here</a> or scan the QR code below.</p><img src="{{qr_code_image}}" width="150"/>',
                'email_template_text' => "Dear {{guest_name}},\n\nYou are invited to {{event_name}} on {{event_date}} at {{event_location}}.\n\nRSVP: {{rsvp_link}}",
                'max_guests_per_invitation' => 2,
                'rsvp_deadline' => Carbon::now()->addMonths(1)->endOfDay(),
            ],
            [
                'name' => 'Charity Gala Dinner',
                'description' => 'An elegant evening supporting local education initiatives.',
                'start_date' => Carbon::now()->addMonths(1)->setTime(19, 0),
                'end_date' => Carbon::now()->addMonths(1)->setTime(23, 0),
                'location' => 'New York, NY',
                'venue' => 'The Plaza Hotel',
                'email_template_html' => '<div style="font-family: serif;"><h2>You\'re Invited!</h2><p>Dear {{guest_name}},</p><p>Join us for a night of purpose and celebration at {{event_name}}.</p><p><strong>When:</strong> {{event_date}}<br><strong>Where:</strong> {{event_location}}</p><p>RSVP by clicking <a href="{{rsvp_link}}">this link</a> or scanning the QR code.</p><img src="{{qr_code_image}}" width="120"/></div>',
                'email_template_text' => "You're Invited!\n\nDear {{guest_name}},\n\nJoin us for {{event_name}} on {{event_date}} at {{event_location}}.\n\nRSVP: {{rsvp_link}}",
                'max_guests_per_invitation' => 4,
                'rsvp_deadline' => Carbon::now()->addWeeks(3)->endOfDay(),
            ],
            [
                'name' => 'Startup Founders Meetup',
                'description' => 'Monthly gathering for founders, investors, and innovators.',
                'start_date' => Carbon::now()->addDays(20)->setTime(18, 30),
                'end_date' => Carbon::now()->addDays(20)->setTime(21, 30),
                'location' => 'Austin, TX',
                'venue' => 'Capital Factory',
                'email_template_html' => '<p>Hi {{guest_name}},</p><p>Ready to mingle? {{event_name}} is happening on {{event_date}} at {{event_location}}.</p><p>Let us know if you can make it: <a href="{{rsvp_link}}">{{rsvp_link}}</a></p><img src="{{qr_code_image}}" width="100"/>',
                'email_template_text' => "Hi {{guest_name}},\n\n{{event_name}} is on {{event_date}} at {{event_location}}.\n\nRSVP: {{rsvp_link}}",
                'max_guests_per_invitation' => 1,
                'rsvp_deadline' => Carbon::now()->addDays(15)->endOfDay(),
            ],
        ];

        foreach ($events as $eventData) {
            // Create the event
            $event = InvitationEvent::create(array_merge($eventData, [
                'is_active' => true,
                'available_placeholders' => ['guest_name', 'guest_organization', 'event_name', 'event_date', 'event_location', 'rsvp_link', 'qr_code_image'],
            ]));

            // Generate between 10 and 20 guests for this event
            $numberOfGuests = rand(10, 20);

            for ($i = 0; $i < $numberOfGuests; $i++) {
                $name = $faker->name;
                $organization = $faker->optional(0.7)->company; // 70% chance of having organization
                $email = $faker->unique()->safeEmail;
                $phone = $faker->optional(0.8)->phoneNumber;

                // Randomly assign some RSVP statuses for realistic seeder data
                $statuses = ['pending', 'yes', 'no', 'maybe'];
                $weights = [0.5, 0.3, 0.1, 0.1]; // 50% pending, 30% yes, 10% no, 10% maybe
                $rsvp_status = $faker->randomElement($statuses, $weights);
                
                $responded_at = null;
                $guests_count = 1;
                $dietary_needs = null;
                
                if ($rsvp_status !== 'pending') {
                    $responded_at = Carbon::now()->subDays(rand(1, 20));
                    if ($rsvp_status === 'yes') {
                        $guests_count = rand(1, $event->max_guests_per_invitation);
                        if ($guests_count > 1) {
                            $dietary_needs = $faker->optional(0.3)->sentence;
                        }
                    }
                }

                InvitationGuest::create([
                    'invitation_event_id' => $event->id,
                    'name' => $name,
                    'organization' => $organization,
                    'email' => $email,
                    'phone' => $phone,
                    'token' => \Illuminate\Support\Str::random(32),
                    'invitation_sent_at' => Carbon::now()->subDays(rand(1, 10)),
                    'qr_code_path' => null, // will be generated on demand
                    'rsvp_status' => $rsvp_status,
                    'responded_at' => $responded_at,
                    'guests_count' => $guests_count,
                    'dietary_needs' => $dietary_needs,
                    'response_notes' => $faker->optional(0.2)->sentence,
                    'custom_fields' => null,
                ]);
            }

            // Ensure at least 10 guests (in case rand gave less but we already set min 10)
            // The loop already guarantees >=10 because rand(10,20).
        }
    }
}