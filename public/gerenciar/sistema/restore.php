<?php
require_once(dirname(dirname(__DIR__)) . '/app.php'); // main app file

need_permission(PermissaoNome::RESTAURACAO, true);

try {
    set_time_limit(0);
    $inputname = 'zipfile';
    if (!isset($_FILES[$inputname])) {
        throw new \Exception('Nenhum dados foi enviado', 401);
    }
    $file = $_FILES[$inputname];
    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        throw new \Exception('Nenhum arquivo foi enviado', 401);
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new \UploadException($file['error']);
    }
    $zip = new ZipArchive;
    $zip->open($file['tmp_name']);

    zip_extract_folder(
        $zip,
        [
            'Site/Upload' => WWW_ROOT . '/static/upload',
            'Site/Documents' => DOC_ROOT,
            'Site/Images/header' => IMG_ROOT . '/header',
            'Site/Images/patrimonio' => IMG_ROOT . '/patrimonio'
        ]
    );

    // Close and release file
    $zip->close();

    json(null, []);
} catch (\Exception $e) {
    json($e->getMessage());
}
