<?php

declare(strict_types=1);

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DateFilter extends InputType
{
    protected $attributes = [
        'name' => 'DateFilter',
    ];

    public function fields(): array
    {
        return [
            'eq' => [
                'type' => GraphQL::type('DateTime'),
            ],
            'ne' => [
                'type' => GraphQL::type('DateTime'),
            ],
            'before' => [
                'type' => GraphQL::type('DateTime'),
            ],
            'after' => [
                'type' => GraphQL::type('DateTime'),
            ],
            'from' => [
                'type' => GraphQL::type('DateTime'),
            ],
            'to' => [
                'type' => GraphQL::type('DateTime'),
            ],
            'between' => [
                'type' => GraphQL::type('DateRangeFilter'),
            ],
        ];
    }
}
