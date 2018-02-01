<?php
require_once(dirname(__DIR__) . '/app.php');

need_permission(PermissaoNome::ALTERARCONFIGURACOES, is_output('json'));

if (!is_post()) {
    json('Nenhum dado foi enviado');
}

try {
    $cert_file = upload_document('certificado', 'cert', 'certificado.pfx');
    if (is_null($cert_file)) {
        throw new Exception('O certificado não foi enviado', 404);
    }
    $cert_path = WWW_ROOT . get_document_url($cert_file, 'cert');
    $cert_store = file_get_contents($cert_path);
    unlink($cert_path);
    if ($cert_store === false) {
        throw new Exception('Não foi possível ler o arquivo', 1);
    }
    if (!openssl_pkcs12_read($cert_store, $cert_info, $_POST['senha'])) {
        throw new Exception('Senha incorreta', 1);
    }
    $certinfo = openssl_x509_parse($cert_info['cert']);
    file_put_contents(WWW_ROOT . get_document_url('public.pem', 'cert'), $cert_info['cert']);
    file_put_contents(WWW_ROOT . get_document_url('private.pem', 'cert'), $cert_info['pkey']);
    json('chave', array(
        'publica' => 'public.pem',
        'privada' => 'private.pem',
        'expiracao' => date('Y-m-d H:i:s', $certinfo['validTo_time_t']),
    ));
} catch (Exception $e) {
    json($e->getMessage());
}
