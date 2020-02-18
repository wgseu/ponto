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

namespace App\GraphQL\Queries;

use App\Models\Cupom;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use App\Exceptions\ValidationException;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CupomSearchQuery extends Query
{
    protected $attributes = [
        'name' => 'cupom',
    ];

    public function type(): Type
    {
        return GraphQL::type('Cupom');
    }

    public function args(): array
    {
        return [
            'codigo' => ['name' => 'codigo', 'type' => Type::nonNull(Type::string()), 'rules' => ['min:2']],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Cupom::where('codigo', $args['codigo'])
            ->whereNull('cliente_id')
            ->where('disponivel', '>', 0)
            ->where('validade', '>=', Carbon::now())
            ->where('cancelado', false);
        $cupom = $query->firstOrFail();
        if (auth()->check()) {
            $test = $cupom->replicate();
            $test->cliente_id = auth()->user()->id;
            if (!empty($error = $test->checkOrder())) {
                throw new ValidationException($error);
            }
        }
        return $cupom;
    }
}
