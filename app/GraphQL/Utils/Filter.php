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

namespace App\GraphQL\Utils;

use Illuminate\Database\Eloquent\Builder;

class Filter
{
    /**
     * Add where statement to query builder from filter
     *
     * @param array $filter
     * @param Builder $query
     * @return Builder
     */
    public static function apply($filter, $query)
    {
        foreach ($filter as $key => $stmt) {
            if (!is_array($stmt)) {
                $query->where($key, '=', $stmt);
                continue;
            }
            $operator = key($stmt);
            $value = $stmt[$operator];
            switch ($operator) {
                case 'eq':
                    if (is_null($value)) {
                        $query->whereNull($key);
                        break;
                    }
                    $query->where($key, '=', $value);
                    break;
                case 'ne':
                    if (is_null($value)) {
                        $query->whereNotNull($key);
                        break;
                    }
                    $query->where($key, '<>', $value);
                    break;
                case 'after':
                case 'gt':
                    $query->where($key, '>', $value);
                    break;
                case 'from':
                case 'ge':
                    $query->where($key, '>=', $value);
                    break;
                case 'before':
                case 'lt':
                    $query->where($key, '<', $value);
                    break;
                case 'to':
                case 'le':
                    $query->where($key, '<=', $value);
                    break;
                case 'startsWith':
                    $query->where($key, 'like', $value . '%');
                    break;
                case 'contains':
                    $query->where($key, 'like', '%' . $value . '%');
                    break;
                case 'between':
                    if (!isset($value['start']) || !isset($value['end'])) {
                        break;
                    }
                    $query->whereBetween($key, [$value['start'], $value['end']]);
                    break;
                default:
                    break;
            }
        }
        return $query;
    }
}
