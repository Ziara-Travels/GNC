<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    // public function register(): void
    // {
    //     $this->reportable(function (Throwable $e) {
    //         // Optional: Add additional reporting logic here
    //     });

    //     // Handle 404 errors
    //     $this->renderable(function (NotFoundHttpException $e, $request) {
    //         if ($request->is('api/*')) {
    //             return response()->json([
    //                 'message' => 'Resource not found.'
    //             ], 404);
    //         }
            
    //         // Check if the request is for dashboard
    //         if ($this->isRequestForDashboard($request)) {
    //             // For logged in users in dashboard context, use dashboard error view
    //             return response()->view('dashboard.error.404', [], 404);
    //         }
            
    //         // For regular users, use public error view
    //         return response()->view('error.404', [], 404);
    //     });

    //     // Handle 500 errors
    //     $this->renderable(function (Throwable $e, $request) {
    //         if ($this->shouldReport($e) && !$this->isHttpException($e)) {
    //             if ($request->is('api/*')) {
    //                 return response()->json([
    //                     'message' => 'Server error. Please try again later.'
    //                 ], 500);
    //             }
                
    //             // Check if the request is for dashboard
    //             if ($this->isRequestForDashboard($request)) {
    //                 // For logged in users in dashboard context, use dashboard error view
    //                 return response()->view('dashboard.error.500', [
    //                     'exception' => $e,
    //                 ], 500);
    //             }
                
    //             // For regular users, use public error view
    //             return response()->view('error.500', [
    //                 'exception' => $e,
    //             ], 500);
    //         }
    //     });

    //     // Handle method not allowed errors
    //     $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
    //         if ($request->is('api/*')) {
    //             return response()->json([
    //                 'message' => 'Method not allowed.'
    //             ], 405);
    //         }
            
    //         // Check if the request is for dashboard
    //         if ($this->isRequestForDashboard($request)) {
    //             return response()->view('dashboard.error.500', [], 405);
    //         }
            
    //         return response()->view('error.500', [], 405);
    //     });

    //     // Handle model not found errors
    //     $this->renderable(function (ModelNotFoundException $e, $request) {
    //         if ($request->is('api/*')) {
    //             return response()->json([
    //                 'message' => 'Resource not found.'
    //             ], 404);
    //         }
            
    //         // Check if the request is for dashboard
    //         if ($this->isRequestForDashboard($request)) {
    //             return response()->view('dashboard.error.404', [], 404);
    //         }
            
    //         return response()->view('error.404', [], 404);
    //     });

    //     $this->renderable(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
    //         \Log::warning('Authorization exception: ' . $e->getMessage());
            
    //         if ($request->is('api/*')) {
    //             return response()->json(['message' => 'Unauthorized action'], 403);
    //         }
            
    //         if (Auth::check()) {
    //             $user = Auth::user();
    //             \Log::warning('User attempted unauthorized access: ID=' . $user->id . ', Role=' . $user->role);
    //         }
            
    //         return response()->view('error.404', ['exception' => $e], 403);
    //     });
    // }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }

    /**
     * Determine if the request is for the dashboard area.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isRequestForDashboard($request)
    {
        // Check if request path starts with dashboard or admin paths
        $isDashboardPath = $request->is('dashboard*') || 
                          $request->is('admin*') || 
                          $request->is('superadmin*') || 
                          $request->is('user/dashboard*');

        // Check if user is authenticated
        $isAuthenticated = Auth::check();

        // Only consider it a dashboard request if both conditions are true
        return $isDashboardPath && $isAuthenticated;
    }
}