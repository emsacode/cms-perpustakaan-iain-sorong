<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Status Reservasi Ruangan - Perpustakaan IAIN Sorong';

        if ($this->reservation->status === 'approved') {
            $subject = 'Reservasi Ruangan DISETUJUI - Perpustakaan IAIN Sorong';
        } elseif ($this->reservation->status === 'rejected') {
            $subject = 'Reservasi Ruangan DITOLAK - Perpustakaan IAIN Sorong';
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-status',
        );
    }
}
