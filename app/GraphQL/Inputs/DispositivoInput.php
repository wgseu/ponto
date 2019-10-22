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

class DispositivoInput extends InputType
{
    protected $attributes = [
        'name' => 'DispositivoInput',
        'description' => 'Computadores e tablets com opções de acesso',
    ];

    public function fields(): array
    {
        return [
            'setor_id' => [
                'type' => Type::id(),
                'description' => 'Setor em que o dispositivo está instalado/será usado',
            ],
            'caixa_id' => [
                'type' => Type::id(),
                'description' => 'Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os dispositivos',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome do computador ou tablet em rede, único entre os dispositivos',
                'rules' => ['max:100'],
            ],
            'tipo' => [
                'type' => GraphQL::type('DispositivoTipo'),
                'description' => 'Tipo de dispositivo',
            ],
            'descricao' => [
                'type' => Type::string(),
                'description' => 'Descrição do dispositivo',
                'rules' => ['max:45'],
            ],
            'opcoes' => [
                'type' => Type::string(),
                'description' => 'Opções do dispositivo, Ex.: Balança, identificador de chamadas e outros',
                'rules' => ['max:65535'],
            ],
            'serial' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Serial do tablet para validação, único entre os dispositivos',
                'rules' => ['max:45'],
            ],
            'validacao' => [
                'type' => Type::string(),
                'description' => 'Validação do dispositivo',
                'rules' => ['max:40'],
            ],
        ];
    }
}
