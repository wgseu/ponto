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

class HorarioInput extends InputType
{
    protected $attributes = [
        'name' => 'HorarioInput',
        'description' => 'Informa o horário de funcionamento do estabelecimento',
    ];

    public function fields(): array
    {
        return [
            'modo' => [
                'type' => GraphQL::type('HorarioModo'),
                'description' => 'Modo de trabalho disponível nesse horário, Funcionamento: horário em que o estabelecimento estará aberto, Operação: quando aceitar novos pedidos locais, Entrega: quando aceita ainda pedidos para entrega',
            ],
            'funcao_id' => [
                'type' => Type::int(),
                'description' => 'Permite informar o horário de acesso ao sistema para realizar essa função',
            ],
            'prestador_id' => [
                'type' => Type::int(),
                'description' => 'Permite informar o horário de prestação de serviço para esse prestador',
            ],
            'inicio' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Início do horário de funcionamento em minutos contando a partir de domingo até sábado',
            ],
            'fim' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Horário final de funcionamento do estabelecimento contando em minutos a partir de domingo',
            ],
            'mensagem' => [
                'type' => Type::string(),
                'description' => 'Mensagem que será mostrada quando o estabelecimento estiver fechado por algum motivo',
                'rules' => ['max:200'],
            ],
            'entrega_minima' => [
                'type' => Type::int(),
                'description' => 'Tempo mínimo que leva para entregar nesse horário',
            ],
            'entrega_maxima' => [
                'type' => Type::int(),
                'description' => 'Tempo máximo que leva para entregar nesse horário',
            ],
            'fechado' => [
                'type' => Type::boolean(),
                'description' => 'Informa se o estabelecimento estará fechado nesse horário programado, o início e fim será tempo no formato unix, quando verdadeiro tem prioridade sobre todos os horários',
            ],
        ];
    }
}
