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

class EmitenteInput extends InputType
{
    protected $attributes = [
        'name' => 'EmitenteInput',
        'description' => 'Dados do emitente das notas fiscais',
    ];

    public function fields(): array
    {
        return [
            'contador_id' => [
                'type' => Type::id(),
                'description' => 'Contador responsável pela contabilidade da empresa',
            ],
            'regime_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Regime tributário da empresa',
            ],
            'ambiente' => [
                'type' => GraphQL::type('EmitenteAmbiente'),
                'description' => 'Ambiente de emissão das notas',
            ],
            'csc_teste' => [
                'type' => Type::string(),
                'description' => 'Código de segurança do contribuinte',
                'rules' => ['max:100'],
            ],
            'csc' => [
                'type' => Type::string(),
                'description' => 'Código de segurança do contribuinte',
                'rules' => ['max:100'],
            ],
            'token_teste' => [
                'type' => Type::string(),
                'description' => 'Token do código de segurança do contribuinte',
                'rules' => ['max:10'],
            ],
            'token' => [
                'type' => Type::string(),
                'description' => 'Token do código de segurança do contribuinte',
                'rules' => ['max:10'],
            ],
            'ibpt' => [
                'type' => Type::string(),
                'description' => 'Token da API do IBPT',
                'rules' => ['max:100'],
            ],
            'data_expiracao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de expiração do certificado',
            ],
        ];
    }
}
