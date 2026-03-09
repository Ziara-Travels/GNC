<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

        public $booking;
    public $eTicketPath;


    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->generateETicket();

    }

    /**
     * Build the message.
     *
     * @return $this
     */
   public function build()
    {
        $mail = $this->subject('Booking Confirmation - ' . $this->booking->booking_reference)
                    ->view('emails.booking-confirmation')
                    ->with([
                        'booking' => $this->booking,
                    ]);

        if ($this->eTicketPath && file_exists($this->eTicketPath)) {
            $mail->attach($this->eTicketPath, [
                'as' => 'e-ticket-' . $this->booking->booking_reference . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
    private function generateETicket()
    {
        try {
            $this->booking->loadMissing('user');
            $pdf = Pdf::loadView('booking.eticket-pdf', [
                'booking' => $this->booking,
            ]);

            $filename = 'e-tickets/ticket-' . $this->booking->booking_reference . '.pdf';
            Storage::disk('public')->put($filename, $pdf->output());
            $this->eTicketPath = Storage::disk('public')->path($filename);
        } catch (\Exception $e) {
            \Log::error('E-ticket generation failed: ' . $e->getMessage());
            $this->eTicketPath = null;
        }
    }
}
