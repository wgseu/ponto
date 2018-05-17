<?php
global $app;
$app = require_once dirname(__DIR__) . '/public/include/application.php';

use MZ\Database\DB;

$app->run(null, function ($app) {
    $script = dirname(__DIR__) . '/database/model/sqlite.sql';
    DB::getPdo()->exec(file_get_contents($script));
    $script = dirname(__DIR__) . '/database/model/sqlite_insert.sql';
    DB::getPdo()->exec(file_get_contents($script));
});
