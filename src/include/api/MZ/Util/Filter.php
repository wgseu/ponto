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
 * Filter values to secure save on database
 */
class Filter
{
    /**
     * Filter string and remove all non digits
     * @param  string $value text containing digits
     * @return string        only digits number from string
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
     * Filter string number into int type or null
     * @param  string $value representative number
     * @return int       int value filtered or null
     */
    public static function number($value)
    {
        $value = self::digits($value);
        if ($value == '') {
            return null;
        }
        return intval($value);
    }

    /**
     * Filter currency values to float or null
     * @param  string $value Country specific formated value
     * @return float         raw value
     */
    public static function money($value)
    {
        $sep = '.';
        $dec = ',';
        $value = preg_replace('/[^0-9'.$dec.'\-]/', '', $value);
        if ($value == '') {
            return null;
        }
        return floatval(str_replace($dec, '.', $value));
    }

    /**
     * Filter string striping tags and removing spaces
     * @param  string $value unsafe string
     * @return string        trimmed and stripped string
     */
    public static function string($value)
    {
        $value = strip_tags(trim($value));
        if ($value == '') {
            return null;
        }
        return $value;
    }

    /**
     * Filter name and beautiful camel case letters
     * @param  string $nome name with tags and wrong case letters
     * @return string       beautiful name
     */
    public static function name($nome)
    {
        $name = self::string($nome);
        if (is_null($name)) {
            return null;
        }
        $nome = mb_strtolower($name, mb_detect_encoding($nome));
        $nome = preg_split('//u', $nome, -1, PREG_SPLIT_NO_EMPTY);
        $nlen = count($nome);
        $p = 0;
        $pal = '';
        for ($i = 0; $i < $nlen; $i++) {
            if (($nome[$i] == ' ' || $i == ($nlen - 1))) {
                if ($p >= 0 && $pal != 'de' && $pal != 'da' && $pal != 'das' &&
                    $pal != 'do' && $pal != 'dos' && strlen($pal) > 1
                ) {
                    $nome[$p] =  mb_strtoupper($nome[$p], mb_detect_encoding($nome[$p]));
                }
                if ($i == ($nlen - 1)) {
                    $pal = $pal.$nome[$i];
                }
                $pal = '';
                $p = -1;
            } else {
                if ($p == -1) {
                    $p = $i;
                }
                $pal = $pal.$nome[$i];
            }
        }
        return implode('', $nome);
    }

    /**
     * Unmask string from mask format
     * @param  string $str  text to unmask
     * @param  string $mask mask to apply
     * @return string       new text unmasked or null if empty
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
        if ($res == '') {
            return null;
        }
        return $res;
    }

    /**
     * Parse a datetime string for current country
     * @param  string $value humman datetime value
     * @return string        database datetime format
     */
    public static function datetime($value)
    {
        $d = \DateTime::createFromFormat('d/m/Y H:i', $value);
        if ($d === false) {
            return null;
        }
        return $d->format('Y-m-d H:i:s');
    }

    /**
     * Escape single and double quotes
     * @param  string $value Text to be escaped
     * @return string        escaped text
     */
    public static function input($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Filter array values and removes blank values entry
     * @param  array $array array to be filtered
     * @return array        array filtered
     */
    public static function query($array)
    {
        return array_filter(
            $array,
            function ($value) {
                return trim($value) !== '';
            }
        );
    }
}
