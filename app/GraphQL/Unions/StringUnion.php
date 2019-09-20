<?php

declare(strict_types=1);

namespace App\GraphQL\Unions;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\UnionType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class StringUnion extends UnionType
{
    protected $attributes = [
        'name' => 'StringFilter',
    ];

    public function types(): array
    {
        return [
            Type::string(),
            GraphQL::type('StringInput'),
        ];
    }

    public function resolveType($value)
    {
        if (is_string($value)) {
            return Type::string();
        } else {
            return GraphQL::type('StringInput');
        }
    }
}
