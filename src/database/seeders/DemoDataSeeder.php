<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Cancellation;
use App\Models\Court;
use App\Models\Facility;
use App\Models\Payment;
use App\Models\Recommendation;
use App\Models\Review;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\CourtService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'phone' => '081100000001',
            'address' => 'Jayapura',
        ]);

        $owners = collect([
            User::factory()->owner()->create([
                'name' => 'Owner A Demo',
                'email' => 'owner1@example.com',
                'password' => 'password123',
                'phone' => '081100000002',
                'address' => 'Abepura',
            ]),
            User::factory()->owner()->create([
                'name' => 'Owner B Demo',
                'email' => 'owner2@example.com',
                'password' => 'password123',
                'phone' => '081100000003',
                'address' => 'Sentani',
            ]),
        ]);

        $users = collect([
            User::factory()->create([
                'name' => 'User Demo 1',
                'email' => 'user1@example.com',
                'password' => 'password123',
                'phone' => '081100000004',
                'address' => 'Abepura',
            ]),
            User::factory()->create([
                'name' => 'User Demo 2',
                'email' => 'user2@example.com',
                'password' => 'password123',
                'phone' => '081100000005',
                'address' => 'Heram',
            ]),
            User::factory()->create([
                'name' => 'User Demo 3',
                'email' => 'user3@example.com',
                'password' => 'password123',
                'phone' => '081100000006',
                'address' => 'Sentani',
            ]),
        ]);

        $facilities = collect([
            ['name' => 'AC', 'icon' => 'snowflake'],
            ['name' => 'Cafe', 'icon' => 'coffee'],
            ['name' => 'Shower', 'icon' => 'droplets'],
            ['name' => 'Parking', 'icon' => 'car'],
            ['name' => 'Wifi', 'icon' => 'wifi'],
        ])->map(fn (array $facility) => Facility::query()->create($facility));

        $courts = $this->seedCourts($owners, $facilities);
        $bookings = $this->seedBookings($courts, $users);

        $this->seedReviews($bookings);
        $this->seedRecommendations($users, $courts);
        $this->seedNotifications($admin, $owners, $users, $bookings);
    }

    protected function seedCourts(Collection $owners, Collection $facilities): Collection
    {
        $courtSeeds = [
            [
                'owner' => $owners[0],
                'name' => 'Skyline Badminton Arena',
                'location' => 'Abepura Sports Hall',
                'price_per_hour' => 85000,
                'rating' => 4.6,
                'status' => 'active',
                'is_active' => true,
                'description' => 'Lapangan indoor premium dengan pencahayaan terang dan area tunggu nyaman.',
                'facility_indexes' => [0, 1, 3, 4],
            ],
            [
                'owner' => $owners[0],
                'name' => 'Garuda Court Center',
                'location' => 'Heram Community Hall',
                'price_per_hour' => 70000,
                'rating' => 4.2,
                'status' => 'active',
                'is_active' => true,
                'description' => 'Cocok untuk latihan rutin dengan harga lebih terjangkau.',
                'facility_indexes' => [1, 3],
            ],
            [
                'owner' => $owners[1],
                'name' => 'Sentani Smash Point',
                'location' => 'Sentani Indoor Complex',
                'price_per_hour' => 95000,
                'rating' => 4.8,
                'status' => 'active',
                'is_active' => true,
                'description' => 'Venue favorit untuk prime time dan pertandingan komunitas.',
                'facility_indexes' => [0, 2, 3, 4],
            ],
            [
                'owner' => $owners[1],
                'name' => 'Cenderawasih Court',
                'location' => 'Waena Sport Center',
                'price_per_hour' => 65000,
                'rating' => 3.9,
                'status' => 'inactive',
                'is_active' => false,
                'description' => 'Lapangan sedang dibatasi operasional untuk penyesuaian jadwal.',
                'facility_indexes' => [2, 3],
            ],
        ];

        return collect($courtSeeds)->map(function (array $courtSeed, int $index) use ($facilities) {
            $court = Court::query()->create([
                'owner_id' => $courtSeed['owner']->id,
                'name' => $courtSeed['name'],
                'slug' => str($courtSeed['name'])->slug()->append('-'.($index + 1)),
                'description' => $courtSeed['description'],
                'location' => $courtSeed['location'],
                'price_per_hour' => $courtSeed['price_per_hour'],
                'cover_image' => null,
                'rating' => $courtSeed['rating'],
                'status' => $courtSeed['status'],
                'is_active' => $courtSeed['is_active'],
            ]);

            $court->facilities()->sync(
                collect($courtSeed['facility_indexes'])->map(fn (int $facilityIndex) => $facilities[$facilityIndex]->id)->all()
            );

            $court->schedules()->createMany(
                collect(CourtService::DAYS)->map(function (string $dayName, int $dayNumber) use ($court) {
                    $isOpen = $court->status === 'active' ? ! in_array($dayNumber, [2], true) : $dayNumber === 6;

                    return [
                        'day_of_week' => $dayNumber,
                        'open_time' => $isOpen ? '08:00' : null,
                        'close_time' => $isOpen ? ($dayNumber >= 5 ? '23:00' : '22:00') : null,
                        'is_open' => $isOpen,
                    ];
                })->values()->all()
            );

            return $court->fresh(['owner', 'schedules', 'facilities']);
        });
    }

    protected function seedBookings(Collection $courts, Collection $users): Collection
    {
        $bookingSeeds = [
            ['user' => $users[0], 'court' => $courts[0], 'day_offset' => 1, 'start' => '18:00', 'status' => 'pending', 'note' => 'Main bareng komunitas malam ini.'],
            ['user' => $users[1], 'court' => $courts[0], 'day_offset' => 2, 'start' => '19:00', 'status' => 'paid', 'note' => 'Sudah siap dan menunggu hari main.'],
            ['user' => $users[2], 'court' => $courts[1], 'day_offset' => 3, 'start' => '20:00', 'status' => 'finished', 'note' => 'Sesi latihan pasangan ganda.'],
            ['user' => $users[0], 'court' => $courts[2], 'day_offset' => 4, 'start' => '17:00', 'status' => 'canceled', 'note' => 'Terpaksa batal karena perubahan jadwal.'],
            ['user' => $users[1], 'court' => $courts[2], 'day_offset' => 5, 'start' => '18:00', 'status' => 'pending', 'note' => 'Booking untuk sparring club.'],
            ['user' => $users[2], 'court' => $courts[0], 'day_offset' => 6, 'start' => '21:00', 'status' => 'paid', 'note' => 'Prime time booking.'],
            ['user' => $users[0], 'court' => $courts[1], 'day_offset' => 7, 'start' => '16:00', 'status' => 'finished', 'note' => 'Sesi latihan sore.'],
            ['user' => $users[1], 'court' => $courts[2], 'day_offset' => 8, 'start' => '19:00', 'status' => 'pending', 'note' => 'Butuh konfirmasi owner.'],
            ['user' => $users[2], 'court' => $courts[1], 'day_offset' => 9, 'start' => '18:00', 'status' => 'paid', 'note' => 'Sudah dibayar via transfer.'],
            ['user' => $users[0], 'court' => $courts[0], 'day_offset' => 10, 'start' => '20:00', 'status' => 'finished', 'note' => 'Pertandingan mingguan selesai.'],
        ];

        return collect($bookingSeeds)->map(function (array $seed, int $index) {
            $bookingDate = Carbon::today()->addDays($seed['day_offset']);
            $schedule = $seed['court']->schedules->firstWhere('day_of_week', (int) $bookingDate->isoWeekday())
                ?? $seed['court']->schedules->first();
            $endTime = Carbon::createFromFormat('H:i', $seed['start'])->addHour()->format('H:i');

            $booking = Booking::query()->create([
                'booking_code' => sprintf('BK-DEMO-%03d', $index + 1),
                'user_id' => $seed['user']->id,
                'court_id' => $seed['court']->id,
                'schedule_id' => $schedule?->id,
                'booking_date' => $bookingDate->toDateString(),
                'start_time' => $seed['start'],
                'end_time' => $endTime,
                'duration_hours' => 1,
                'price_per_hour' => $seed['court']->price_per_hour,
                'total_price' => $seed['court']->price_per_hour,
                'status' => $seed['status'],
                'notes' => $seed['note'],
            ]);

            if (in_array($seed['status'], ['paid', 'finished'], true)) {
                Payment::query()->create([
                    'booking_id' => $booking->id,
                    'payment_method' => $index % 2 === 0 ? 'bank_transfer' : 'qris',
                    'transaction_id' => 'TRX-DEMO-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                    'amount' => $booking->total_price,
                    'payment_status' => $seed['status'] === 'finished' ? 'paid' : 'paid',
                    'paid_at' => $bookingDate->copy()->subDay(),
                ]);
            }

            if ($seed['status'] === 'canceled') {
                Cancellation::query()->create([
                    'booking_id' => $booking->id,
                    'cancelled_by' => $seed['user']->id,
                    'cancellation_reason' => 'Ada agenda lain yang bentrok.',
                    'cancelled_at' => now()->subDays(2),
                ]);
            }

            return $booking->fresh(['court', 'user', 'payment', 'cancellation']);
        });
    }

    protected function seedReviews(Collection $bookings): void
    {
        $bookings
            ->where('status', 'finished')
            ->take(3)
            ->each(function (Booking $booking, int $index) {
                Review::query()->create([
                    'user_id' => $booking->user_id,
                    'court_id' => $booking->court_id,
                    'rating' => [5, 4, 5][$index] ?? 4,
                    'comment' => [
                        'Lapangan bersih dan jadwal sangat rapi.',
                        'Pengalaman booking lancar dan owner cepat merespons.',
                        'Venue nyaman untuk latihan rutin maupun sparring.',
                    ][$index] ?? 'Pengalaman bermain sangat baik.',
                ]);
            });
    }

    protected function seedRecommendations(Collection $users, Collection $courts): void
    {
        foreach ($users as $user) {
            foreach ($courts->take(3) as $position => $court) {
                Recommendation::query()->create([
                    'user_id' => $user->id,
                    'court_id' => $court->id,
                    'similarity_score' => 95 - ($position * 7) - ($user->id % 3),
                    'created_at' => now()->subMinutes($position * 10),
                ]);
            }
        }
    }

    protected function seedNotifications(User $admin, Collection $owners, Collection $users, Collection $bookings): void
    {
        $notifications = [
            [$admin->id, 'system', 'Platform health stable', 'Semua layanan inti berjalan normal.'],
            [$admin->id, 'booking', 'New booking activity', 'Ada beberapa booking baru yang masuk hari ini.'],
            [$owners[0]->id, 'booking', 'Pending confirmation', 'Ada booking yang menunggu konfirmasi di lapangan Anda.'],
            [$owners[0]->id, 'payment', 'Payment received', 'Pembayaran booking terbaru telah berhasil diterima.'],
            [$owners[1]->id, 'system', 'Schedule reminder', 'Periksa kembali jadwal operasional untuk akhir pekan.'],
            [$users[0]->id, 'booking', 'Booking created', 'Booking Anda berhasil dibuat dan sedang menunggu konfirmasi.'],
            [$users[1]->id, 'payment', 'Payment confirmed', 'Pembayaran untuk booking Anda sudah terverifikasi.'],
            [$users[2]->id, 'booking', 'Session completed', 'Sesi bermain Anda telah ditandai selesai.'],
        ];

        foreach ($notifications as $index => [$userId, $type, $title, $message]) {
            UserNotification::query()->create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'channel' => 'in_app',
                'is_read' => $index % 3 === 0,
                'created_at' => now()->subHours($index + 1),
            ]);
        }

        $latestCanceledBooking = $bookings->firstWhere('status', 'canceled');

        if ($latestCanceledBooking) {
            UserNotification::query()->create([
                'user_id' => $latestCanceledBooking->user_id,
                'type' => 'booking',
                'title' => 'Booking canceled',
                'message' => 'Booking '.$latestCanceledBooking->booking_code.' telah dibatalkan.',
                'channel' => 'in_app',
                'is_read' => false,
                'created_at' => now()->subMinutes(30),
            ]);
        }
    }
}
