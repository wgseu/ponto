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

namespace App\GraphQL\Enums;

use App\Models\Carteira;
use Rebing\GraphQL\Support\EnumType;

class CarteiraTipoEnum extends EnumType
{
    protected $attributes = [
        'name' => 'CarteiraTipo',
        'description' => 'Tipo de carteira, \'Bancaria\' para conta bancária, \'Financeira\' para carteira financeira da empresa ou de sites de pagamentos, \'Credito\' para cartão de crédito e \'Local\' para caixas e cofres locais',
        'values' => [
            Carteira::TIPO_BANCARIA => Carteira::TIPO_BANCARIA,
            Carteira::TIPO_FINANCEIRA => Carteira::TIPO_FINANCEIRA,
            Carteira::TIPO_CREDITO => Carteira::TIPO_CREDITO,
            Carteira::TIPO_LOCAL => Carteira::TIPO_LOCAL,
        ],
    ];
}