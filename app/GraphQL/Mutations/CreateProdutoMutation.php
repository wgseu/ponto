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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateProdutoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateProduto',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && Auth::user()->can('produto:create');
    }

    public function type(): Type
    {
        return GraphQL::type('Produto');
    }

    public function args(): array
    {
        return [
            'input' => ['type' => Type::nonNull(GraphQL::type('ProdutoInput'))],
        ];
    }

    /**
     * Salva os cardápios enviados junto com os produtos
     *
     * @param array $cardapios_data
     * @param Produto $produto
     * @return void
     */
    protected static function saveCardapios($cardapios_data, $produto)
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
    protected static function saveTributacao($tributacao_data, $produto)
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
    protected static function saveProduct($produto, $input)
    {
        DB::transaction(function () use ($produto, $input) {
            $produto->fill($input);
            $tributacao_data = $input['tributacao'] ?? [];
            if (!empty($tributacao_data) && app('settings')->get('fiscal', 'mostrar_campos')) {
                $tributacao = self::saveTributacao($tributacao_data, $produto);
                $produto->tributacao_id = $tributacao->id;
            }
            if ($produto->exists) {
                $produto->restore();
            } else {
                $produto->save();
            }
            $cardapios_data = $input['cardapios'] ?? [];
            self::saveCardapios($cardapios_data, $produto);
        });
    }

    public function resolve($root, $args)
    {
        $produto = new Produto();
        $produto->fillNextCode();
        try {
            self::saveProduct($produto, $args['input']);
        } catch (\Throwable $th) {
            $produto->clean(new Produto());
            throw $th;
        }
        return $produto;
    }
}
