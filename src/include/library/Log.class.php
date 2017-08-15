<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
class Log
{

    private static function write($type, $message)
    {
        $log_dir = dirname(dirname(__FILE__)).'/logs';
        $filename = $log_dir.'/'.date('Ymd').'.txt';
        $fp = fopen($filename, 'a');
        if (!$fp) {
            return;
        }
        fwrite($fp, date('d/m/Y H:i:s').' - '.$type.': '.$message."\n");
        fclose($fp);
        chmod($filename, 0755);
    }

    public static function error($message)
    {
        self::write('error', $message);
    }

    public static function warning($message)
    {
        self::write('warning', $message);
    }

    public static function debug($message)
    {
        self::write('debug', $message);
    }

    public static function information($message)
    {
        self::write('information', $message);
    }
}
