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

class AuditoriaInput extends InputType
{
    protected $attributes = [
        'name' => 'Auditoria',
        'description' => 'Registra todas as atividades importantes do sistema',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da auditoria',
            ],
            'permissao_id' => [
                'type' => Type::int(),
                'description' => 'Informa a permissão concedida ou utilizada que permitiu a realização da operação',
            ],
            'prestador_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Prestador que exerceu a atividade',
            ],
            'autorizador_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Prestador que autorizou o acesso ao recurso descrito',
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('AuditoriaTipoEnum')),
                'description' => 'Tipo de atividade exercida',
            ],
            'prioridade' => [
                'type' => Type::nonNull(GraphQL::type('AuditoriaPrioridadeEnum')),
                'description' => 'Prioridade de acesso do recurso',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:255'],
                'description' => 'Descrição da atividade exercida',
            ],
            'autorizacao' => [
                'type' => Type::string(),
                'rules' => ['max:255'],
                'description' => 'Código de autorização necessário para permitir realizar a função descrita',
            ],
            'data_registro' => [
                'type' => Type::nonNull(GraphQL::type('datetime')),
                'description' => 'Data e hora do ocorrido',
            ],
        ];
    }
}
