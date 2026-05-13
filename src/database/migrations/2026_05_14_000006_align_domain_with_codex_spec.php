<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
        });

        DB::table('users')->where('role', 'customer')->update(['role' => 'user']);

        Schema::table('courts', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('price_per_hour');
            $table->decimal('rating', 3, 2)->default(0)->after('cover_image');
            $table->string('status')->default('active')->after('rating');
        });

        DB::table('courts')->where('is_active', true)->update(['status' => 'active']);
        DB::table('courts')->where('is_active', false)->update(['status' => 'inactive']);

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('court_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_id')->constrained('courts')->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['court_id', 'facility_id']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('schedule_id')->nullable()->after('court_id')->constrained('court_schedules')->nullOnDelete();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('court_id')->constrained('courts')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment');
            $table->timestamps();
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('court_id')->constrained('courts')->cascadeOnDelete();
            $table->decimal('similarity_score', 8, 2)->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'court_id']);
        });

        Schema::create('cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancellation_reason');
            $table->timestamp('cancelled_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->string('channel')->default('in_app');
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('cancellations');
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('reviews');

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('schedule_id');
        });

        Schema::dropIfExists('court_facility');
        Schema::dropIfExists('facilities');

        Schema::table('courts', function (Blueprint $table) {
            $table->dropColumn(['cover_image', 'rating', 'status']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address']);
        });
    }
};
