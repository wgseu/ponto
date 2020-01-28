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

use App\Exceptions\Exception;
use App\Models\Pedido;
use App\Models\Avaliacao;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateAvaliacaoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateAvaliacao',
    ];

    public function authorize(array $args): bool
    {
        $pedido = Pedido::findOrFail($args['input']['pedido_id']);
        return Auth::check() && $pedido->cliente_id == auth()->user()->id;
    }

    public function type(): Type
    {
        return GraphQL::type('Avaliacao');
    }

    public function args(): array
    {
        return [
            'input' => ['type' => Type::nonNull(GraphQL::type('AvaliacaoInput'))],
        ];
    }

    /**
     * Salva a lista de avaliação
     *
     * @param array $subavaliacoes
     * @param Avaliacao $resumo
     * @return void
     */
    public static function saveAll($subavaliacoes, $resumo)
    {
        $canPublish = auth()->user()->can('avaliacao:update');
        foreach ($subavaliacoes as $data) {
            $avaliacao = new Avaliacao();
            if (!$canPublish) {
                unset($data['publico']);
            }
            if (isset($data['id'])) {
                $avaliacao = Avaliacao::where('pedido_id', $resumo->pedido_id)
                    ->findOrFail($data['id']);
            }
            $avaliacao->pedido_id = $resumo->pedido_id;
            $avaliacao->cliente_id = $resumo->cliente_id;
            $avaliacao->fill($data);
            $avaliacao->save();
        }
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $resumo = new Avaliacao();
        if (!auth()->user()->can('avaliacao:update')) {
            unset($input['publico']);
        }
        $resumo->fill($input);
        $resumo->cliente_id = auth()->user()->id;
        $subavaliacoes = $input['subavaliacoes'] ?? [];
        if (count($subavaliacoes) == 0) {
            throw new Exception(__('messages.no_evaluation_given'));
        }
        DB::transaction(function () use ($resumo, $subavaliacoes) {
            self::saveAll($subavaliacoes, $resumo);
            $resumo->save();
        });
        return $resumo;
    }
}
