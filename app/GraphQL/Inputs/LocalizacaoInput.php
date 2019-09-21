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
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do endereço',
            ],
            'cliente_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Cliente a qual esse endereço pertence',
            ],
            'bairro_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Bairro do endereço',
            ],
            'zona_id' => [
                'type' => Type::int(),
                'description' => 'Informa a zona do bairro',
            ],
            'cep' => [
                'type' => Type::string(),
                'rules' => ['max:8'],
                'description' => 'Código dos correios para identificar um logradouro',
            ],
            'logradouro' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:100'],
                'description' => 'Nome da rua ou avenida',
            ],
            'numero' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:20'],
                'description' => 'Número da casa ou do condomínio',
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('LocalizacaoTipo')),
                'description' => 'Tipo de endereço Casa ou Apartamento',
            ],
            'complemento' => [
                'type' => Type::string(),
                'rules' => ['max:100'],
                'description' => 'Complemento do endereço, Ex.: Loteamento Sul',
            ],
            'condominio' => [
                'type' => Type::string(),
                'rules' => ['max:100'],
                'description' => 'Nome do condomínio',
            ],
            'bloco' => [
                'type' => Type::string(),
                'rules' => ['max:20'],
                'description' => 'Número do bloco quando for apartamento',
            ],
            'apartamento' => [
                'type' => Type::string(),
                'rules' => ['max:20'],
                'description' => 'Número do apartamento',
            ],
            'referencia' => [
                'type' => Type::string(),
                'rules' => ['max:200'],
                'description' => 'Ponto de referência para chegar ao local',
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
                'rules' => ['max:45'],
                'description' => 'Ex.: Minha Casa, Casa da Amiga',
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Informa a data que essa localização foi removida',
            ],
        ];
    }
}
