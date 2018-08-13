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

use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use MZ\Response\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MZ\Exception\AuthorizationException;

/**
 * Allow application to serve system resources
 */
abstract class Controller
{
    private $application;

    /**
     * Constructor for a new instance of Controller
     * @param Application $application Main application
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * Get the application object
     * @return Application application object
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Get current request
     * @return \Symfony\Component\HttpFoundation\Request http request
     */
    public function getRequest()
    {
        return $this->getApplication()->getRequest();
    }

    /**
     * Detect response object from context, JsonResponse or HtmlResponse
     * @return \MZ\Response\HtmlResponse contextual response object
     */
    public function getResponse()
    {
        return new \MZ\Response\HtmlResponse(
            $this->getApplication()->getSystem()->getSettings()
        );
    }

    public function needPermission($permission)
    {
        if (!$this->getApplication()->getAuthentication()->getEmployee()->has($permission)) {
            settype($permission, 'array');
            throw new AuthorizationException(_t('need.permission'), Response::HTTP_UNAUTHORIZED, $permission);
        }
    }

    /**
     * Detect response object from context, JsonResponse or HtmlResponse
     * @param string $template template name
     * @param array $data data to pass to template
     * @return \MZ\Response\HtmlResponse template response object
     */
    public function view($template, $data = [])
    {
        $response = $this->getResponse();
        foreach ($data as $key => $value) {
            $response->getEngine()->{$key} = $value;
        }
        return $response->output($template);
    }

    /**
     * Redirect to give path
     * @param string $path path to redirect
     * @return \Symfony\Component\HttpFoundation\RedirectResponse redirect response
     */
    public function redirect($path)
    {
        return new RedirectResponse($path);
    }

    /**
     * Json response
     * @param array $data json array
     * @return \MZ\Response\JsonResponse json response
     */
    public function json($data = [])
    {
        return new JsonResponse($data);
    }

    /**
     * @param \Symfony\Component\Routing\RouteCollection $collection
     */
    public static function addRoutes($collection)
    {
        $routes = static::getRoutes();
        foreach ($routes as $info) {
            $route = new Route(
                $info['path'],
                ['_controller' => static::class . '@' . $info['controller']], // default values
                isset($info['requirements']) ? $info['requirements'] : [], // requirements
                [], // options
                '', // host
                [], // schemes
                $info['method'] // methods
            );
            $collection->add($info['name'], $route);
        }
    }
}
