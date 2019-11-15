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

use App\Models\Notificacao;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateNotificacaoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateNotificacao',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check();
    }

    public function type(): Type
    {
        return GraphQL::type('Notificacao');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Identificador da notificação',
            ],
            'visualizado' => [
                'type' => Type::boolean(),
                'description' => 'Marca ou desmarca a notificação como visualizada',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $notificacao = Notificacao::where('destinatario_id', Auth::user()->id)->findOrFail($args['id']);
        if ($args['visualizado'] ?? false) {
            $notificacao->data_visualizacao = Carbon::now();
        } else {
            $notificacao->data_visualizacao = null;
        }
        $notificacao->save();
        return $notificacao;
    }
}
