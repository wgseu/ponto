<?php

namespace Tests;

use Facebook\Facebook;

class FacebookMock extends Facebook
{
    public $token = 'fake_token';
    private $givenToken = null;

    /**
     * {@inheritDoc}
     */
    public function get($endpoint, $accessToken = null, $eTag = null, $graphVersion = null)
    {
        $this->givenToken = $accessToken;
        return $this;
    }

    public function getGraphUser()
    {
        if ($this->token != $this->givenToken) {
            return false;
        }
        return [
            'id' => resource_path('images/cards/elo.png'),
            'name' => 'Customer Name',
            'email' => 'fake@facebook.com',
        ];
    }
}
