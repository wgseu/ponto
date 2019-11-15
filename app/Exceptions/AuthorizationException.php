<?php

namespace App\Exceptions;

class AuthorizationException extends Exception
{
    public function getCategory()
    {
        return 'authorization';
    }
}
