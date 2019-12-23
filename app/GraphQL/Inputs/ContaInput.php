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

class ContaInput extends InputType
{
    protected $attributes = [
        'name' => 'ContaInput',
        'description' => 'Contas a pagar e ou receber',
    ];

    public function fields(): array
    {
        return [
            'classificacao_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Classificação da conta',
            ],
            'conta_id' => [
                'type' => Type::id(),
                'description' => 'Informa a conta principal',
            ],
            'agrupamento_id' => [
                'type' => Type::id(),
                'description' => 'Informa se esta conta foi agrupada e não precisa ser mais paga' .
                    ' individualmente, uma conta agrupada é tratada internamente como' .
                    ' desativada',
            ],
            'carteira_id' => [
                'type' => Type::id(),
                'description' => 'Informa a carteira que essa conta será paga automaticamente ou para' .
                    ' informar as contas a pagar dessa carteira',
            ],
            'cliente_id' => [
                'type' => Type::id(),
                'description' => 'Cliente a qual a conta pertence',
            ],
            'tipo' => [
                'type' => GraphQL::type('ContaTipo'),
                'description' => 'Tipo de conta se receita ou despesa',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Descrição da conta',
                'rules' => ['max:200'],
            ],
            'valor' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Valor da conta',
            ],
            'fonte' => [
                'type' => GraphQL::type('ContaFonte'),
                'description' => 'Fonte dos valores, comissão e remuneração se pagar antes do vencimento,' .
                    ' o valor será proporcional',
            ],
            'numero_parcela' => [
                'type' => Type::int(),
                'description' => 'Informa qual o número da parcela para esta conta',
            ],
            'parcelas' => [
                'type' => Type::int(),
                'description' => 'Quantidade de parcelas que essa conta terá, zero para conta recorrente e' .
                    ' será alterado para 1 quando criar a próxima conta',
            ],
            'frequencia' => [
                'type' => Type::int(),
                'description' => 'Frequência da recorrência em dias ou mês, depende do modo de cobrança',
            ],
            'modo' => [
                'type' => GraphQL::type('ContaModo'),
                'description' => 'Modo de cobrança se diário ou mensal, a quantidade é definida em' .
                    ' frequencia',
            ],
            'automatico' => [
                'type' => Type::boolean(),
                'description' => 'Informa se o pagamento será automático após o vencimento, só ocorrerá se' .
                    ' tiver saldo na carteira, usado para débito automático',
            ],
            'acrescimo' => [
                'type' => Type::float(),
                'description' => 'Acréscimo de valores ao total',
            ],
            'multa' => [
                'type' => Type::float(),
                'description' => 'Valor da multa em caso de atraso',
            ],
            'juros' => [
                'type' => Type::float(),
                'description' => 'Juros diário em caso de atraso, valor de 0 a 1, 1 = 100%',
            ],
            'formula' => [
                'type' => GraphQL::type('ContaFormula'),
                'description' => 'Fórmula de juros que será cobrado em caso de atraso',
            ],
            'vencimento' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Data de vencimento da conta',
            ],
            'numero' => [
                'type' => Type::string(),
                'description' => 'Número do documento que gerou a conta',
                'rules' => ['max:64'],
            ],
            'anexo_url' => [
                'type' => Type::string(),
                'description' => 'Caminho do anexo da conta',
                'rules' => ['max:200'],
            ],
            'anexo' => [
                'type' => Type::string(),
                'description' => 'Base64 do anexo da conta a ser alterada/cadastrada',
            ],
            'estado' => [
                'type' => GraphQL::type('ContaEstado'),
                'description' => 'Informa o estado da conta',
            ],
            'data_emissao' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Data de emissão da conta',
            ],
        ];
    }
}
