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

namespace MZ\Template;

/**
 * Proccess template files
 */
abstract class Engine
{
    private $settings;
    private $properties;

    /**
     * Constructor for a new instance of Engine
     * @param \MZ\Core\Settings app settings
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->properties = [];
    }

    /**
     * Allow to set properties easily for use on template file
     * @param string $k variable name
     * @param mixed $v content of variable
     */
    public function __set($k, $v)
    {
        $this->properties[$k] = $v;
    }

    /**
     * Allow to get properties from $this from templates files
     * @param string $k variable name
     * @return mixed content of property
     */
    public function __get($k)
    {
        return $this->properties[$k];
    }

    /**
     * App settings
     * @return \MZ\Core\Settings app settings
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Property tags array
     * @return array associative tag name => value
     */
    public function getProperties()
    {
        return $this->properties;
    }

    // TODO: remove retrocompatibility
    public function retrocompatibility()
    {
        foreach ($GLOBALS as $k => $v) {
            $this->properties[$k] = $v;
        }
        if (!isset($this->properties['currency'])) {
            $this->currency = $this->app->getSystem()->getCurrency();
        }
        if (!isset($this->properties['auth'])) {
            $this->auth = $this->app->getAuthentication();
        }
        if (!isset($this->properties['user'])) {
            $this->user = $this->app->getAuthentication()->getUser();
        }
        if (!isset($this->properties['provider'])) {
            $this->provider = $this->app->getAuthentication()->getEmployee();
        }
    }

    /**
     * Render a template file and returns it's content
     * @param  string $tFile template name
     * @return string template content processed
     */
    abstract public function render($tFile);
}
