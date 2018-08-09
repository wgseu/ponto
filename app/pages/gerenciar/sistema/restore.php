<?php
require_once(dirname(dirname(__DIR__)) . '/app.php'); // main app file

use MZ\System\Permissao;

need_permission(Permissao::NOME_RESTAURACAO, true);

try {
    set_time_limit(0);
    $inputname = 'zipfile';
    if (!isset($_FILES[$inputname])) {
        throw new \Exception('Nenhum dado foi enviado', 401);
    }
    $file = $_FILES[$inputname];
    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        throw new \Exception('Nenhum arquivo foi enviado', 401);
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new \MZ\Exception\UploadException($file['error']);
    }
    $zip = new \ZipArchive();
    $zip->open($file['tmp_name']);

    zip_extract_folder(
        $zip,
        [
            'Site/Upload' => $app->getPath('public') . '/static/upload',
            'Site/Documents' => $app->getPath('docs'),
            'Site/Images/header' => $app->getPath('image') . '/header',
            'Site/Images/patrimonio' => $app->getPath('image') . '/patrimonio'
        ]
    );

    // Close and release file
    $zip->close();

    json(null, []);
} catch (\Exception $e) {
    json($e->getMessage());
}
