<?php

declare(strict_types=1);

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class DateRangeFilter extends InputType
{
    protected $attributes = [
        'name' => 'DateRangeFilter',
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
