<?php

namespace App\Exceptions;

class AuthenticationException extends Exception
{
    public function getCategory()
    {
        return 'authentication';
    }
}
