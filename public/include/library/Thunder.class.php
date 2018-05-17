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
class Thunder
{
    private static function message($type, $msg, $auto_close)
    {
        $messages = [];
        if (\Session::Get('thunder') != null) {
            $messages = unserialize(\Session::Get('thunder', true));
        }
        $messages[] = ['type' => $type, 'data' => ['message' => $msg, 'auto_close' => $auto_close]];
        \Session::Set('thunder', serialize($messages));
    }
    
    public static function warning($msg, $auto_close = false)
    {
        self::message('attention', $msg, $auto_close);
    }
    
    public static function success($msg, $auto_close = false)
    {
        self::message('success', $msg, $auto_close);
    }
    
    public static function error($msg, $auto_close = false)
    {
        self::message('error', $msg, $auto_close);
    }
    
    public static function information($msg, $auto_close = false)
    {
        self::message('information', $msg, $auto_close);
    }

    public static function execute()
    {
        $messages = [];
        if (\Session::Get('thunder') != null) {
            $messages = unserialize(\Session::Get('thunder', true));
        }
        $values = [];
        foreach ($messages as $type => $value) {
            $values[] = [
                'type' => $value['type'],
                'text' => $value['data']['message'],
                'auto_close' => $value['data']['auto_close'] ? 'true': 'false'
            ];
        }
        return $values;
    }
}
