// database/migrations/xxxx_xx_xx_000001_create_invitation_events_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invitation_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // for public URLs and tracking
            // Event information
            $table->string('name');
            $table->text('description')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('venue')->nullable();
            
            // Email template (HTML or text)
            $table->text('pdf_template')->nullable();   // full HTML with placeholders
            $table->text('email_template')->nullable();   // plain text fallback
            
            // Template placeholders help (optional)
            $table->json('available_placeholders')->nullable(); // e.g. ['guest_name', 'event_name', 'rsvp_link']
            
            // Event settings
            $table->boolean('is_active')->default(true);
            $table->integer('max_guests_per_invitation')->default(1);
            $table->dateTime('rsvp_deadline')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('start_date');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invitation_events');
    }
};