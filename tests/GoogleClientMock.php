<?php

namespace Tests;

use Google_Client;

class GoogleClientMock extends Google_Client
{
    public $token = 'fake_token';

    public function verifyIdToken($idToken = null)
    {
        if ($this->token != $idToken) {
            return false;
        }
        return [
            'name' => 'Customer Name',
            'email' => 'fake@google.com',
            'picture' => resource_path('images/cards/elo.png'),
        ];
    }
}
