<?php

declare(strict_types=1);

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class NumberRangeFilter extends InputType
{
    protected $attributes = [
        'name' => 'NumberRangeFilter',
    ];

    public function fields(): array
    {
        return [
            'start' => [
                'type' => Type::float(),
            ],
            'end' => [
                'type' => Type::float(),
            ],
        ];
    }
}
