<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Log activity in the system
     */
    public static function log($action, $description, $model = null, $modelId = null, $userId = null, $ipAddress = null)
    {
        try {
            Activity::create([
                'user_id' => $userId ?? Auth::id(),
                'action' => $action,
                'description' => $description,
                'model_type' => $model ? get_class($model) : null,
                'model_id' => $modelId ?? ($model ? $model->id : null),
                'ip_address' => $ipAddress ?? request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Get activities for dashboard
     */
    public function index(Request $request)
    {
        $query = Activity::with('user')
            ->select('activities.*')
            ->orderBy('created_at', 'desc');

        // Filter by user role
        if (auth()->user()->role !== 'superadmin') {
            if (auth()->user()->role === 'admin') {
                // Admins can see all activities except superadmin activities
                $query->whereHas('user', function ($q) {
                    $q->where('role', '!=', 'superadmin');
                });
            } else {
                // Users can only see their own activities
                $query->where('user_id', auth()->id());
            }
        }

        // Filter by date range
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);
        }

        // Filter by action
        if ($request->has('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }

        $activities = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
        }

        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Get recent notifications for user
     */
    public function getNotifications(Request $request)
    {
        $userId = auth()->id();
        $limit = $request->input('limit', 10);

        $notifications = Activity::where('user_id', $userId)
            ->whereIn('action', [
                'login', 'profile_updated', 'password_changed', 
                'flight_booked', 'hotel_booked', 'payment_completed',
                'booking_confirmed', 'report_downloaded'
            ])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'title' => $this->getNotificationTitle($activity->action),
                    'message' => $activity->description,
                    'icon' => $this->getNotificationIcon($activity->action),
                    'type' => $this->getNotificationType($activity->action),
                    'time' => $activity->created_at->diffForHumans(),
                    'read' => false // You can add a 'read' column to activities table
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $notifications->count()
        ]);
    }

    /**
     * Get activity statistics
     */
    public function getStatistics(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subDays(30));
        $dateTo = $request->input('date_to', now());

        $baseQuery = Activity::whereBetween('created_at', [$dateFrom, $dateTo]);

        // Apply role-based filtering
        if (auth()->user()->role !== 'superadmin') {
            if (auth()->user()->role === 'admin') {
                $baseQuery->whereHas('user', function ($q) {
                    $q->where('role', '!=', 'superadmin');
                });
            } else {
                $baseQuery->where('user_id', auth()->id());
            }
        }

        $statistics = [
            'total_activities' => $baseQuery->count(),
            'activities_by_action' => $baseQuery->select('action', DB::raw('count(*) as count'))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->get(),
            'activities_by_day' => $baseQuery->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get(),
            'top_users' => $baseQuery->with('user')
                ->select('user_id', DB::raw('count(*) as count'))
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Mark notifications as read
     */
    public function markAsRead(Request $request)
    {
        $notificationIds = $request->input('notification_ids', []);
        
        if (empty($notificationIds)) {
            // Mark all as read
            Activity::where('user_id', auth()->id())->update(['read_at' => now()]);
        } else {
            Activity::whereIn('id', $notificationIds)
                ->where('user_id', auth()->id())
                ->update(['read_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read'
        ]);
    }

    /**
     * Get notification title based on action
     */
    private function getNotificationTitle($action)
    {
        $titles = [
            'login' => 'Successful Login',
            'profile_updated' => 'Profile Updated',
            'password_changed' => 'Password Changed',
            'flight_booked' => 'Flight Booked',
            'hotel_booked' => 'Hotel Booked',
            'payment_completed' => 'Payment Completed',
            'booking_confirmed' => 'Booking Confirmed',
            'report_downloaded' => 'Report Downloaded'
        ];

        return $titles[$action] ?? ucfirst(str_replace('_', ' ', $action));
    }

    /**
     * Get notification icon based on action
     */
    private function getNotificationIcon($action)
    {
        $icons = [
            'login' => 'fas fa-sign-in-alt',
            'profile_updated' => 'fas fa-user-edit',
            'password_changed' => 'fas fa-key',
            'flight_booked' => 'fas fa-plane',
            'hotel_booked' => 'fas fa-bed',
            'payment_completed' => 'fas fa-credit-card',
            'booking_confirmed' => 'fas fa-check-circle',
            'report_downloaded' => 'fas fa-download'
        ];

        return $icons[$action] ?? 'fas fa-info-circle';
    }

    /**
     * Get notification type for styling
     */
    private function getNotificationType($action)
    {
        $types = [
            'login' => 'info',
            'profile_updated' => 'success',
            'password_changed' => 'warning',
            'flight_booked' => 'success',
            'hotel_booked' => 'success',
            'payment_completed' => 'success',
            'booking_confirmed' => 'success',
            'report_downloaded' => 'info'
        ];

        return $types[$action] ?? 'info';
    }

    /**
     * Export activities
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');
        
        $query = Activity::with('user')->orderBy('created_at', 'desc');

        // Apply role-based filtering
        if (auth()->user()->role !== 'superadmin') {
            if (auth()->user()->role === 'admin') {
                $query->whereHas('user', function ($q) {
                    $q->where('role', '!=', 'superadmin');
                });
            } else {
                $query->where('user_id', auth()->id());
            }
        }

        $activities = $query->get();

        if ($format === 'csv') {
            return $this->exportToCsv($activities);
        }

        return response()->json([
            'success' => false,
            'message' => 'Export format not supported'
        ], 400);
    }

    /**
     * Export activities to CSV
     */
    private function exportToCsv($activities)
    {
        $filename = 'activities_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($activities) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Date', 'Time', 'User', 'Action', 'Description', 
                'IP Address', 'User Agent'
            ]);

            // Data rows
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->created_at->format('Y-m-d'),
                    $activity->created_at->format('H:i:s'),
                    $activity->user ? $activity->user->name : 'System',
                    $activity->action,
                    $activity->description,
                    $activity->ip_address,
                    $activity->user_agent
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}