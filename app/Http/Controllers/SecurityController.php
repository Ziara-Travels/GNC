<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show recent account activity log for the authenticated user.
     */
    public function activity()
    {
        return view('security.activity', [
            'user' => Auth::user(),
        ]);
    }
}
