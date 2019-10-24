<?php

namespace App\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;

class MySafeException extends Exception implements ClientAware
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
