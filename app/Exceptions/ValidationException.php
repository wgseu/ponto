<?php

namespace App\Exceptions;

class ValidationException extends Exception
{
    public $errors = [];

    public function __construct($errors = [], $code = 400)
    {
        $this->errors = $errors;
        reset($errors);
        parent::__construct(current($errors), $code);
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
