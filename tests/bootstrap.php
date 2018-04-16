<?php
$app = require_once dirname(__DIR__) . '/public/include/application.php';

$app->run(null, function ($app) {
    $script = dirname(__DIR__) . '/database/model/sqlite.sql';
    \DB::$pdo->getPdo()->exec(file_get_contents($script));
    $script = dirname(__DIR__) . '/database/model/sqlite_insert.sql';
    \DB::$pdo->getPdo()->exec(file_get_contents($script));
});
