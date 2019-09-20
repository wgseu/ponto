<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\InputType;

class DateInput extends InputType
{
    protected $attributes = [
        'name' => 'DateInput',
    ];

    public function fields(): array
    {
        return [
            'before' => [
                'type' => Type::string(),
            ],
            'after' => [
                'type' => Type::string(),
            ],
            'between' => [
                'type' => GraphQL::type('DateRangeInput'),
            ],
        ];
    }
}
