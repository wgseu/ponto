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

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class NotaFilter extends InputType
{
    protected $attributes = [
        'name' => 'NotaFilter',
        'description' => 'Notas fiscais e inutilizações',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('NotaTipoFilter')),
            ],
            'ambiente' => [
                'type' => Type::nonNull(GraphQL::type('NotaAmbienteFilter')),
            ],
            'acao' => [
                'type' => Type::nonNull(GraphQL::type('NotaAcaoFilter')),
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('NotaEstadoFilter')),
            ],
            'ultimo_evento_id' => [
                'type' => Type::int(),
            ],
            'serie' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'numero_inicial' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'numero_final' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'sequencia' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'chave' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'recibo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'protocolo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'pedido_id' => [
                'type' => Type::int(),
            ],
            'motivo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'contingencia' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'consulta_url' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'qrcode' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'tributos' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'detalhes' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'corrigido' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'concluido' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'data_autorizacao' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_emissao' => [
                'type' => Type::nonNull(GraphQL::type('DateFilter')),
            ],
            'data_lancamento' => [
                'type' => Type::nonNull(GraphQL::type('DateFilter')),
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
