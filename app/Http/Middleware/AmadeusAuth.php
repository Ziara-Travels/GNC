<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\AccessTokenController;

class AmadeusAuth
{
    protected $tokenController;

    public function __construct(AccessTokenController $tokenController)
    {
        $this->tokenController = $tokenController;
    }

    public function handle($request, Closure $next)
    {
        try {
            $token = $this->tokenController->__invoke()->access_token;
            $request->headers->set('Authorization', 'Bearer ' . $token);
            
            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Authentication failed',
                'message' => $e->getMessage()
            ], 401);
        }
    }
}