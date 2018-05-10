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
namespace MZ\Response;

use MZ\Core\Response;

/**
 * Output JSON response
 */
class HtmlResponse extends Response
{
    private $engine;
    private $title;

    /**
     * Constructor for a new instance of Response
     * @param Response $processor Response processor
     * @param Settings $settings Application settings for template engine
     */
    public function __construct($processor, $settings)
    {
        parent::__construct($processor);
        $this->engine = new \MZ\Template\Custom($settings);
    }

    /**
     * Title of the HTML page
     * @param string $title new title for the page
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set engine to process templates
     * @param \MZ\Template\Engine current template engine
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
        return $this;
    }

    /**
     * Get the template engine used for processing templates
     * @return \MZ\Template\Engine current template engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Output a processed template into response
     * @param  string $template template name
     */
    public function output($template)
    {
        $ext = '';
        $pattern = '/\.(\w+)$/';
        if (preg_match($pattern, $template, $matches)) {
            $ext = $matches[1];
        }
        switch ($ext) {
            case 'xml':
                $this->getProcessor()->header('Content-Type', 'application/xml; charset=UTF-8');
                break;
            case 'txt':
                $this->getProcessor()->header('Content-Type', 'text/plain; charset=UTF-8');
                break;
            default:
                $this->engine->retrocompatibility();
                $this->getProcessor()->header('Content-Type', 'text/html; charset=UTF-8');
                break;
        }
        $this->engine->pagetitle = $this->title;
        parent::output($this->engine->render($template . '.twig'));
    }
}
