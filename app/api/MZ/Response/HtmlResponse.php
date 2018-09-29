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

use Symfony\Component\HttpFoundation\Response;

/**
 * Output JSON response
 */
class HtmlResponse extends Response
{
    private $engine;

    /**
     * Constructor for a new instance of Response
     * @param Settings $settings Application settings for template engine
     */
    public function __construct($settings)
    {
        parent::__construct();
        $this->engine = new \MZ\Template\Custom($settings);
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
     * @param string $template template name
     * @return self
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
                $this->headers->set('Content-Type', 'application/xml; charset=UTF-8');
                break;
            case 'txt':
                $this->headers->set('Content-Type', 'text/plain; charset=UTF-8');
                break;
            default:
                $this->engine->retrocompatibility();
                $this->headers->set('Content-Type', 'text/html; charset=UTF-8');
                break;
        }
        $this->engine->pagetitle = isset($this->engine->getProperties()['pagetitle']) ? $this->engine->pagetitle : null;
        $content = $this->engine->render($template . '.twig');
        $this->setContent($content);
        return $this;
    }
}
