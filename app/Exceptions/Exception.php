<?php

namespace App\Exceptions;

use GraphQL\Error\ClientAware;

class Exception extends \Exception implements ClientAware
{
    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return 'generic';
    }
}
