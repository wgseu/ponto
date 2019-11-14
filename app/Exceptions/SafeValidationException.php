<?php

namespace App\Exceptions;

use GraphQL\Error\ClientAware;

class SafeValidationException extends \Exception implements ClientAware
{
    public $errors = [];

    public function __construct($errors = [], $code = 400)
    {
        $this->errors = $errors;
        reset($errors);
        parent::__construct(current($errors), $code);
    }

    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return 'businessLogic';
    }

    /**
     * Create a new validation exception from a plain array of messages.
     *
     * @param  array  $messages
     * @return static
     */
    public static function withMessages(array $messages)
    {
        return new static($messages);
    }
}
