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

namespace App\GraphQL\Inputs;

use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class ServicoInput extends InputType
{
    protected $attributes = [
        'name' => 'ServicoInput',
        'description' => 'Taxas, eventos e serviço cobrado nos pedidos',
    ];

    public function fields(): array
    {
        return [
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome do serviço, Ex.: Comissão, Entrega, Couvert',
                'rules' => ['max:50'],
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Descrição do serviço, Ex.: Show de fulano',
                'rules' => ['max:100'],
            ],
            'detalhes' => [
                'type' => Type::string(),
                'description' => 'Detalhes do serviço, Ex.: Com participação especial de fulano',
                'rules' => ['max:200'],
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('ServicoTipo')),
                'description' => 'Tipo de serviço, Evento: Eventos como show no estabelecimento',
            ],
            'obrigatorio' => [
                'type' => Type::boolean(),
                'description' => 'Informa se a taxa é obrigatória',
            ],
            'data_inicio' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de início do evento',
            ],
            'data_fim' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data final do evento',
            ],
            'tempo_limite' => [
                'type' => Type::int(),
                'description' => 'Tempo de participação máxima que não será obrigatório adicionar o serviço ao pedido',
            ],
            'valor' => [
                'type' => Type::float(),
                'description' => 'Valor do serviço',
            ],
            'individual' => [
                'type' => Type::boolean(),
                'description' => 'Informa se a taxa ou serviço é individual para cada pessoa',
            ],
            'imagem_url' => [
                'type' => Type::string(),
                'description' => 'Banner do evento',
                'rules' => ['max:100'],
            ],
            'ativo' => [
                'type' => Type::boolean(),
                'description' => 'Informa se o serviço está ativo',
            ],
        ];
    }
}
