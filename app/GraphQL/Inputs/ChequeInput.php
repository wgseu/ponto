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

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\InputType;

class ChequeInput extends InputType
{
    protected $attributes = [
        'name' => 'ChequeInput',
        'description' => 'Folha de cheque lançado como pagamento',
    ];

    public function fields(): array
    {
        return [
            'cliente_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Cliente que emitiu o cheque',
            ],
            'banco_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Banco do cheque',
            ],
            'agencia' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Número da agência',
                'rules' => ['max:45'],
            ],
            'conta' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Número da conta do banco descrito no cheque',
                'rules' => ['max:45'],
            ],
            'numero' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Número da folha do cheque',
                'rules' => ['max:20'],
            ],
            'valor' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Valor na folha do cheque',
            ],
            'vencimento' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Data de vencimento do cheque',
            ],
            'cancelado' => [
                'type' => Type::boolean(),
                'description' => 'Informa se o cheque e todas as suas folhas estão cancelados',
            ],
            'recolhimento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de recolhimento do cheque',
            ],
        ];
    }
}
