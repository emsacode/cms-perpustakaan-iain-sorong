<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use App\Mail\ReservationStatusMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->adminUser = User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@iainsorong.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);
    }

    public function test_admin_can_approve_reservation_and_send_email(): void
    {
        Mail::fake();

        $reservation = Reservation::create([
            'name' => 'Ahmad Fauzi',
            'nim_nip' => '202410012',
            'email' => 'ahmad.fauzi@iainsorong.ac.id',
            'room_name' => 'Ruang Diskusi Kelompok 1',
            'booking_date' => '2026-07-10',
            'session_time' => '09:00 - 11:00 WIT',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post("/admin/reservations/{$reservation->id}/status", [
                'status' => 'approved'
            ]);

        $response->assertRedirect();
        $this->assertEquals('approved', $reservation->fresh()->status);

        Mail::assertSent(ReservationStatusMail::class, function ($mail) use ($reservation) {
            return $mail->hasTo($reservation->email) && $mail->reservation->status === 'approved';
        });
    }

    public function test_admin_can_reject_reservation_with_reason(): void
    {
        Mail::fake();

        $reservation = Reservation::create([
            'name' => 'Siti Aminah',
            'nim_nip' => '202410045',
            'email' => 'siti.aminah@iainsorong.ac.id',
            'room_name' => 'Ruang Home Theater',
            'booking_date' => '2026-07-10',
            'session_time' => '13:00 - 15:00 WIT',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post("/admin/reservations/{$reservation->id}/status", [
                'status' => 'rejected',
                'rejection_reason' => 'Ruangan sedang dipakai untuk rapat rektorat'
            ]);

        $response->assertRedirect();
        $this->assertEquals('rejected', $reservation->fresh()->status);
        $this->assertEquals('Ruangan sedang dipakai untuk rapat rektorat', $reservation->fresh()->rejection_reason);

        Mail::assertSent(ReservationStatusMail::class, function ($mail) use ($reservation) {
            return $mail->hasTo($reservation->email) && $mail->reservation->status === 'rejected';
        });
    }

    public function test_admin_cannot_reject_without_reason(): void
    {
        Mail::fake();

        $reservation = Reservation::create([
            'name' => 'Siti Aminah',
            'nim_nip' => '202410045',
            'email' => 'siti.aminah@iainsorong.ac.id',
            'room_name' => 'Ruang Home Theater',
            'booking_date' => '2026-07-10',
            'session_time' => '13:00 - 15:00 WIT',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post("/admin/reservations/{$reservation->id}/status", [
                'status' => 'rejected',
                'rejection_reason' => ''
            ]);

        $response->assertSessionHasErrors(['rejection_reason']);
        $this->assertEquals('pending', $reservation->fresh()->status);
        Mail::assertNotSent(ReservationStatusMail::class);
    }

    public function test_admin_can_mark_key_as_picked_up(): void
    {
        Mail::fake();

        $reservation = Reservation::create([
            'name' => 'Rina Wijaya',
            'nim_nip' => '202410112',
            'email' => 'rina.wijaya@iainsorong.ac.id',
            'room_name' => 'Ruang Diskusi Kelompok 1',
            'booking_date' => '2026-07-10',
            'session_time' => '14:00 - 16:00 WIT',
            'status' => 'approved'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post("/admin/reservations/{$reservation->id}/status", [
                'status' => 'key_picked_up',
                'notes_inventory' => 'Remote AC dipinjam'
            ]);

        $response->assertRedirect();
        $fresh = $reservation->fresh();
        $this->assertEquals('key_picked_up', $fresh->status);
        $this->assertNotNull($fresh->picked_up_at);
        $this->assertEquals('Remote AC dipinjam', $fresh->notes_inventory);
    }

    public function test_livewire_detail_page_renders_and_sets_data(): void
    {
        $reservation = Reservation::create([
            'name' => 'Ahmad Fauzi',
            'nim_nip' => '202410012',
            'email' => 'ahmad.fauzi@iainsorong.ac.id',
            'room_name' => 'Ruang Diskusi Kelompok 1',
            'booking_date' => '2026-07-10',
            'session_time' => '09:00 - 11:00 WIT',
            'status' => 'pending'
        ]);

        \Livewire\Livewire::test(\App\Livewire\Admin\ReservationDetail::class, ['id' => $reservation->id])
            ->assertSet('reservationId', $reservation->id)
            ->assertSet('showRejectInput', false);
    }

    public function test_livewire_detail_approve_reservation(): void
    {
        Mail::fake();

        $reservation = Reservation::create([
            'name' => 'Ahmad Fauzi',
            'nim_nip' => '202410012',
            'email' => 'ahmad.fauzi@iainsorong.ac.id',
            'room_name' => 'Ruang Diskusi Kelompok 1',
            'booking_date' => '2026-07-10',
            'session_time' => '09:00 - 11:00 WIT',
            'status' => 'pending'
        ]);

        \Livewire\Livewire::test(\App\Livewire\Admin\ReservationDetail::class, ['id' => $reservation->id])
            ->call('approveReservation');

        $this->assertEquals('approved', $reservation->fresh()->status);
        Mail::assertSent(ReservationStatusMail::class);
    }

    public function test_livewire_detail_reject_reservation_with_reason(): void
    {
        Mail::fake();

        $reservation = Reservation::create([
            'name' => 'Siti Aminah',
            'nim_nip' => '202410045',
            'email' => 'siti.aminah@iainsorong.ac.id',
            'room_name' => 'Ruang Home Theater',
            'booking_date' => '2026-07-10',
            'session_time' => '13:00 - 15:00 WIT',
            'status' => 'pending'
        ]);

        \Livewire\Livewire::test(\App\Livewire\Admin\ReservationDetail::class, ['id' => $reservation->id])
            ->set('rejectionReasonText', 'Ruangan penuh')
            ->call('rejectReservation');

        $this->assertEquals('rejected', $reservation->fresh()->status);
        $this->assertEquals('Ruangan penuh', $reservation->fresh()->rejection_reason);
        Mail::assertSent(ReservationStatusMail::class);
    }

    public function test_reservation_auto_cancellation_logic(): void
    {
        // 1. Create a reservation that is approved and today (relative to our simulated time)
        $reservation = Reservation::create([
            'name' => 'Lutfi Hakim',
            'nim_nip' => '202410200',
            'email' => 'lutfi@iainsorong.ac.id',
            'room_name' => 'Ruang Multimedia',
            'booking_date' => '2026-07-10',
            'session_time' => '09:00 - 11:00 WIT',
            'status' => 'approved'
        ]);

        // Simulated time: 10 July 2026, 09:20 WIT (which is UTC 00:20)
        // Jayapura/Sorong is UTC+9, so 09:20 WIT = 00:20 UTC
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::create(2026, 7, 10, 0, 20, 0, 'UTC'));

        // Run checking
        Reservation::checkAndCancelExpiredReservations();

        // Should be cancelled since 09:20 is greater than 09:15 (09:00 + 15 mins)
        $this->assertEquals('cancelled', $reservation->fresh()->status);
        $this->assertStringContainsString('Dibatalkan otomatis', $reservation->fresh()->rejection_reason);

        // Reset Carbon test time
        \Carbon\Carbon::setTestNow();
    }
}
