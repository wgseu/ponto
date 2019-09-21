<?php

declare(strict_types=1);

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\InputType;

class DateFilter extends InputType
{
    protected $attributes = [
        'name' => 'DateFilter',
    ];

    public function fields(): array
    {
        return [
            'eq' => [
                'type' => Type::string(),
            ],
            'before' => [
                'type' => Type::string(),
            ],
            'after' => [
                'type' => Type::string(),
            ],
            'from' => [
                'type' => Type::string(),
            ],
            'to' => [
                'type' => Type::string(),
            ],
            'between' => [
                'type' => GraphQL::type('DateRangeFilter'),
            ],
        ];
    }
}
