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

namespace App\GraphQL\Ordering;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class NotaOrder extends InputType
{
    protected $attributes = [
        'name' => 'NotaOrder',
        'description' => 'Notas fiscais e inutilizações',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'tipo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'ambiente' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'acao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'estado' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'ultimo_evento_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'serie' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'numero_inicial' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'numero_final' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'sequencia' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'chave' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'recibo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'protocolo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'pedido_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'motivo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'contingencia' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'consulta_url' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'qrcode' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'tributos' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'detalhes' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'corrigido' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'concluido' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_autorizacao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_emissao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_lancamento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
        ];
    }
}
