    /**
     * Get dashboard statistics for AJAX requests
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardStats()
    {
        return response()->json([
            'booking_stats' => $this->getBookingStats(),
            'payment_stats' => $this->getPaymentStats(),
            'booking_trends' => $this->getBookingTrends(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
    }
    
    /**
     * Get payment statistics
     * 
     * @return array
     */
    private function getPaymentStats()
    {
        return \Cache::remember('payment_stats_all', 5, function () {
            return [
                'total' => \App\Models\Payment::count(),
                'completed' => \App\Models\Payment::where('status', 'completed')->count(),
                'failed' => \App\Models\Payment::where('status', 'failed')->count(),
                'pending' => \App\Models\Payment::where('status', 'pending')->count(),
                'last_updated' => now()->format('Y-m-d H:i:s')
            ];
        });
    }
    
    /**
     * Get booking trends for charts
     * 
     * @return array
     */
    private function getBookingTrends()
    {
        return \Cache::remember('booking_trends', 15, function () {
            // Get bookings for last 30 days grouped by date
            $startDate = now()->subDays(30)->startOfDay();
            $bookings = \App\Models\Booking::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date')
                ->toArray();
            
            // Fill in missing dates
            $result = [];
            for ($i = 0; $i <= 30; $i++) {
                $date = now()->subDays(30 - $i)->format('Y-m-d');
                $result[] = [
                    'x' => $date,
                    'y' => $bookings[$date] ?? 0
                ];
            }
            
            return $result;
        });
    }
