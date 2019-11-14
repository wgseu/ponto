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

use App\Models\Empresa;
use App\Models\Sistema;
use App\Util\Filter;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class SistemaQuery extends Query
{
    protected $attributes = [
        'name' => 'sistema',
    ];

    public function type(): Type
    {
        return GraphQL::type('Sistema');
    }

    public function args(): array
    {
        return [
            'all' => [
                'name' => 'all',
                'type' => Type::boolean(),
                'description' => 'Se verdadeiro devolve todas as opções do sistema',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $sistema = Sistema::find('1');
        $sistema->loadOptions();
        $sistema_data = $sistema->toArray();
        $sistema_data['opcoes'] = json_encode(Filter::emptyObject(
            $sistema->options->getValues($args['all'] ?? false)
        ));
        $empresa = Empresa::find('1');
        if (is_null($empresa)) {
            return $sistema_data;
        }
        $empresa_data = $empresa->toArray();
        $pais = $empresa->pais;
        if (!is_null($pais)) {
            $empresa_data['pais'] = $pais->toArray();
            $empresa_data['pais']['moeda'] = $pais->moeda->toArray();
        }
        $cliente_empresa = $empresa->empresa;
        if (!is_null($cliente_empresa)) {
            $empresa_data['empresa'] = $cliente_empresa->toArray();
        }
        $sistema_data['empresa'] = $empresa_data;
        return $sistema_data;
    }
}
