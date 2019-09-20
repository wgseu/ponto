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
        'name' => 'Pais',
        'description' => 'Informações de um páis com sua moeda e língua nativa',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do país',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:100'],
                'description' => 'Nome do país',
            ],
            'sigla' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:10'],
                'description' => 'Abreviação do nome do país',
            ],
            'codigo' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:10'],
                'description' => 'Código do país com 2 letras',
            ],
            'moeda_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Informa a moeda principal do país',
            ],
            'idioma' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:10'],
                'description' => 'Idioma nativo do país',
            ],
            'prefixo' => [
                'type' => Type::string(),
                'rules' => ['max:45'],
                'description' => 'Prefixo de telefone para ligações internacionais',
            ],
            'entradas' => [
                'type' => Type::string(),
                'rules' => ['max:65535'],
                'description' => 'Frases, nomes de campos e máscaras específicas do país',
            ],
            'unitario' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o país tem apenas um estado federativo',
            ],
        ];
    }
}
