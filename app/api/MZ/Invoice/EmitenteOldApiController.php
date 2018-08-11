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
namespace MZ\Invoice;

use MZ\System\Permissao;

/**
 * Allow application to serve system resources
 */
class EmitenteOldApiController extends \MZ\Core\ApiController
{
    public function certificate()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, true);
        
        if (!is_post()) {
            json('Nenhum dado foi enviado');
        }
        
        try {
            $cert_file = upload_document('certificado', 'cert', 'certificado.pfx');
            if (is_null($cert_file)) {
                throw new \Exception('O certificado não foi enviado', 404);
            }
            $cert_path = $app->getPath('public') . get_document_url($cert_file, 'cert');
            $cert_store = file_get_contents($cert_path);
            unlink($cert_path);
            if ($cert_store === false) {
                throw new \Exception('Não foi possível ler o arquivo', 1);
            }
            if (!openssl_pkcs12_read($cert_store, $cert_info, $_POST['senha'])) {
                throw new \Exception('Senha incorreta', 1);
            }
            $certinfo = openssl_x509_parse($cert_info['cert']);
            file_put_contents($app->getPath('public') . get_document_url('public.pem', 'cert'), $cert_info['cert']);
            file_put_contents($app->getPath('public') . get_document_url('private.pem', 'cert'), $cert_info['pkey']);
            json('chave', [
                'publica' => 'public.pem',
                'privada' => 'private.pem',
                'expiracao' => date('Y-m-d H:i:s', $certinfo['validTo_time_t']),
            ]);
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'emitente_certificate',
                'path' => '/gerenciar/emitente/certificado',
                'method' => 'GET',
                'controller' => 'certificate',
            ]
        ];
    }
}
