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

class EmpresaInput extends InputType
{
    protected $attributes = [
        'name' => 'EmpresaInput',
        'description' => 'Informações da empresa',
    ];

    public function fields(): array
    {
        return [
            'pais_id' => [
                'type' => Type::int(),
                'description' => 'País em que a empresa está situada',
            ],
            'empresa_id' => [
                'type' => Type::int(),
                'description' => 'Informa a empresa do cadastro de clientes, a empresa deve ser um cliente do tipo pessoa jurídica',
            ],
            'parceiro_id' => [
                'type' => Type::int(),
                'description' => 'Informa quem realiza o suporte do sistema, deve ser um cliente do tipo empresa que possua um acionista como representante',
            ],
            'opcoes' => [
                'type' => Type::string(),
                'description' => 'Opções gerais do sistema como opções de impressão e comportamento',
                'rules' => ['max:65535'],
            ],
        ];
    }
}
