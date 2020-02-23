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

namespace App\Util;

/**
 * Currency common operations
 */
class Currency
{
    /**
     * Number of decimal places
     *
     * @return int
     */
    public static function places()
    {
        return log10(app('currency')->divisao ?: 100);
    }

    /**
     * Delta value for comparison
     *
     * @return float
     */
    public static function delta()
    {
        return 0.5 / (app('currency')->divisao ?: 100);
    }

    /**
     * Round currency using configured decimal places
     *
     * @return float
     */
    public static function round($value)
    {
        return \round($value, self::places());
    }

    /**
     * Compare two values are equals using delta based on currency decimal places
     *
     * @return bool
     */
    public static function isEqual($value, $compare)
    {
        return Number::isEqual($value, $compare, self::delta());
    }

    /**
     * Verify if value is bigger then given
     *
     * @return bool
     */
    public static function isGreater($value, $compare)
    {
        return Number::isGreater($value, $compare, self::delta());
    }

    /**
     * Verify if value is less then given
     *
     * @return bool
     */
    public static function isLess($value, $compare)
    {
        return Number::isLess($value, $compare, self::delta());
    }
}
