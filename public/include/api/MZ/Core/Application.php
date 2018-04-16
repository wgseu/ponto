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

class Application
{
    private $authentication;
    private $settings;
    private $database;
    private $system;
    private $path;
    private $response;

    public function __construct($path)
    {
        $this->path = $path;
        $this->system = new \MZ\System\Sistema();
        $this->authentication = new \MZ\Account\Authentication();
    }

    /**
     * Get system object
     * @return \MZ\System\Sistema system object
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * Get authentication object
     * @return \MZ\Account\Authentication authentication object
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * Get database object
     * @return \DB database object
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Get response object
     * @param  string $format default format, json or html
     * @return Response response object
     */
    public function getResponse($format = null)
    {
        if (is_null($this->response)) {
            $this->detectResponse($format);
        }
        return $this->response;
    }

    /**
     * Detect response object from context, JsonResponse or HtmlResponse
     * @param  string $saida Choose output format, json or html
     * @return Response contextual response object or from choose
     */
    public function detectResponse($saida = null)
    {
        if (is_null($saida)) {
            $saida = isset($_GET['saida']) ? $_GET['saida'] : null;
            $saida = $_POST && isset($_POST['saida']) ? $_POST['saida'] : $saida;
        }
        switch ($saida) {
            case 'json':
                $this->response = new \MZ\Response\JsonResponse(new Processor());
                break;
            default:
                $this->response = new \MZ\Response\HtmlResponse(
                    new Processor(),
                    $this->getSystem()->getSettings()
                );
        }
        return $this->getResponse();
    }

    /**
     * Build a URL for this site
     * @param  string $path path to append
     * @return string       full link URL
     */
    public function makeURL($path)
    {
        return $this->getSystem()->getURL() . $path;
    }

    /**
     * Get path from name
     * @return string absolute path for give name
     */
    public function getPath($name)
    {
        return $this->getSettings()->getValue('path', $name);
    }

    /**
     * Initialize settings for the application
     */
    private function initialize()
    {
        \Session::Init();
        $this->getSystem()->initialize($this->path);
        $this->getAuthentication()->initialize();
    }

    /**
     * Load settings that require connected resources
     */
    private function load()
    {
        $this->getSystem()->loadAll();
        $this->getAuthentication()->load();
    }

    /**
     * Connect to the database and others connections
     */
    private function connect()
    {
        $this->database = \DB::Instance($this->getSystem()->getSettings()->getValue('db'));
    }

    /**
     * Execute the application
     */
    public function run($ready = null, $connected = null)
    {
        try {
            $this->initialize();
            $this->connect();
            if (is_callable($connected)) {
                $connected($this);
            }
            $this->load();
            if (is_callable($ready)) {
                $ready($this);
            }
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Handle exceptions and show message to user
     * @param  \Exception $e exception throwed
     */
    private function handleException($e)
    {
        throw $e;
    }
}
