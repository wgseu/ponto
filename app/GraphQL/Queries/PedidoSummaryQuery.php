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

namespace App\GraphQL\Queries;

use App\Models\Pedido;
use App\GraphQL\Utils\Filter;
use App\GraphQL\Utils\Ordering;
use Closure;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PedidoSummaryQuery extends Query
{
    protected $attributes = [
        'name' => 'pedido',
    ];

    public function authorize(array $args): bool
    {
        $pedido = Pedido::findOrFail($args['id']);
        return Auth::check() && (
            $pedido->cliente_id == Auth::user()->id
            || Auth::user()->can('pedido:view')
        );
    }

    public function type(): Type
    {
        return GraphQL::type('PedidoSummary');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Código do pedido',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $pedido = Pedido::findOrFail($args['id']);
        $itens = $pedido->itens;
        $itens_data = [];
        foreach ($itens as $item) {
            $item_data = $item->toArray();
            $item_data['produto'] = is_null($item->produto_id) ? null : $item->produto->toArray();
            $item_data['servico'] = is_null($item->servico_id) ? null : $item->servico->toArray();
            $itens_data[] = $item_data;
        }
        $pagamentos = $pedido->pagamentos;
        $pagamentos_data = [];
        foreach ($pagamentos as $pagamento) {
            $pagamento_data = $pagamento->toArray();
            $pagamento_data['forma'] = $pagamento->forma->toArray();
            $pagamento_data['cartao'] = is_null($pagamento->cartao_id) ? null : $pagamento->cartao->toArray();
            $pagamentos_data[] = $item_data;
        }
        $pedido_data = $pedido->toArray();
        $pedido_data['cliente'] = is_null($pedido->cliente_id) ? null : $pedido->cliente->toArray();
        $pedido_data['entrega'] = is_null($pedido->entrega_id) ? null : $pedido->entrega->toArray();
        $pedido_data['localizacao'] = is_null($pedido->localizacao_id) ? null : $pedido->localizacao->toArray();
        $pedido_data['itens'] = $itens_data;
        $pedido_data['pagamentos'] = $pagamentos_data;
        return $pedido_data;
    }
}
