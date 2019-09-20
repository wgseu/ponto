<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class NumberInput extends InputType
{
    protected $attributes = [
        'name' => 'NumberInput',
    ];

    public function fields(): array
    {
        return [
            'gt' => [
                'type' => Type::float(),
            ],
            'gte' => [
                'type' => Type::float(),
            ],
            'lt' => [
                'type' => Type::float(),
            ],
            'lte' => [
                'type' => Type::float(),
            ],
        ];
    }
}
