<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklyReportMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DataExportService
{
    public function exportUsers($filters = [])
    {
        $query = User::query();

        if (isset($filters['created_from'])) {
            $query->where('created_at', '>=', $filters['created_from']);
        }

        if (isset($filters['created_to'])) {
            $query->where('created_at', '<=', $filters['created_to']);
        }

        if (isset($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        $users = $query->get();

        $csvData = [];
        $csvData[] = ['ID', 'Name', 'Email', 'Role', 'Loyalty Points', 'Created At', 'Last Login'];

        foreach ($users as $user) {
            $csvData[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->role ?? 'user',
                $user->loyalty_points ?? 0,
                $user->created_at->format('Y-m-d H:i:s'),
                $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never'
            ];
        }

        return $this->generateCSV($csvData, 'users_export_' . now()->format('Y-m-d'));
    }

    public function exportBookings($filters = [])
    {
        $query = Booking::with('user');

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['booking_type'])) {
            $query->where('booking_type', $filters['booking_type']);
        }

        if (isset($filters['route'])) {
            $query->where('departure_airport', $filters['route']['from'])
                  ->where('arrival_airport', $filters['route']['to']);
        }

        $bookings = $query->get();

        $csvData = [];
        $csvData[] = [
            'Booking Reference', 'User', 'Email', 'Type', 'From', 'To', 
            'Departure', 'Arrival', 'Amount', 'Currency', 'Status', 
            'Payment Status', 'Created At'
        ];

        foreach ($bookings as $booking) {
            $csvData[] = [
                $booking->booking_reference,
                $booking->user->name,
                $booking->user->email,
                $booking->booking_type,
                $booking->departure_airport ?? 'N/A',
                $booking->arrival_airport ?? 'N/A',
                $booking->departure_time ? $booking->departure_time->format('Y-m-d H:i:s') : 'N/A',
                $booking->arrival_time ? $booking->arrival_time->format('Y-m-d H:i:s') : 'N/A',
                $booking->amount,
                $booking->currency,
                $booking->status,
                $booking->payment_status,
                $booking->created_at->format('Y-m-d H:i:s')
            ];
        }

        return $this->generateCSV($csvData, 'bookings_export_' . now()->format('Y-m-d'));
    }

    public function exportPayments($filters = [])
    {
        $query = Booking::with('user')->whereNotNull('transaction_id');

        if (isset($filters['date_from'])) {
            $query->where('updated_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('updated_at', '<=', $filters['date_to']);
        }

        if (isset($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        $payments = $query->get();

        $csvData = [];
        $csvData[] = [
            'Transaction ID', 'Booking Reference', 'User', 'Amount', 
            'Currency', 'Payment Method', 'Status', 'Processed At'
        ];

        foreach ($payments as $payment) {
            $csvData[] = [
                $payment->transaction_id,
                $payment->booking_reference,
                $payment->user->name,
                $payment->amount,
                $payment->currency,
                $payment->payment_method,
                $payment->payment_status,
                $payment->updated_at->format('Y-m-d H:i:s')
            ];
        }

        return $this->generateCSV($csvData, 'payments_export_' . now()->format('Y-m-d'));
    }

    private function generateCSV($data, $filename)
    {
        $handle = fopen('php://temp', 'w+');
        
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return [
            'filename' => $filename . '.csv',
            'content' => $csvContent,
            'mime_type' => 'text/csv'
        ];
    }

    public function generateWeeklyReport()
    {
        $startDate = now()->startOfWeek()->subWeek();
        $endDate = now()->endOfWeek()->subWeek();

        $data = [
            'period' => $startDate->format('M j') . ' - ' . $endDate->format('M j, Y'),
            'total_bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'completed')->sum('amount'),
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'top_destinations' => Booking::select('arrival_airport', DB::raw('COUNT(*) as count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('arrival_airport')
                ->groupBy('arrival_airport')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'cancellation_rate' => $this->calculateCancellationRate($startDate, $endDate)
        ];

        return $data;
    }

    private function calculateCancellationRate($startDate, $endDate)
    {
        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
        $cancelledBookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'cancelled')->count();

        return $totalBookings > 0 ? round(($cancelledBookings / $totalBookings) * 100, 2) : 0;
    }

    public function sendWeeklyReportToAdmins()
    {
        $reportData = $this->generateWeeklyReport();
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new WeeklyReportMail($reportData));
        }
    }
}
