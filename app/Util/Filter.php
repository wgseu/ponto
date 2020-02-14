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

use App\Models\Pais;

/**
 * Filter values to secure save on database
 */
class Filter
{
    /**
     * Transform empty array into stdClass object
     *
     * @param array $array
     * @return mixed
     */
    public static function emptyObject($array)
    {
        if (empty($array)) {
            return new \stdClass();
        }
        return $array;
    }

    /**
     * Remove a máscara do telefone retornando só os dígitos
     *
     * @param string $input
     * @param Pais $country pais do telefone
     *
     * @return string
     */
    public static function phone($input, $country)
    {
        $masks = explode('|', $country->entries->get('phone', 'mask'));
        foreach ($masks as $mask) {
            $unmasked = self::unmask($input, $mask);
            $digits = self::digits($input);
            if ($unmasked == $digits) {
                return $unmasked;
            }
        }
        return null;
    }

    /**
     * Remove a máscara do CEP retornando só os dígitos
     *
     * @param string $input
     *
     * @return string
     */
    public static function zipcode($input)
    {
        $mask = app('country')->entries->get('zipcode', 'mask');
        $unmasked = self::unmask($input, $mask);
        $digits = self::digits($input);
        if ($unmasked != $digits) {
            return null;
        }
        return $unmasked;
    }

    /**
     * Remove a máscara do CPF retornando só os dígitos
     *
     * @param string $input
     *
     * @return string
     */
    public static function cpf($input)
    {
        $mask = app('country')->entries->get('cpf', 'mask');
        $unmasked = self::unmask($input, $mask);
        $digits = self::digits($input);
        if ($unmasked != $digits) {
            return null;
        }
        return $unmasked;
    }

    /**
     * Remove a máscara do CNPJ retornando só os dígitos
     *
     * @param string $input
     *
     * @return string
     */
    public static function cnpj($input)
    {
        $mask = app('country')->entries->get('cnpj', 'mask');
        $unmasked = self::unmask($input, $mask);
        $digits = self::digits($input);
        if ($unmasked != $digits) {
            return null;
        }
        return $unmasked;
    }

    /**
     * Remove a máscara do NCM retornando só os dígitos
     *
     * @param string $input
     *
     * @return string
     */
    public static function ncm($input)
    {
        $mask = app('country')->entries->get('ncm', 'mask');
        $unmasked = self::unmask($input, $mask);
        $digits = self::digits($input);
        if ($unmasked != $digits) {
            return null;
        }
        return $unmasked;
    }

    /**
     * Retorna os possíveis números de telefones válidos
     *
     * @param string $number
     * @param Pais $country pais do telefone
     *
     * @return string[]
     */
    public static function makePhoneNumbers($number, $country)
    {
        $result = [];
        $masks = explode('|', $country->entries->get('phone', 'mask'));
        foreach ($masks as $mask) {
            $phone_len = strlen(self::digits($number));
            $slots_len = strlen(preg_replace('/[^#]/', '', $mask));
            if ($phone_len == $slots_len) {
                $result[] = $number;
            } elseif ($country->codigo != Pais::CODE_BRAZIL) {
                continue;
            }
            if ($phone_len < $slots_len) {
                $result[] = self::include9thDigit($number);
            } elseif ($phone_len > $slots_len) {
                $result[] = self::remove9thDigit($number);
            }
        }
        return $result;
    }

    /**
     * Inclui o nono dígito no telefone
     *
     * @param string $number
     * @return string
     */
    public static function include9thDigit($number)
    {
        if (strlen($number) == 10) {
            return substr($number, 0, 2) . '9' . substr($number, 2);
        }
        if (strlen($number) == 8) {
            return '9' . $number;
        }
        return $number;
    }

    /**
     * Remove o nono dígito do telefone
     *
     * @param string $number
     * @return string
     */
    public static function remove9thDigit($number)
    {
        if (strlen($number) == 11) {
            return substr($number, 0, 2) . substr($number, 3);
        }
        if (strlen($number) == 9) {
            return substr($number, 1);
        }
        return $number;
    }

    /**
     * Filter array using defaults values
     * Remove same values as default
     *
     * @param array $values
     * @param array $defaults
     * @return array filtered values
     */
    public static function defaults($values, $defaults)
    {
        $result = [];
        $values = array_intersect_key($values, $defaults);
        foreach ($values as $key => $value) {
            if (is_array($value) && is_array($defaults[$key])) {
                $array = self::defaults($value, $defaults[$key]);
                if (!empty($array)) {
                    $result[$key] = $array;
                }
            } elseif (!is_array($value) && !is_array($defaults[$key])) {
                if ($defaults[$key] !== $value) {
                    $result[$key] = $value;
                }
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
    public static function unmask($input, $mask)
    {
        $result = '';
        $input_index = 0;
        for ($mask_index = 0; $mask_index < strlen($mask); $mask_index++) {
            $mask_char = $mask[$mask_index];
            if ($input_index >= strlen($input)) {
                if ($mask_char == '#') {
                    return null;
                }
                continue;
            }
            $input_char = $input[$input_index];
            if ($mask_char == '#' && preg_match('/\d/', $input_char)) {
                $result .= $input_char;
                $input_index++;
            } elseif ($mask_char == $input_char) {
                $input_index++;
            }
        }
        if (trim($result) == '') {
            return null;
        }
        return $result;
    }
}
