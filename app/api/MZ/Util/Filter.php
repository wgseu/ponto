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
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
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
     * Filter string number into int type or null
     * @param  string $value representative number
     * @return int int value filtered or null
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
     * Filter string float repesentation to float or null
     * @param  string $value Country specific formated value
     * @param boolean $localized Informs if value is localized
     * @return float raw value
     */
    public static function float($value, $localized = true)
    {
        if (!$localized) {
            return floatval($value);
        }
        $sep = '.';
        $dec = ',';
        $value = preg_replace('/[^0-9'.$dec.'\-]/', '', $value);
        if ($value == '') {
            return null;
        }
        return floatval(str_replace($dec, '.', $value));
    }

    /**
     * Filter currency values to float or null
     * @param  string $value Country specific formated value
     * @param boolean $localized Informs if value is localized
     * @return float raw value
     */
    public static function money($value, $localized = true)
    {
        $value = self::float($value, $localized);
        return is_null($value) ? null : \round($value, \log10(app()->system->currency->getDivisao()));
    }

    /**
     * Filter string striping tags and removing spaces
     * @param  string $value unsafe string
     * @return string trimmed and stripped string
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
     * Filter raw or special text
     * @param  string $value unknow text
     * @return string processed text
     */
    public static function text($value)
    {
        if (strval($value) == '') {
            return null;
        }
        return $value;
    }

    /**
     * Filter name and beautiful camel case letters
     * @param  string $nome name with tags and wrong case letters
     * @return string beautiful name
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

    /**
     * Parse order string to array
     * @param  mixed $order order array or string to sort rows
     * @return array order list
     */
    public static function order($order)
    {
        if (is_array($order)) {
            return $order;
        }
        if (trim($order) != '') {
            $stmt = explode(',', $order);
        } else {
            $stmt = [];
        }
        $order = [];
        foreach ($stmt as $key => $value) {
            $entry = explode(':', $value);
            if (count($entry) == 2 && $entry[1] == 'desc') {
                $order[$entry[0]] = -1;
            } elseif (count($entry) == 2 && $entry[1] == 'asc') {
                $order[$entry[0]] = 1;
            } else {
                $order[$entry[0]] =  0;
            }
        }
        return $order;
    }

    /**
     * Filter array with allowed keys
     * @param  mixed $array array to filter
     * @param  mixed $allowed allowed array keys
     * @return array allowed array
     */
    public static function keys($array, $allowed, $prefix = '')
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (array_key_exists($key, $allowed)) {
                $result[$key] = $value;
            } elseif (is_array($prefix)) {
                foreach ($prefix as $kprefix) {
                    if (array_key_exists($kprefix.$key, $allowed)) {
                        $result[$kprefix.$key] = $value;
                        break;
                    }
                }
            } elseif (array_key_exists($prefix.$key, $allowed)) {
                $result[$prefix.$key] = $value;
            }
        }
        return $result;
    }

    /**
     * Concat array keys
     * @param  string $left concat left to key
     * @param  array $array array to concat keys
     * @param  string $right concat right to key
     * @return array concatenated array keys
     */
    public static function concatKeys($left, $array, $right = '')
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$left . $key . $right] = $value;
        }
        return $result;
    }

    /**
     * Parse and filter order by string to array
     * @param  mixed $order order array or string to sort rows
     * @param  mixed $allowed allowed array keys
     * @return array allowed order array
     */
    public static function orderBy($order, $allowed, $prefix = '')
    {
        $order = self::order($order);
        return self::keys($order, $allowed, $prefix);
    }

    /**
     * Parse a date or datetime string for current country
     * @param  string $value humman datetime value
     * @param  string $time default time value
     * @return string database datetime format
     */
    public static function parseTime($value, $time)
    {
        if (trim($value) == '') {
            return false;
        }
        $d = \DateTime::createFromFormat('d/m/Y H:i', $value);
        if ($d !== false) {
            return $d;
        }
        $d = \DateTime::createFromFormat('d/m/Y H:i:s', $value);
        if ($d !== false) {
            return $d;
        }
        $d = \DateTime::createFromFormat('d/m/Y', $value);
        if ($d === false) {
            $d = \DateTime::createFromFormat('Y-m-d', $value);
        }
        if ($d !== false) {
            $t = strtotime($time);
            $d->setTime(intval(date('G', $t)), intval(date('i', $t)), intval(date('s', $t)));
            return $d;
        }
        return new \DateTime($value);
    }

    /**
     * Parse a datetime string for current country
     * @param  string $value humman datetime value
     * @param  string $time default time value
     * @return string database datetime format
     */
    public static function datetime($value, $time = null)
    {
        $d = self::parseTime($value, $time);
        if ($d === false) {
            return null;
        }
        return $d->format('Y-m-d H:i:s');
    }

    /**
     * Parse a date string for current country
     * @param  string $value humman date value
     * @return string database date format
     */
    public static function date($value)
    {
        $d = self::parseTime($value, null);
        if ($d === false) {
            return null;
        }
        return $d->format('Y-m-d');
    }

    /**
     * Parse a date or datetime string for current country
     * @param  string $value humman date value
     * @return string database date format
     */
    public static function time($value)
    {
        $result = self::date($value);
        if (is_null($result)) {
            $result = self::datetime($value);
        }
        if (is_null($result)) {
            $result = $value;
        }
        return $result;
    }

    /**
     * Escape single and double quotes
     * @param  mixed $value Text to be escaped or array
     * @param  string $key Key to access array
     * @return string escaped text
     */
    public static function input($value, $key = null)
    {
        if (is_array($value)) {
            $value = isset($value[$key])?$value[$key]:null;
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Filter array values and removes blank values entry
     * @param  array $array array to be filtered
     * @return array array filtered
     */
    public static function query($array)
    {
        return array_filter(
            $array,
            function ($value) {
                return is_array($value) || trim($value) !== '';
            }
        );
    }

    /**
     * Filter array values and change to null blank values entry
     * @param  array $array array to be filtered
     * @return array array filtered
     */
    public static function values($array)
    {
        return array_map(
            function ($value) {
                if (is_array($value) || trim($value) !== '') {
                    return $value;
                }
                return null;
            },
            $array
        );
    }
}
