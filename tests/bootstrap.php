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
require __DIR__.'/../bootstrap/autoload.php';

use MZ\Database\DB;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

global $app;
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->setSession(new Session(new MockArraySessionStorage()));
$app->run(
    function ($app) {},
    function ($app) {
        DB::getFpdo()->convertTypes = false;
        $script = dirname(__DIR__) . '/database/model/sqlite.sql';
        DB::getPdo()->exec(file_get_contents($script));
        $script = dirname(__DIR__) . '/database/model/sqlite_insert.sql';
        DB::getPdo()->exec(file_get_contents($script));
    }
);

function getExpectedBuffer($name, $content)
{
    $ext = '';
    if (!\preg_match('/\.\w+$/', $name)) {
        $ext = '.bin';
    }
    $filename = __DIR__ . '/resources/' . $name . $ext;
    if (!\file_exists($filename)) {
        \file_put_contents($filename, $content);
    }
    return \file_get_contents($filename);
}
