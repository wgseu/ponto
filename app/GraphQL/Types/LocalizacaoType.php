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

namespace App\GraphQL\Types;

use App\Models\Localizacao;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class LocalizacaoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Localizacao',
        'description' => 'Endereço detalhado de um cliente',
        'model' => Localizacao::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do endereço',
            ],
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
            ],
            'logradouro' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da rua ou avenida',
            ],
            'numero' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Número da casa ou do condomínio',
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('LocalizacaoTipo')),
                'description' => 'Tipo de endereço Casa ou Apartamento',
            ],
            'complemento' => [
                'type' => Type::string(),
                'description' => 'Complemento do endereço, Ex.: Loteamento Sul',
            ],
            'condominio' => [
                'type' => Type::string(),
                'description' => 'Nome do condomínio',
            ],
            'bloco' => [
                'type' => Type::string(),
                'description' => 'Número do bloco quando for apartamento',
            ],
            'apartamento' => [
                'type' => Type::string(),
                'description' => 'Número do apartamento',
            ],
            'referencia' => [
                'type' => Type::string(),
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
                'description' => 'Ex.: Minha Casa, Casa da Amiga',
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Informa a data que essa localização foi removida',
            ],
        ];
    }
}
