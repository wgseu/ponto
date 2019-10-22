<?php

declare(strict_types=1);

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class NumberFilter extends InputType
{
    protected $attributes = [
        'name' => 'NumberFilter',
    ];

    public function fields(): array
    {
        return [
            'eq' => [
                'type' => Type::float(),
            ],
            'ne' => [
                'type' => Type::float(),
            ],
            'gt' => [
                'type' => Type::float(),
            ],
            'ge' => [
                'type' => Type::float(),
            ],
            'lt' => [
                'type' => Type::float(),
            ],
            'le' => [
                'type' => Type::float(),
            ],
            'between' => [
                'type' => GraphQL::type('NumberRangeFilter'),
            ],
        ];
    }
}
