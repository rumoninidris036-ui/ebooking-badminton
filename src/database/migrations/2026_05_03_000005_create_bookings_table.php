<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('court_id')->constrained('courts')->cascadeOnDelete();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedTinyInteger('duration_hours');
            $table->decimal('price_per_hour', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['court_id', 'booking_date', 'start_time', 'end_time'], 'bookings_exact_slot_unique');
            $table->index(['court_id', 'booking_date', 'status'], 'bookings_court_date_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
