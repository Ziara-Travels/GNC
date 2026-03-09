<?php


namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use Carbon\Carbon;

class PDFGenerationService
{
    public function generateInvoice(Booking $booking)
    {
        $data = [
            'booking' => $booking,
            'user' => $booking->user,
            'invoice_number' => 'INV-' . $booking->booking_reference,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'company_details' => [
                'name' => config('app.name'),
                'address' => '123 Travel Street, City, Country',
                'phone' => '+1-234-567-8900',
                'email' => 'billing@' . config('app.domain', 'example.com'),
                'tax_id' => 'TAX123456789'
            ]
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        $filename = 'invoices/invoice-' . $booking->booking_reference . '.pdf';
        
        Storage::disk('public')->put($filename, $pdf->output());
        
        return Storage::disk('public')->url($filename);
    }

    public function generateItinerary(Booking $booking)
    {
        $data = [
            'booking' => $booking,
            'user' => $booking->user,
            'passengers' => $booking->passengers,
            'generated_at' => now()->format('Y-m-d H:i:s')
        ];

        $pdf = Pdf::loadView('pdf.itinerary', $data);
        $filename = 'itineraries/itinerary-' . $booking->booking_reference . '.pdf';
        
        Storage::disk('public')->put($filename, $pdf->output());
        
        return Storage::disk('public')->url($filename);
    }

    public function generateReceipt(Booking $booking)
    {
        $data = [
            'booking' => $booking,
            'user' => $booking->user,
            'receipt_number' => 'REC-' . $booking->booking_reference,
            'payment_date' => $booking->updated_at->format('Y-m-d H:i:s'),
            'company_details' => [
                'name' => config('app.name'),
                'address' => '123 Travel Street, City, Country',
                'phone' => '+1-234-567-8900',
                'email' => 'support@' . config('app.domain', 'example.com')
            ]
        ];

        $pdf = Pdf::loadView('pdf.receipt', $data);
        $filename = 'receipts/receipt-' . $booking->booking_reference . '.pdf';
        
        Storage::disk('public')->put($filename, $pdf->output());
        
        return Storage::disk('public')->url($filename);
    }
}