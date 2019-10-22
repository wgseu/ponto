<?php

declare(strict_types=1);

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\InputType;

class IdFilter extends InputType
{
    protected $attributes = [
        'name' => 'IdFilter',
    ];

    public function fields(): array
    {
        return [
            'eq' => [
                'type' => Type::string(),
            ],
            'ne' => [
                'type' => Type::string(),
            ],
        ];
    }
}
