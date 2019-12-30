<?php

declare(strict_types=1);

namespace App\GraphQL\Filters;

use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DateRangeFilter extends InputType
{
    protected $attributes = [
        'name' => 'DateRangeFilter',
    ];

    public function fields(): array
    {
        return [
            'start' => [
                'type' => GraphQL::type('DateTime'),
            ],
            'end' => [
                'type' => GraphQL::type('DateTime'),
            ],
        ];
    }
}
