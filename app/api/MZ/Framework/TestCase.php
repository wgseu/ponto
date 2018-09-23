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
namespace MZ\Framework;

use Symfony\Component\HttpFoundation\Request;

/**
 * Frontend testing helper
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Execute http GET request and return json decoded as array
     * @param string $url url to fetch data
     * @param mixed[] $query url query params
     * @return mixed[] response data
     */
    public function get($url, $query = [])
    {
        $request = Request::create($url, 'GET', $query);
        $result = \json_decode(app()->dispatch($request)->getContent(), true);
        return $result;
    }

    /**
     * Execute http POST request and return json decoded as array
     * @param string $url url to post data
     * @param mixed[] $data data to submit
     * @param boolean $form send as form url encoded
     * @return mixed[] response data
     */
    public function post($url, $data, $form = false)
    {
        $content = $form ? null : \json_encode($data);
        $server = $form ? [] : ['Content-Type' => 'application/json'];
        $parameters = $form ? $data : [];
        $request = Request::create(
            $url,
            'POST',
            $parameters,
            [],
            [],
            $server,
            $content
        );
        $result = \json_decode(app()->dispatch($request)->getContent(), true);
        return $result;
    }

    /**
     * Execute http PUT request and return json decoded as array
     * @param string $url url to post data
     * @param mixed[] $data data to submit
     * @param boolean $form send as form url encoded
     * @return mixed[] response data
     */
    public function put($url, $data = [], $form = false)
    {
        $content = $form ? null : \json_encode($data);
        $server = $form ? [] : ['Content-Type' => 'application/json'];
        $parameters = $form ? $data : [];
        $request = Request::create(
            $url,
            'PUT',
            $parameters,
            [],
            [],
            $server,
            $content
        );
        $result = \json_decode(app()->dispatch($request)->getContent(), true);
        return $result;
    }

    /**
     * Execute http PATCH request and return json decoded as array
     * @param string $url url to post data
     * @param mixed[] $data data to submit
     * @param boolean $form send as form url encoded
     * @return mixed[] response data
     */
    public function patch($url, $data = [], $form = false)
    {
        $content = $form ? null : \json_encode($data);
        $server = $form ? [] : ['Content-Type' => 'application/json'];
        $parameters = $form ? $data : [];
        $request = Request::create(
            $url,
            'PATCH',
            $parameters,
            [],
            [],
            $server,
            $content
        );
        $result = \json_decode(app()->dispatch($request)->getContent(), true);
        return $result;
    }

    /**
     * Execute http DELETE request and return json decoded as array
     * @param string $url url to post data
     * @param mixed[] $query url query params
     * @return mixed[] response data
     */
    public function delete($url, $query = [])
    {
        $request = Request::create($url, 'DELETE', $query);
        $result = \json_decode(app()->dispatch($request)->getContent(), true);
        return $result;
    }
}
