<?php

namespace App\Traits;

use App\Http\Controllers\AccessTokenController;

trait HandlesAmadeusToken
{
    protected function getAmadeusToken()
    {
        $tokenController = app(AccessTokenController::class);
        return $tokenController();
    }

    protected function getAmadeusHeaders()
    {
        $token = $this->getAmadeusToken();
        return [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ];
    }
}