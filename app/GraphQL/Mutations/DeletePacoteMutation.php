<?php

/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Pacote;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DeletePacoteMutation extends Mutation
{
    protected $attributes = [
        'name' => 'DeletePacote',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && Auth::user()->can('pacote:delete');
    }

    public function type(): Type
    {
        return GraphQL::type('Pacote');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Identificador do pacote',
            ],
            'force' => [
                'type' => Type::boolean(),
                'description' => 'Força a remoção do pacote no banco',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $pacote = Pacote::withTrashed()->findOrFail($args['id']);
        if ($args['force'] ?? false) {
            $pacote->forceDelete();
        } else {
            $pacote->delete();
        }
        return $pacote;
    }
}
