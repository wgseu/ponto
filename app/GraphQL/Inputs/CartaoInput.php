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
use Rebing\GraphQL\Support\InputType;

class CartaoInput extends InputType
{
    protected $attributes = [
        'name' => 'CartaoInput',
        'description' => 'Cartões utilizados na forma de pagamento em cartão',
    ];

    public function fields(): array
    {
        return [
            'forma_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Forma de pagamento associada à esse cartão ou vale',
            ],
            'carteira_id' => [
                'type' => Type::id(),
                'description' => 'Carteira de entrada de valores no caixa',
            ],
            'bandeira' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da bandeira do cartão',
                'rules' => ['max:50'],
            ],
            'taxa' => [
                'type' => Type::float(),
                'description' => 'Taxa em porcentagem cobrado sobre o total do pagamento, valores de 0 a' .
                    ' 100',
            ],
            'dias_repasse' => [
                'type' => Type::int(),
                'description' => 'Quantidade de dias para repasse do valor',
            ],
            'taxa_antecipacao' => [
                'type' => Type::float(),
                'description' => 'Taxa em porcentagem para antecipação de recebimento de parcelas',
            ],
            'imagem_url' => [
                'type' => Type::string(),
                'description' => 'Imagem do cartão',
                'rules' => ['max:100'],
            ],
            'imagem' => [
                'type' => Type::string(),
                'description' => 'Base64 da imagem do cartão a ser alterada/cadastrada',
            ],
            'ativo' => [
                'type' => Type::boolean(),
                'description' => 'Informa se o cartão está ativo',
            ],
        ];
    }
}
