<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class DateRangeInput extends InputType
{
    protected $attributes = [
        'name' => 'DateRangeInput',
    ];

    public function fields(): array
    {
        return [
            'start' => [
                'type' => Type::string(),
            ],
            'end' => [
                'type' => Type::string(),
            ],
        ];
    }
}
