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
 * Filter values to secure save on database
 */
class Filter
{
    public static function defaults($values, $defaults)
    {
        $result = [];
        $values = array_intersect_key($values, $defaults);
        foreach ($values as $key => $value) {
            if (is_array($value) && is_array($defaults[$key])) {
                $result[$key] = self::defaults($value, $defaults[$key]);
            } elseif (!is_array($value) && !is_array($defaults[$key])) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * Filter string and remove all non digits
     * @param  string $value text containing digits
     * @return string only digits number from string
     */
    public static function digits($value)
    {
        $value = preg_replace('/[^0-9]/', '', strval($value));
        if ($value == '') {
            return null;
        }
        return $value;
    }

    /**
     * Unmask string from mask format
     * @param  string $str  text to unmask
     * @param  string $mask mask to apply
     * @return string new text unmasked or null if empty
     */
    public static function unmask($str, $mask)
    {
        $res = '';
        $j = 0;
        $opt = false;
        for ($i = 0; $i < strlen($mask); $i++) {
            if ($j >= strlen($str)) {
                break;
            }
            if (($mask[$i] == '9' || $mask[$i] == '0') && preg_match('/[0-9]/', $str[$j])) {
                $res .= $str[$j++];
            } elseif ($mask[$i] == '?') {
                $opt = true;
            } elseif ($mask[$i] == $str[$j]) {
                $j++;
            }
        }
        if (trim($res) == '') {
            return null;
        }
        return $res;
    }
}
