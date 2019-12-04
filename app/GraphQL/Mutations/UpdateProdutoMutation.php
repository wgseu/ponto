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

namespace App\GraphQL\Mutations;

use App\Models\Produto;
use App\Models\Cardapio;
use App\Models\Tributacao;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateProdutoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateProduto',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && Auth::user()->can('produto:update');
    }

    public function type(): Type
    {
        return GraphQL::type('Produto');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Código do produto',
            ],
            'input' => ['type' => Type::nonNull(GraphQL::type('ProdutoUpdateInput'))],
        ];
    }

    /**
     * Salva os cardápios enviados junto com os produtos
     *
     * @param array $cardapios_data
     * @param Produto $produto
     * @return void
     */
    private static function saveCardapios($cardapios_data, $produto)
    {
        foreach ($cardapios_data as $cardapio_data) {
            $cardapio = new Cardapio();
            if (isset($cardapio_data['id'])) {
                $cardapio = Cardapio::findOrFail($cardapio_data['id']);
            }
            $cardapio->fill($cardapio_data);
            $cardapio->produto_id = $produto->id;
            $cardapio->save();
        }
    }

    /**
     * Salva a tributação do produto
     *
     * @param array $tributacao_data
     * @param Produto $produto
     * @return Tributacao
     */
    private static function saveTributacao($tributacao_data, $produto)
    {
        $tributacao = new Tributacao();
        $tributacao_id = $produto->exists ? $produto->tributacao_id : ($tributacao_data['id'] ?? []);
        if (isset($tributacao_id)) {
            $tributacao = Tributacao::findOrFail($tributacao_id);
        }
        $tributacao->fill($tributacao_data);
        $tributacao->save();
    }

    /**
     * Salva o produto e suas informações
     *
     * @param Produto $produto
     * @param array $input
     * @return void
     */
    public static function saveProduct($produto, $input)
    {
        DB::transaction(function () use ($produto, $input) {
            $produto->fill($input);
            $tributacao_data = $input['tributacao'] ?? [];
            if (!empty($tributacao_data) && app('settings')->get('fiscal', 'mostrar_campos')) {
                $tributacao = self::saveTributacao($tributacao_data, $produto);
                $produto->tributacao_id = $tributacao->id;
            }
            $produto->save();
            $cardapios_data = $input['cardapios'] ?? [];
            self::saveCardapios($cardapios_data, $produto);
        });
    }

    public function resolve($root, $args)
    {
        $produto = Produto::findOrFail($args['id']);
        $old = $produto->replicate();
        try {
            self::saveProduct($produto, $args['input']);
            $old->clean($produto);
        } catch (\Throwable $th) {
            $produto->clean($old);
            throw $th;
        }
        return $produto;
    }
}
