// database/migrations/xxxx_xx_xx_000002_create_invitation_guests_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invitation_guests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); 
            // Foreign key to event
            $table->foreignId('invitation_event_id')
                  ->constrained('invitation_events')
                  ->onDelete('cascade');
            
            // Guest information
            $table->string('name');
            $table->string('organization')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            
            // Invitation unique token & tracking
            $table->string('token')->unique();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('invitation_opened_at')->nullable();
            $table->string('qr_code_path')->nullable();   // stored QR image path
            
            // RSVP response fields
            $table->enum('rsvp_status', ['pending', 'yes', 'no', 'maybe'])->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->integer('guests_count')->default(1);   // number of people attending (including guest)
            $table->text('dietary_needs')->nullable();
            $table->text('response_notes')->nullable();
            
            // Custom data for this guest (e.g., table assignment, special requests)
            $table->json('custom_fields')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('email');
            $table->index('token');
            $table->index('rsvp_status');
            $table->index('invitation_event_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invitation_guests');
    }
};