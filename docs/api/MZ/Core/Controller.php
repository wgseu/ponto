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
    /**
     * Constructor for a new instance of Controller
     */
    public function __construct()
    {
    }

    /**
     * Get current request
     * @return \Symfony\Component\HttpFoundation\Request http request
     */
    public function getRequest()
    {
        return app()->getRequest();
    }

    /**
     * Detect response object from context, JsonResponse or HtmlResponse
     * @return \MZ\Response\HtmlResponse contextual response object
     */
    public function getResponse()
    {
        return new \MZ\Response\HtmlResponse(
            app()->getSystem()->getSettings()
        );
    }

    /**
     * Require permissions for logged provider
     * @param array $permissions permission need
     * @return self Application
     * @throws \MZ\Exception\AuthorizationException
     */
    public function needPermission($permission)
    {
        return app()->needPermission($permission);
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
     * Return true if request has output as json
     * @return bool true for json output, false otherwise
     */
    public function isJson()
    {
        return $this->getRequest()->query->get('saida') == 'json';
    }

    /**
     * Get current request array
     * @param array $defaults default values
     * @return array request data
     */
    public function getData($defaults = [])
    {
        return array_merge($defaults, $this->getRequest()->request->all());
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
            $defaults = ['_controller' => static::class . '@' . $info['controller']];
            $_defaults = isset($info['defaults']) ? $info['defaults'] : [];
            $defaults = \array_merge($defaults, $_defaults);
            $route = new Route(
                $info['path'],
                $defaults, // default values
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
