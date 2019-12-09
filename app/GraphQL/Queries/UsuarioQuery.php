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

use App\Models\Cliente;
use App\Models\Funcao;
use App\Models\Prestador;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UsuarioQuery extends Query
{
    protected $attributes = [
        'name' => 'usuario',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check();
    }

    public function type(): Type
    {
        return GraphQL::type('Usuario');
    }

    /**
     * Cria a query com base no usuário
     *
     * @param Cliente $user
     * @return array
     */
    public static function process($user)
    {
        /** @var Prestador $prestador */
        $prestador = $user->prestador;
        $cliente_data = $user->toArray();
        if (!is_null($prestador)) {
            /** @var Funcao $funcao */
            $funcao = $prestador->funcao;
            $permissoes = $funcao->permissoes()->pluck('nome');
            $cliente_data['prestador'] = $prestador->toArray();
            $cliente_data['prestador']['funcao'] = $funcao->toArray();
            $cliente_data['prestador']['funcao']['permissoes'] = $permissoes;
        }
        $cliente_data['proprietario'] = $user->isOwner();
        return $cliente_data;
    }

    public function resolve()
    {
        /** @var Cliente $user */
        $user = Auth::user();
        return self::process($user);
    }
}
