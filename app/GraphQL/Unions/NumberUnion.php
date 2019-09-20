<?php

declare(strict_types=1);

namespace App\GraphQL\Unions;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\UnionType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class NumberUnion extends UnionType
{
    protected $attributes = [
        'name' => 'NumberFilter',
    ];

    public function types(): array
    {
        return [
            Type::float(),
            GraphQL::type('NumberInput'),
        ];
    }

    public function resolveType($value)
    {
        if (is_float($value)) {
            return Type::float();
        } else {
            return GraphQL::type('NumberInput');
        }
    }
}
