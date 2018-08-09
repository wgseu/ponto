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

use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;

/**
 * Output JSON response
 */
class JsonResponse extends SymfonyJsonResponse
{
    public function error($message, $code = null, $errors = [])
    {
        $response = [
            'status' => 'error',
            'msg' => $message
        ];
        if (!is_null($code)) {
            $response['code'] = $code;
        }
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        $this->setData($response);
        return $this;
    }

    public function warning($message, $content = [], $code = null)
    {
        $response = [
            'status' => 'warning',
            'msg' => $message
        ];
        if (!is_null($code)) {
            $response['code'] = $code;
        }
        $this->setData(array_merge($response, $content));
        return $this;
    }

    public function success($content = [], $message = null)
    {
        $response = [
            'status' => 'ok'
        ];
        if (!is_null($message)) {
            $response['msg'] = $message;
        }
        $this->setData(array_merge($response, $content));
        return $this;
    }
}
