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

use MZ\Database\DB;

/**
 * Validate common data
 */
class Validator
{
    /**
     * Check if E-mail is valid
     * @param  string  $email E-mail to check
     * @param  boolean $empty allow empty E-mail as valid
     * @return boolean        True if E-mail is valid or false otherwise
     */
    public static function checkEmail($email, $empty = false)
    {
        if (is_null($email) && $empty) {
            return true;
        }
        $regexp = '/^[\w\-\.]+@[\w\-]+(\.[\w\-]+)*(\.[a-zA-Z]{2,})$/';
        return preg_match($regexp, $email);
    }

    /**
     * Check if username is valid
     * @param  string  $username username to check
     * @param  boolean $empty    allow empty username as valid
     * @return boolean           True if username is valid or false otherwise
     */
    public static function checkUsername($username, $empty = false)
    {
        if (is_null($username) && $empty) {
            return true;
        }
        return preg_match('/^[A-Za-z][A-Za-z0-9\._-]{2,44}$/', $username);
    }

    /**
     * Check if phone number is valid for current country
     * @param  string  $phone phone number to check
     * @param  boolean $empty    allow empty phone number as valid
     * @return boolean           True if phone number is valid or false otherwise
     */
    public static function checkPhone($phone, $empty = false)
    {
        if (is_null($phone) && $empty) {
            return true;
        }
        $mask = _p('Mascara', 'Telefone');
        $phone = Filter::unmask($phone, $mask);
        $mask_len = strlen(Filter::digits($mask));
        global $app;
        if ($app->getSystem()->getCountry()->getSigla() != 'BRA') {
            return strlen($phone) == $mask_len;
        }
        // Somente Brasil (Nono dígito)
        return strlen($phone) == 10 || strlen($phone) == 11;
    }

    /**
     * Check if CPF is valid
     * @param  string  $cpf   CPF to check
     * @param  boolean $empty    allow empty CPF as valid
     * @return boolean           True if CPF is valid or false otherwise
     */
    public static function checkCPF($cpf, $empty = false)
    {
        if (is_null($cpf) && $empty) {
            return true;
        }
        // Verifica se o número digitado contém todos os digitos
        $mask = _p('Mascara', 'CPF');
        $cpf = Filter::unmask($cpf, $mask);
        $mask_len = strlen(Filter::digits($mask));
        if (strlen($cpf) != $mask_len) {
            return false;
        }
        global $app;
        if ($app->getSystem()->getCountry()->getSigla() != 'BRA') {
            return true;
        }
        // Somente Brasil
        $pattern = '/^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/';
        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (preg_match($pattern, $cpf)) {
            return false;
        }
        // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if CNPJ is valid
     * @param  string  $cnpj   CNPJ to check
     * @param  boolean $empty    allow empty CNPJ as valid
     * @return boolean           True if CNPJ is valid or false otherwise
     */
    public static function checkCNPJ($cnpj, $empty = false)
    {
        if (is_null($cnpj) && $empty) {
            return true;
        }
        // Verifiva se o número digitado contém todos os digitos
        $mask = _p('Mascara', 'CNPJ');
        $cnpj = Filter::unmask($cnpj, $mask);
        $mask_len = strlen(Filter::digits($mask));
        if (strlen($cnpj) != $mask_len) {
            return false;
        }
        global $app;
        if ($app->getSystem()->getCountry()->getSigla() != 'BRA') {
            return true;
        }
        // Somente Brasil
        $pattern = '/^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/';
        if (preg_match($pattern, $cnpj)) {
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
     * Check for high password strength
     * @param  string  $password raw password string
     * @param  boolean $empty    allow empty password as valid
     * @return boolean           True if password strength is high or false otherwise
     */
    public static function checkPassword($password, $empty = false)
    {
        if (is_null($password) && $empty) {
            return true;
        }
        if (strlen($password) < 4) {
            return false; // Password too short
        }
        return true;
    }

    /**
     * Check if IP address is valid
     * @param  [type]  $value IP address to test
     * @param  boolean $empty    allow empty IP address as valid
     * @return boolean           True if IP address is valid or false otherwise
     */
    public static function checkIP($value, $empty = false)
    {
        if (is_null($value) && $empty) {
            return true;
        }
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Check if $value only contains digits
     * @param  [type]  $value text to check
     * @param  boolean $empty    allow empty digits as valid
     * @return boolean           True if IP address is valid or false otherwise
     */
    public static function checkDigits($value, $empty = false)
    {
        if ($value == '' && $empty) {
            return true;
        }
        return preg_match('/^[0-9]+$/', $value);
    }

    /**
     * Check if CEP is valid
     * @param  string  $cep   CEP to check
     * @param  boolean $empty    allow empty CEP as valid
     * @return boolean           True if CEP is valid or false otherwise
     */
    public static function checkCEP($cep, $empty = false)
    {
        if ($cep == '' && $empty) {
            return true;
        }
        $mask = _p('Mascara', 'CEP');
        $cep = Filter::unmask($cep, $mask);
        $mask_len = strlen(Filter::digits($mask));
        return strlen($cep) == $mask_len;
    }

    /**
     * Check if entry is in array set as key
     * @param  string  $entry key name
     * @param  array   $set array as key set
     * @param  boolean $empty allow null entry as valid
     * @return boolean True if entry is in set or false otherwise
     */
    public static function checkInSet($entry, $set, $empty = false)
    {
        if (is_null($entry) && $empty) {
            return true;
        }
        return array_key_exists($entry, $set);
    }

    /**
     * Check if value is a valid boolean database
     * @param  string  $value boolean value
     * @param  boolean $empty allow null as valid boolean
     * @return boolean True if value is a valid boolean value or false otherwise
     */
    public static function checkBoolean($value, $empty = false)
    {
        if (is_null($value) && $empty) {
            return true;
        }
        return self::checkInSet($value, DB::getBooleanOptions(), $empty);
    }
}
