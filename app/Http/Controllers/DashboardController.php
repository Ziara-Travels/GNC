<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController - Generic dashboard controller
 * Delegates to role-specific dashboard controllers
 */
class DashboardController extends Controller
{
    /**
     * Business intelligence dashboard
     */
    public function businessIntelligence(Request $request)
    {
        return app(AdminDashboardController::class)->businessIntelligence($request);
    }

    /**
     * Export business intelligence data
     */
    public function exportBusinessIntelligence(Request $request)
    {
        return app(AdminDashboardController::class)->exportBusinessIntelligence($request);
    }

    /**
     * Get analytics data
     */
    public function getAnalyticsData(Request $request)
    {
        return app(AdminDashboardController::class)->getAnalyticsData($request);
    }

    /**
     * Permissions index
     */
    public function permissionsIndex(Request $request)
    {
        return app(AdminDashboardController::class)->rolesPermissionsAll($request);
    }

    /**
     * Show a specific permission
     */
    public function showPermission(Request $request, $id)
    {
        return app(AdminDashboardController::class)->viewPermission($request, $id);
    }
}
