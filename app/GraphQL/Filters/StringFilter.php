<?php

declare(strict_types=1);

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class StringFilter extends InputType
{
    protected $attributes = [
        'name' => 'StringFilter',
    ];

    public function fields(): array
    {
        return [
            'eq' => [
                'type' => Type::string(),
            ],
            'like' => [
                'type' => Type::string(),
                'description' => 'Text like give input',
            ],
            'startsWith' => [
                'type' => Type::string(),
                'description' => 'Text start with give input',
            ],
            'contains' => [
                'type' => Type::string(),
                'description' => 'Text contains give input',
            ],
        ];
    }
}
