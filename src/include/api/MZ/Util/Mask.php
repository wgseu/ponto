<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Util;

/**
 * Mask values using a mask provided from current country
 */
class Mask
{
    /**
     * Convert database datetime format into user country date and time format
     * @param  string $value datetime into database format
     * @return string        date and time into readable user
     */
    public static function datetime($value)
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
        if ($date === false) {
            return null;
        }
        return $date->format('d/m/Y H:i');
    }

    /**
     * Convert database date format into user country date format
     * @param  string $value date into database format
     * @return string        date into readable user
     */
    public static function date($value)
    {
        $date = \DateTime::createFromFormat('!d-m-Y', $value);
        if ($date === false) {
            return null;
        }
        return $date->format('d/m/Y');
    }

    /**
     * Convert database time format into user country time format
     * @param  string $value time into database format
     * @return string        time into readable user
     */
    public static function time($value)
    {
        $date = \DateTime::createFromFormat('!H:i:s', $value);
        if ($date === false) {
            return null;
        }
        return $date->format('H:i');
    }

    /**
     * Convert float value into money format from user country
     * @param  float  $value  money value
     * @param  boolean $format format money adding symbol to number
     * @return string          money into readable format
     */
    public static function money($value, $format = false)
    {
        $value = round($value, 2);
        $sep = '.';
        $dec = ',';
        $number =  number_format($value, 2, $dec, $sep);
        if ($format) {
            return vsprintf('R$ %s', array($number));
        }
        return $number;
    }

    /**
     * Mask phone number
     * @param  string $fone phone number formatted or not
     * @return string       Well formatted phone number
     */
    public static function phone($fone)
    {
        return self::mask(Filter::digits($fone), '(99) 9999-9999?9');
    }

    /**
     * Mask Postal code number
     * @param  string $cep  Postal code number
     * @return string       Well formatted Postal code number
     */
    public static function cep($cep)
    {
        return self::mask(Filter::digits($cep), '99999-999');
    }

    /**
     * Mask CPF personal number
     * @param  string $cpf  CPF number
     * @return string       Well formatted CPF number
     */
    public static function cpf($cpf)
    {
        return self::mask(Filter::digits($cpf), '999.999.999-99');
    }

    /**
     * Mask CNPJ business number
     * @param  string $cnpj  CNPJ number
     * @return string       Well formatted CNPJ number
     */
    public static function cnpj($cnpj)
    {
        return self::mask(Filter::digits($cnpj), '99.999.999/9999-99');
    }

    /**
     * Mask any text using a mask format
     * @param  string $str  texto to be masked
     * @param  string $mask mask to apply
     * @return string       Text with mask applied
     */
    public static function mask($str, $mask)
    {
        if (empty($str)) {
            return null;
        }
        $len = strlen($mask);
        $res = '';
        $j = 0;
        $opt = false;
        for ($i = 0; $i < $len; $i++) {
            if ($mask[$i] == '9' || $mask[$i] == '0') {
                if ($j < strlen($str)) {
                    $res .= $str[$j++];
                }
            } elseif ($mask[$i] == '?') {
                $opt = true;
            } else {
                $res .= $mask[$i];
                $opt = false;
            }
        }
        return $res;
    }
}
