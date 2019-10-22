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

class LocalizacaoInput extends InputType
{
    protected $attributes = [
        'name' => 'LocalizacaoInput',
        'description' => 'Endereço detalhado de um cliente',
    ];

    public function fields(): array
    {
        return [
            'cliente_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Cliente a qual esse endereço pertence',
            ],
            'bairro_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Bairro do endereço',
            ],
            'zona_id' => [
                'type' => Type::id(),
                'description' => 'Informa a zona do bairro',
            ],
            'cep' => [
                'type' => Type::string(),
                'description' => 'Código dos correios para identificar um logradouro',
                'rules' => ['max:8'],
            ],
            'logradouro' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da rua ou avenida',
                'rules' => ['max:100'],
            ],
            'numero' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Número da casa ou do condomínio',
                'rules' => ['max:20'],
            ],
            'tipo' => [
                'type' => GraphQL::type('LocalizacaoTipo'),
                'description' => 'Tipo de endereço Casa ou Apartamento',
            ],
            'complemento' => [
                'type' => Type::string(),
                'description' => 'Complemento do endereço, Ex.: Loteamento Sul',
                'rules' => ['max:100'],
            ],
            'condominio' => [
                'type' => Type::string(),
                'description' => 'Nome do condomínio',
                'rules' => ['max:100'],
            ],
            'bloco' => [
                'type' => Type::string(),
                'description' => 'Número do bloco quando for apartamento',
                'rules' => ['max:20'],
            ],
            'apartamento' => [
                'type' => Type::string(),
                'description' => 'Número do apartamento',
                'rules' => ['max:20'],
            ],
            'referencia' => [
                'type' => Type::string(),
                'description' => 'Ponto de referência para chegar ao local',
                'rules' => ['max:200'],
            ],
            'latitude' => [
                'type' => Type::float(),
                'description' => 'Ponto latitudinal para localização em um mapa',
            ],
            'longitude' => [
                'type' => Type::float(),
                'description' => 'Ponto longitudinal para localização em um mapa',
            ],
            'apelido' => [
                'type' => Type::string(),
                'description' => 'Ex.: Minha Casa, Casa da Amiga',
                'rules' => ['max:45'],
            ],
        ];
    }
}
