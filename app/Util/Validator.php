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
 * Validate common data
 */
class Validator
{
    public static function cpf($cpf, $empty = false)
    {
        if (is_null($cpf) && $empty) {
            return true;
        }
        $cpf = Filter::cpf($cpf);
        if (app('country')->codigo != Pais::CODE_BRAZIL) {
            return !is_null($cpf);
        }
        // Somente Brasil
        $pattern = '/^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/';
        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (is_null($cpf) || preg_match($pattern, $cpf)) {
            return false;
        }
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * ($t + 1 - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    public static function cnpj($cnpj, $empty = false)
    {
        if (is_null($cnpj) && $empty) {
            return true;
        }
        $cnpj = Filter::cnpj($cnpj);
        if (app('country')->codigo != Pais::CODE_BRAZIL) {
            return !is_null($cnpj);
        }
        // Somente Brasil
        $pattern = '/^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/';
        if (is_null($cnpj) || preg_match($pattern, $cnpj)) {
            return false;
        }
        $calcular = 0;
        $calcularDois = 0;
        for ($i = 0, $x = 5; $i <= 11; $i++, $x--) {
            $x = ($x < 2) ? 9 : $x;
            $number = substr($cnpj, $i, 1);
            $calcular += $number * $x;
        }
        for ($i = 0, $x = 6; $i <= 12; $i++, $x--) {
            $x = ($x < 2) ? 9 : $x;
            $numberDois = substr($cnpj, $i, 1);
            $calcularDois += $numberDois * $x;
        }
        $digitoUm = (($calcular % 11) < 2) ? 0 : 11 - ($calcular % 11);
        $digitoDois = (($calcularDois % 11) < 2) ? 0 : 11 - ($calcularDois % 11);
        if ($digitoUm != substr($cnpj, 12, 1) || $digitoDois != substr($cnpj, 13, 1)) {
            return false;
        }
        return true;
    }

    /**
     * Check if zipcode is valid
     * @param  string  $cep   CEP to check
     * @param  boolean $empty    allow empty CEP as valid
     * @return boolean           True if CEP is valid or false otherwise
     */
    public static function zipcode($cep, $empty = false)
    {
        if (is_null($cep) && $empty) {
            return true;
        }
        return !is_null(Filter::zipcode($cep));
    }

    /**
     * Verifica se o NCM é válido
     *
     * @param string $ncm
     * @param bool $empty aceita ncm nulo
     * @return bool
     */
    public static function ncm($ncm, $empty = false)
    {
        if ($ncm == '00' || (is_null($ncm) && $empty)) {
            return true;
        }
        return !is_null(Filter::ncm($ncm));
    }

    /**
     * Check if phone number is valid for country
     *
     * @param  string  $phone phone number to check
     * @param Pais $country pais do telefone
     * @param  boolean $empty allow empty phone number as valid
     *
     * @return boolean true if phone number is valid or false otherwise
     */
    public static function phone($phone, $country, $empty = false)
    {
        if (is_null($phone) && $empty) {
            return true;
        }
        return !is_null(Filter::phone($phone, $country));
    }

    /**
     * Check if IP address is valid
     * @param  [type]  $value IP address to test
     * @param  boolean $empty    allow empty IP address as valid
     * @return boolean           True if IP address is valid or false otherwise
     */
    public static function ip($value, $empty = false)
    {
        if (is_null($value) && $empty) {
            return true;
        }
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }
    /**
     * Check if E-mail is valid
     * @param  string  $value E-mail to check
     * @param  boolean $empty allow empty E-mail as valid
     * @return boolean        True if E-mail is valid or false otherwise
     */
    public static function email($value, $empty = false)
    {
        if (is_null($value) && $empty) {
            return true;
        }
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
