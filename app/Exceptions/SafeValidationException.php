<?php

namespace App\Exceptions;

use GraphQL\Error\ClientAware;
use Illuminate\Validation\ValidationException;

class SafeValidationException extends ValidationException implements ClientAware
{
    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return 'businessLogic';
    }
}
