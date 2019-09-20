<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class StringInput extends InputType
{
    protected $attributes = [
        'name' => 'StringInput',
    ];

    public function fields(): array
    {
        return [
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
