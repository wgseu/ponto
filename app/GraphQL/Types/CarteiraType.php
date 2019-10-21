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

namespace App\GraphQL\Types;

use App\Models\Carteira;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CarteiraType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Carteira',
        'description' => 'Informa uma conta bancária ou uma carteira financeira',
        'model' => Carteira::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Código local da carteira',
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('CarteiraTipo')),
                'description' => 'Tipo de carteira, \'Bancaria\' para conta bancária, \'Financeira\' para carteira financeira da empresa ou de sites de pagamentos, \'Credito\' para cartão de crédito e \'Local\' para caixas e cofres locais',
            ],
            'carteira_id' => [
                'type' => Type::int(),
                'description' => 'Informa a carteira superior, exemplo: Banco e cartões como subcarteira',
            ],
            'banco_id' => [
                'type' => Type::int(),
                'description' => 'Código local do banco quando a carteira for bancária',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Descrição da carteira, nome dado a carteira cadastrada',
            ],
            'conta' => [
                'type' => Type::string(),
                'description' => 'Número da conta bancária ou usuário da conta de acesso da carteira',
            ],
            'agencia' => [
                'type' => Type::string(),
                'description' => 'Número da agência da conta bancária ou site da carteira financeira',
            ],
            'transacao' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Valor cobrado pela operadora de pagamento para cada transação',
            ],
            'limite' => [
                'type' => Type::float(),
                'description' => 'Limite de crédito',
            ],
            'token' => [
                'type' => Type::string(),
                'description' => 'Token para integração de pagamentos',
            ],
            'ambiente' => [
                'type' => GraphQL::type('CarteiraAmbiente'),
                'description' => 'Ambiente de execução da API usando o token',
            ],
            'logo_url' => [
                'type' => Type::string(),
                'description' => 'Logo do gateway de pagamento',
            ],
            'cor' => [
                'type' => Type::string(),
                'description' => 'Cor predominante da marca da instituição',
            ],
            'ativa' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se a carteira ou conta bancária está ativa',
            ],
            'data_desativada' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'null',
            ],
        ];
    }
}
