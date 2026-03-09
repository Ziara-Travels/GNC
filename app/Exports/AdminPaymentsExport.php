<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminPaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $type;
    protected $startDate;
    protected $endDate;

    public function __construct($type = 'all', $startDate = null, $endDate = null)
    {
        $this->type = $type;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Payment::with(['user', 'booking'])
            ->whereHas('user', function($q) {
                $q->where('role', 'user');
            });

        if ($this->type === 'flight') {
            $query->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                  ->where('bookings.type', 'flight');
        } elseif ($this->type === 'hotel') {
            $query->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                  ->where('bookings.type', 'hotel');
        }

        if ($this->startDate) {
            $query->whereDate('payments.created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('payments.created_at', '<=', $this->endDate);
        }

        return $query->select('payments.*')->get();
    }

    public function headings(): array
    {
        return [
            'Payment Reference',
            'User Name',
            'User Email',
            'Booking Reference',
            'Booking Type',
            'Amount',
            'Currency',
            'Payment Method',
            'Status',
            'Created At',
            'Updated At'
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->payment_reference,
            $payment->user->name,
            $payment->user->email,
            $payment->booking->booking_reference,
            $payment->booking->type,
            $payment->amount,
            $payment->currency,
            $payment->payment_method,
            $payment->status,
            $payment->created_at->format('Y-m-d H:i:s'),
            $payment->updated_at->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}