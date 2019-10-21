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

use App\Models\Promocao;
use Rebing\GraphQL\Support\EnumType;

class PromocaoLocalEnum extends EnumType
{
    protected $attributes = [
        'name' => 'PromocaoLocal',
        'description' => 'Local onde o preço será aplicado',
        'values' => [
            Promocao::LOCAL_LOCAL => Promocao::LOCAL_LOCAL,
            Promocao::LOCAL_MESA => Promocao::LOCAL_MESA,
            Promocao::LOCAL_COMANDA => Promocao::LOCAL_COMANDA,
            Promocao::LOCAL_BALCAO => Promocao::LOCAL_BALCAO,
            Promocao::LOCAL_ENTREGA => Promocao::LOCAL_ENTREGA,
            Promocao::LOCAL_ONLINE => Promocao::LOCAL_ONLINE,
        ],
    ];
}
