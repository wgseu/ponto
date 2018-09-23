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

use MZ\Database\DB;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use MZ\Exception\RedirectException;
use MZ\Exception\AuthorizationException;

class Application
{
    /**
     * Database connection
     * @var \MZ\Database\DB
     */
    public $db;
    /**
     * System options
     * @var \MZ\System\Sistema
     */
    public $system;
    /**
     * Authentication guard
     * @var \MZ\Account\Authentication
     */
    public $auth;
    /**
     * Root app path
     * @var string
     */
    private $path;
    /**
     * App session
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;
    /**
     * HTTP request
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;
    /**
     * Website URL
     * @var string
     */
    private $url;

    public function __construct($path)
    {
        $this->path = $path;
        $this->system = new \MZ\System\Sistema();
        $this->auth = new \MZ\Account\Authentication();
        $this->db = new DB();
        $this->session = new Session();
        $this->request = Request::createFromGlobals();
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
        return $this->auth;
    }

    /**
     * Get database object
     * @return \MZ\Database\DB database object
     */
    public function getDatabase()
    {
        return $this->db;
    }

    /**
     * Get session management
     * @return \Symfony\Component\HttpFoundation\Session\Session session management
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Get current request
     * @return \Symfony\Component\HttpFoundation\Request http request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Detect response object from context, JsonResponse or HtmlResponse
     * @return \Symfony\Component\HttpFoundation\Response contextual response object
     */
    public function getResponse()
    {
        $format = $this->getRequest()->query->get('saida', 'html');
        $format = $this->getRequest()->request->get('saida', $format);
        switch ($format) {
            case 'json':
                return new \MZ\Response\JsonResponse();
        }
        return new \MZ\Response\HtmlResponse(
            $this->getSystem()->getSettings()
        );
    }

    public function initUrl()
    {
        $host = $this->getRequest()->server->get('HTTP_HOST');
        if ($this->getRequest()->server->get('HTTPS') != 'off') {
            $protocol = 'https';
        } else {
            $protocol = 'http';
        }
        $this->url = "{$protocol}://{$host}";
    }

    /**
     * Get the current URL for this application system
     * @return string URL with protocol for this system
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * Build a URL for this site
     * @param  string $path path to append
     * @return string       full link URL
     */
    public function makeURL($path)
    {
        return $this->getURL() . $path;
    }

    /**
     * Get path from name
     * @return string absolute path for give name
     */
    public function getPath($name)
    {
        return $this->getSystem()->getSettings()->getValue('path', $name);
    }

    /**
     * Initialize settings for the application
     */
    private function initialize()
    {
        $this->initUrl();
        $this->getSession()->start();
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
        $this->getDatabase()->connect($this->getSystem()->getSettings()->getValue('db'));
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
            } else {
                $this->dispatch()->send();
            }
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Dispatch request
     * @param \Symfony\Component\HttpFoundation\Request $request http request
     * @return \Symfony\Component\HttpFoundation\Response response object
     */
    public function dispatch($request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $locator = new FileLocator([ $this->getPath('routes') ]);
        $router = new Router(
            new PhpFileLoader($locator),
            'web.php',
            [ 'cache_dir' => $this->getPath('cache') ],
            $context
        );
        $matched = $router->matchRequest($request);
        $save_request = $this->request;
        $this->request = $request;
        $response = $this->route($matched);
        $this->request = $save_request;
        return $response;
    }

    private function route($matched)
    {
        list($class, $method) = explode('@', $matched['_controller']);
        $service = new $class();
        try {
            $response = call_user_func_array(
                [$service, $method],
                array_slice($matched, 2) // skip: _route, _controller
            );
        } catch (\Exception $e) {
            $response = $service->getResponse();
            $this->translateException($e, $response);
        }
        return $response;
    }

    /**
     * Handle exceptions and show output to user
     * @param  \Exception $exception exception throwed
     * @param \Symfony\Component\HttpFoundation\Response $response response object for exception
     */
    private function translateException($exception, $response)
    {
        if ($response instanceof \MZ\Response\JsonResponse) {
            $errors = [];
            if ($exception instanceof \MZ\Exception\ValidationException) {
                $errors = $exception->getErrors();
            }
            $response->error($exception->getMessage(), $exception->getCode(), $errors);
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof ResourceNotFoundException) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->output('erro_404');
        }
    }

    /**
     * Handle exceptions and show output to user
     * @param  \Exception $exception exception throwed
     * @param \Symfony\Component\HttpFoundation\Response $response response object for exception
     */
    private function handleException($exception)
    {
        if ($exception instanceof RedirectException) {
            $response = new RedirectResponse($exception->getURL());
        } else {
            $response = $this->getResponse();
        }
        $this->translateException($exception, $response);
        $response->send();
    }

    /**
     * Require logged user
     * @return self Application
     * @throws \MZ\Exception\RedirectException
     */
    public function needLogin()
    {
        if (!$this->getAuthentication()->isLogin()) {
            throw new RedirectException(_t('need_login'), Response::HTTP_UNAUTHORIZED, '/conta/entrar');
        }
        return $this;
    }

    /**
     * Require logged user as provider
     * @return self Application
     * @throws \MZ\Exception\RedirectException
     */
    public function needManager()
    {
        $this->needLogin();
        if (!$this->getAuthentication()->isManager()) {
            throw new RedirectException(_t('need_manager'), Response::HTTP_FORBIDDEN, '/conta/entrar');
        }
        return $this;
    }

    /**
     * Require logged provider as owner
     * @return self Application
     * @throws \MZ\Exception\RedirectException
     */
    public function needOwner()
    {
        $this->needManager();
        if (!$this->getAuthentication()->isOwner()) {
            throw new RedirectException(_t('need_owner'), Response::HTTP_FORBIDDEN, '/gerenciar/');
        }
        return $this;
    }

    /**
     * Require permissions for logged provider
     * @param array $permissions permission need
     * @param string $message option error message
     * @return self Application
     * @throws \MZ\Exception\AuthorizationException
     */
    public function needPermission($permissions, $message = null)
    {
        $this->needManager();
        if (!$this->getAuthentication()->has($permissions)) {
            throw new AuthorizationException(
                $message ?: _t('$this->needPermission'),
                Response::HTTP_FORBIDDEN,
                $permissions
            );
        }
        return $this;
    }
}
