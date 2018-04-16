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
namespace MZ\Core;

/**
 * Load database connection settings
 */
class Settings
{
    private $values;

    /**
     * Constructor for a new instance of Settings
     * @param array $values Default values
     */
    public function __construct($values = [])
    {
        $this->values = $values;
        $this->values = $this->getValues();
    }

    /**
     * Get value or array for key and entry
     * @param string $key   section value
     * @param string $entry entry key for value
     * @param string $default default value when key or entry does not exists
     * @return mixed return value for key and entry
     */
    public function getValue($key, $entry = null, $default = null)
    {
        if (!array_key_exists($key, $this->values)) {
            return $default;
        }
        $value = $this->values[$key];
        if (!is_null($entry) && array_key_exists($entry, $value)) {
            $value = $value[$entry];
        } elseif (!is_null($entry)) {
            return $default;
        }
        return $value;
    }

    /**
     * Set new value for entry key
     * @param string $key   section value
     * @param string $entry entry key for value
     * @param mixed $value new value data
     */
    public function setValue($key, $entry, $value)
    {
        if (in_array($key, $this->getConfigKeys())) {
            throw new \Exception('Static configuration cannot be changed', 403);
        }
        $this->values[$key][$entry] = $value;
        return $this;
    }

    /**
     * Delete entry
     * @param string $key   section value
     * @param string $entry entry key for value
     */
    public function deleteEntry($key, $entry)
    {
        if (in_array($key, $this->getConfigKeys())) {
            throw new \Exception('Static configuration cannot be changed', 403);
        }
        unset($this->values[$key][$entry]);
        return $this;
    }

    /**
     * Add entry values to settings or replace existing
     * @param array $values new values to add or replace
     */
    public function addValues($values)
    {
        $values = array_intersect_key($values, array_flip($this->getKeys()));
        $this->values = array_merge($this->values, $values);
        return $this;
    }

    /**
     * Get all dynamic values for save
     * @return [type] [description]
     */
    public function getValues()
    {
        return array_intersect_key($this->values, array_flip($this->getKeys()));
    }

    /**
     * Load configure files
     * @param string $path Path to load configure files
     */
    public function load($path = null)
    {
        if (is_null($path)) {
            $path = $this->path;
        }
        $keys = $this->getConfigKeys();
        foreach ($keys as $key) {
            $this->values[$key] = $this->loadKey($key, $path);
        }
        return $this;
    }

    /**
     * Return static configuration file names
     * @return array Set of filenames to load
     */
    private function getConfigKeys()
    {
        return [
            'db',
            'path',
        ];
    }

    /**
     * Return dynamic configuration entries
     * @return array Set of keys available to save
     */
    private function getKeys()
    {
        return array_keys(array_diff_key($this->values, array_flip($this->getConfigKeys())));
    }

    /**
     * Load a php configuration file and return their values
     * @param  string $key  name of php file without extension
     * @param  string $path path of configuration files
     * @return array  configuration values
     */
    private function loadKey($key, $path)
    {
        $php_file = $path . DIRECTORY_SEPARATOR . $key . '.php';
        if (!file_exists($php_file)) {
            throw new \Exception('Configuration file "'.$php_file.'" not found', 404);
        }
        require_once($php_file);
        return $value;
    }
}
