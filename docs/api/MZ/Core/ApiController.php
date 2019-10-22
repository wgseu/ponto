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

use Doctrine\Common\Annotations\SimpleAnnotationReader;

/**
 * Allow application to serve system resources as API
 */
abstract class ApiController extends Controller
{
    /**
     * Returns a new json response instance
     * @return \MZ\Response\JsonResponse json response object
     */
    public function getResponse()
    {
        return new \MZ\Response\JsonResponse();
    }

    /**
     * Get current json request data
     * @param array $defaults default values
     * @return array json request data
     */
    public function getData($defaults = [])
    {
        $content = $this->getRequest()->getContent();
        $data = json_decode($content, true) ?: [];
        return array_merge($defaults, $data);
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        $routes = [];
        $class_exists = \class_exists('MZ\Routing\Annotation\Get') &&
            \class_exists('MZ\Routing\Annotation\Put') &&
            \class_exists('MZ\Routing\Annotation\Post') &&
            \class_exists('MZ\Routing\Annotation\Patch') &&
            \class_exists('MZ\Routing\Annotation\Delete');
        $methods = get_class_methods(static::class);
        $annotationReader = new SimpleAnnotationReader();
        $annotationReader->addNamespace('MZ\Routing\Annotation');
        foreach ($methods as $method_name) {
            $reflectionMethod = new \ReflectionMethod(static::class, $method_name);
            $methodAnnotations = $annotationReader->getMethodAnnotations($reflectionMethod);
            foreach ($methodAnnotations as $annotation) {
                if ($annotation instanceof \MZ\Routing\Annotation\Route) {
                    $classname = get_class($annotation);
                    $pos = strrpos($classname, '\\');
                    $http_method = strtoupper(substr($classname, $pos + 1));
                    $routes[] = [
                        'name' => $annotation->name,
                        'path' => $annotation->value,
                        'method' => $http_method,
                        'requirements' => $annotation->params,
                        'controller' => $method_name,
                    ];
                }
            }
        }
        return $routes;
    }
}
