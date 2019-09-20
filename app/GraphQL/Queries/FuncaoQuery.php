<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Funcao;
use Closure;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class FuncaoQuery extends Query
{
    protected $attributes = [
        'name' => 'funcao',
        'description' => 'A query'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('funcao'));
    }

    public function args(): array
    {
        return [
            'descricao' => ['name' => 'descricao', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $funcoes = Funcao::select($select)->with($with);
        return $funcoes->get();
    }
}
