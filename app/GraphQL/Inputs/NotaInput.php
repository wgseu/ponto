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

class NotaInput extends InputType
{
    protected $attributes = [
        'name' => 'NotaInput',
        'description' => 'Notas fiscais e inutilizações',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da nota',
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('NotaTipo')),
                'description' => 'Tipo de registro se nota ou inutilização',
            ],
            'ambiente' => [
                'type' => Type::nonNull(GraphQL::type('NotaAmbiente')),
                'description' => 'Ambiente em que a nota foi gerada',
            ],
            'acao' => [
                'type' => Type::nonNull(GraphQL::type('NotaAcao')),
                'description' => 'Ação que deve ser tomada sobre a nota fiscal',
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('NotaEstado')),
                'description' => 'Estado da nota',
            ],
            'ultimo_evento_id' => [
                'type' => Type::int(),
                'description' => 'Último evento da nota',
            ],
            'serie' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Série da nota',
            ],
            'numero_inicial' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Número inicial da nota',
            ],
            'numero_final' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Número final da nota, igual ao número inicial quando for a nota de um pedido',
            ],
            'sequencia' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Permite iniciar o número da nota quando alcançar 999.999.999, deve ser incrementado sempre que alcançar',
            ],
            'chave' => [
                'type' => Type::string(),
                'rules' => ['max:50'],
                'description' => 'Chave da nota fiscal',
            ],
            'recibo' => [
                'type' => Type::string(),
                'rules' => ['max:50'],
                'description' => 'Recibo de envio para consulta posterior',
            ],
            'protocolo' => [
                'type' => Type::string(),
                'rules' => ['max:80'],
                'description' => 'Protocolo de autorização da nota fiscal',
            ],
            'pedido_id' => [
                'type' => Type::int(),
                'description' => 'Pedido da nota',
            ],
            'motivo' => [
                'type' => Type::string(),
                'rules' => ['max:255'],
                'description' => 'Motivo do cancelamento, contingência ou inutilização',
            ],
            'contingencia' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se a nota está em contingência',
            ],
            'consulta_url' => [
                'type' => Type::string(),
                'rules' => ['max:255'],
                'description' => 'URL de consulta da nota fiscal',
            ],
            'qrcode' => [
                'type' => Type::string(),
                'rules' => ['max:65535'],
                'description' => 'Dados do QRCode da nota',
            ],
            'tributos' => [
                'type' => Type::float(),
                'description' => 'Tributos totais da nota',
            ],
            'detalhes' => [
                'type' => Type::string(),
                'rules' => ['max:255'],
                'description' => 'Informações de interesse do contribuinte',
            ],
            'corrigido' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se os erros já foram corrigidos para retomada do processamento',
            ],
            'concluido' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se todos os processamentos da nota já foram realizados',
            ],
            'data_autorizacao' => [
                'type' => GraphQL::type('datetime'),
                'description' => 'Data de autorização da nota fiscal',
            ],
            'data_emissao' => [
                'type' => Type::nonNull(GraphQL::type('datetime')),
                'description' => 'Data de emissão da nota',
            ],
            'data_lancamento' => [
                'type' => Type::nonNull(GraphQL::type('datetime')),
                'description' => 'Data de lançamento da nota no sistema',
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('datetime'),
                'description' => 'Data em que a nota foi arquivada',
            ],
        ];
    }
}
