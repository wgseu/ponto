<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Funcao;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class FuncaoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'saveFuncao',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
        return GraphQL::type('funcao');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da função para atualização',
            ],
            'data' => [
                'type' => GraphQL::type('FuncaoInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $funcao = new Funcao();
        if (isset($args['id'])) {
            $funcao = Funcao::findOrFail($args['id']);
        }
        $funcao->fill($args['data']);
        $funcao->save();

        return $funcao;
    }
}
