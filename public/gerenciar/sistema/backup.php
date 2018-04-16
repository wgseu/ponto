<?php
require_once(dirname(dirname(__DIR__)) . '/app.php');

use MZ\System\Permissao;

need_permission(Permissao::NOME_BACKUP, true);

try {
    set_time_limit(0);
    // Prepare File
    $file = tempnam("tmp", "zip");
    $zip = new \ZipArchive();
    $zip->open($file, \ZipArchive::OVERWRITE);

    zip_add_folder($zip, $app->getPath('public') . '/static/upload', 'Site/Upload/');
    zip_add_folder($zip, $app->getPath('docs'), 'Site/Documents/');
    zip_add_folder($zip, $app->getPath('image') . '/header', 'Site/Images/header/');
    zip_add_folder($zip, $app->getPath('image') . '/patrimonio', 'Site/Images/patrimonio/');

    // Close and send to users
    $zip->close();

    $filename = 'GrandChef_'.date('Y-m-d_H-i-s').'.Site.Backup.zip';
    header('Content-Type: application/zip');
    header('Content-Length: ' . filesize($file));
    header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($filename));
    readfile($file);
    unlink($file);
} catch (\Exception $e) {
    json($e->getMessage());
}
