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

class PaisInput extends InputType
{
    protected $attributes = [
        'name' => 'PaisInput',
        'description' => 'Informações de um páis com sua moeda e língua nativa',
    ];

    public function fields(): array
    {
        return [
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome do país',
                'rules' => ['max:100'],
            ],
            'sigla' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Abreviação do nome do país',
                'rules' => ['max:10'],
            ],
            'codigo' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Código do país com 2 letras',
                'rules' => ['max:10'],
            ],
            'moeda_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Informa a moeda principal do país',
            ],
            'idioma' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Idioma nativo do país',
                'rules' => ['max:10'],
            ],
            'prefixo' => [
                'type' => Type::string(),
                'description' => 'Prefixo de telefone para ligações internacionais',
                'rules' => ['max:45'],
            ],
            'entradas' => [
                'type' => Type::string(),
                'description' => 'Frases, nomes de campos e máscaras específicas do país',
                'rules' => ['max:65535'],
            ],
            'unitario' => [
                'type' => Type::boolean(),
                'description' => 'Informa se o país tem apenas um estado federativo',
            ],
        ];
    }
}
