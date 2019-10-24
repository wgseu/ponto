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

namespace App\Core;

use App\Util\Filter;

/**
 * Load database connection settings
 */
class Settings
{
    /**
     * Conjunto de chave e valores
     *
     * @var array
     */
    private $values = [];

    /**
     * Defaults values, if set, only allow keys
     *
     * @var array
     */
    private $defaults = [];

    /**
     * Constructor for a new instance of Settings
     * @param array $defaults Default values
     */
    public function __construct($defaults = [])
    {
        $this->defaults = $defaults;
    }

    /**
     * Get value for key
     *
     * @param string $key section value
     * @param string $default default value when key does not exists
     * @return mixed return value for key
     */
    public function getValue($key, $default = null)
    {
        if (\func_num_args() == 1 && array_key_exists($key, $this->defaults)) {
            $default = $this->defaults[$key];
        }
        if (!array_key_exists($key, $this->values)) {
            return $default;
        }
        return $this->values[$key];
    }

    /**
     * Get value or array for key and entry
     * @param string $key   section value
     * @param string $entry entry key for value
     * @param string $default default value when key or entry does not exists
     * @return mixed return value for key and entry
     */
    public function getEntry($section, $key, $default = null)
    {
        if (
            \func_num_args() == 2
            && array_key_exists($section, $this->defaults)
            && array_key_exists($key, $this->defaults[$section])
        ) {
            $default = $this->defaults[$section][$key];
        }
        if (
            !array_key_exists($section, $this->values)
            || !array_key_exists($key, $this->values[$section])
        ) {
            return $default;
        }
        return $this->values[$section][$key];
    }

    /**
     * Set new value for entry key
     * @param string $key   section value
     * @param string $entry entry key for value
     * @param mixed $value new value data
     */
    public function addValue($key, $value)
    {
        if (!empty($this->defaults) && !array_key_exists($key, $this->defaults)) {
            throw new \Exception(_t('settings.inexistent_setting', $key), 403);
        }
        if (is_array($value) && is_array($this->defaults[$key])) {
            if (!empty($this->defaults)) {
                $value = Filter::defaults($value, $this->defaults[$key]);
            }
            $value = array_replace_recursive($this->values[$key] ?? [], $value);
        } elseif (is_array($value) != is_array($this->defaults[$key])) {
            throw new \Exception(_t('settings.inexistent_setting', $key), 403);
        }
        $this->values[$key] = $value;
        return $this;
    }

    /**
     * Set new value for section key
     * @param string $section section name
     * @param string $key key name
     * @param mixed $value new value data
     */
    public function addEntry($section, $key, $value)
    {
        if (!empty($this->defaults)) {
            if (
                !array_key_exists($section, $this->defaults)
                && !array_key_exists($key, $this->defaults[$section])
            ) {
                throw new \Exception(_t('settings.inexistent_setting', $section . '.' . $key), 403);
            }
            if (is_array($value) && is_array($this->defaults[$section][$key])) {
                $value = Filter::defaults($value, $this->defaults[$section][$key]);
            } elseif (is_array($value) != is_array($this->defaults[$section][$key])) {
                throw new \Exception(_t('settings.inexistent_setting', $section . '.' . $key), 403);
            }
        }
        if (is_array($value) && is_array($this->values[$section][$key] ?? null)) {
            $value = array_replace_recursive($this->values[$section][$key], $value);
        }
        $this->values[$section][$key] = $value;
        return $this;
    }

    /**
     * Informs if exists value or entry
     *
     * @param string $section
     * @param string $key
     * @return boolean
     */
    public function has($section, $key = null)
    {
        $exists = array_key_exists($section, $this->values);
        if (\func_num_args() == 2) {
            return $exists && array_key_exists($key, $this->values[$section]);
        }
        return $exists;
    }

    /**
     * Delete value
     * @param string $key   section value
     */
    public function deleteValue($key)
    {
        unset($this->values[$key]);
        return $this;
    }

    /**
     * Delete entry
     * @param string $key   section value
     * @param string $entry entry key for value
     */
    public function deleteEntry($section, $key)
    {
        unset($this->values[$section][$key]);
        return $this;
    }

    /**
     * Add entry values to settings or replace existing
     * @param array $values new values to add or replace
     */
    public function addValues($values)
    {
        if (!is_array($values)) {
            return $this;
        }
        if (!empty($this->defaults)) {
            $values = Filter::defaults($values, $this->defaults);
        }
        $this->values = array_replace_recursive($this->values, $values);
        return $this;
    }

    /**
     * Get all dynamic values for save
     * @return array all values set
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Load configure files
     * @param string $path Path to load configure files
     */
    public function load($path)
    {
        $names = array_keys($this->defaults);
        foreach ($names as $name) {
            $this->addValue($name, self::loadFile($name, $path));
        }
        return $this;
    }

    /**
     * Load a php configuration file and return their values
     * @param  string $name  name of php file without extension
     * @param  string $path path of configuration files
     * @return array  configuration values
     */
    private static function loadFile($name, $path)
    {
        $php_file = $path . DIRECTORY_SEPARATOR . $name . '.php';
        if (!file_exists($php_file)) {
            throw new \Exception(_t('settings.file_not_found'), 404);
        }
        require($php_file);
        return $value;
    }
}
