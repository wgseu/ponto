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
     * @param array $query url query params
     * @return \Symfony\Component\HttpFoundation\Response|array response object or array
     */
    public function get($url, $query = [])
    {
        $request = Request::create($url, 'GET', $query);
        $response = app()->dispatch($request);
        if ($response->headers->get('Content-Type') == 'application/json') {
            return $this->fromJson($response);
        }
        return $response;
    }

    /**
     * Execute http POST request and return json decoded as array
     * @param string $url url to post data
     * @param array $data data to submit
     * @param boolean $form send as form url encoded
     * @param array $files files to submit
     * @return \Symfony\Component\HttpFoundation\Response|array response object or array
     */
    public function post($url, $data, $form = false, $files = [])
    {
        $content = $form ? null : \json_encode($data);
        $server = $form ? [] : ['Content-Type' => 'application/json'];
        $parameters = $form ? $data : [];
        $request = Request::create(
            $url,
            'POST',
            $parameters,
            [],
            $files,
            $server,
            $content
        );
        $response = app()->dispatch($request);
        if ($response->headers->get('Content-Type') == 'application/json') {
            return $this->fromJson($response);
        }
        return $response;
    }

    /**
     * Execute http PUT request and return json decoded as array
     * @param string $url url to post data
     * @param array $data data to submit
     * @param boolean $form send as form url encoded
     * @return \Symfony\Component\HttpFoundation\Response|array response object or array
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
        $response = app()->dispatch($request);
        if ($response->headers->get('Content-Type') == 'application/json') {
            return $this->fromJson($response);
        }
        return $response;
    }

    /**
     * Execute http PATCH request and return json decoded as array
     * @param string $url url to post data
     * @param array $data data to submit
     * @param boolean $form send as form url encoded
     * @return \Symfony\Component\HttpFoundation\Response|array response object or array
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
        $response = app()->dispatch($request);
        if ($response->headers->get('Content-Type') == 'application/json') {
            return $this->fromJson($response);
        }
        return $response;
    }

    /**
     * Execute http DELETE request and return json decoded as array
     * @param string $url url to post data
     * @param array $query url query params
     * @return \Symfony\Component\HttpFoundation\Response|array response object or array
     */
    public function delete($url, $query = [])
    {
        $request = Request::create($url, 'DELETE', $query);
        $response = app()->dispatch($request);
        if ($response->headers->get('Content-Type') == 'application/json') {
            return $this->fromJson($response);
        }
        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response response object or array
     * @return array data array
     */
    public function fromJson($response)
    {
        return \json_decode($response->getContent(), true);
    }
}
