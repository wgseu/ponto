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

use App\Models\Empresa;
use App\Models\Moeda;
use App\Models\Pais;

/**
 * Filter values to secure save on database
 */
class Mask
{
    /**
     * Convert float value into money format from user country
     * @param float  $value  money value
     * @param boolean $format format money adding symbol to number
     * @param Moeda $currency curency of value
     * @return string          money into readable format
     */
    public static function money($value, $format = false, $currency = null)
    {
        $value = round($value, 2);
        $empresa = Empresa::find(1);
        /** @var Pais $pais */
        $pais = $empresa->pais;
        $sep = $pais->entries->getEntry('currency', 'separator', '.');
        $dec = $pais->entries->getEntry('currency', 'decimal', ',');
        $number =  number_format($value, 2, $dec, $sep);
        if ($format) {
            if (is_null($currency)) {
                $currency = $pais->moeda;
            }
            return __($currency->formato, ['value' => $number]);
        }
        return $number;
    }
}
