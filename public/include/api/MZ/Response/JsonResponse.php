<?php
/**
 * Copyright (c) 2016 MZ Desenvolvimento de Sistemas LTDA - All Rights Reserved
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Response;

use MZ\Core\Response;

/**
 * Output JSON response
 */
class JsonResponse extends Response
{
    public function output($content)
    {
        $this->getProcessor()->header('Content-Type', 'application/json');
        parent::output(json_encode($content));
    }

    public function error($message, $code = null, $errors = [])
    {
        $response = [
            'resposta' => 'erro',
            'mensagem' => $message
        ];
        if (!is_null($code)) {
            $response['codigo'] = $code;
        }
        if (!empty($errors)) {
            $response['erros'] = $errors;
        }
        $this->output($response);
    }

    public function warning($message, $content = [], $code = null)
    {
        $response = [
            'resposta' => 'aviso',
            'mensagem' => $message
        ];
        if (!is_null($code)) {
            $response['codigo'] = $code;
        }
        $this->output(array_merge($response, $content));
    }

    public function success($content = [], $message = null)
    {
        $response = [
            'resposta' => 'ok'
        ];
        if (!is_null($message)) {
            $response['mensagem'] = $message;
        }
        $this->output(array_merge($response, $content));
    }
}
