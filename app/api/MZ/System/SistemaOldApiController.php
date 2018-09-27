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
namespace MZ\System;

use MZ\Product\Produto;
use MZ\Task\Runner;
use MZ\Logger\Log;

/**
 * Allow application to serve system resources
 */
class SistemaOldApiController extends \MZ\Core\ApiController
{
    public function backup()
    {
        $this->needPermission([Permissao::NOME_BACKUP]);

        try {
            set_time_limit(0);
            // Prepare File
            $file = tempnam(sys_get_temp_dir(), 'zip');
            $zip = new \ZipArchive();
            $zip->open($file, \ZipArchive::OVERWRITE);

            zip_add_folder($zip, app()->getPath('public') . '/static/upload', 'Site/Upload/');
            zip_add_folder($zip, app()->getPath('docs'), 'Site/Documents/');
            zip_add_folder($zip, app()->getPath('image') . '/header', 'Site/Images/header/');
            zip_add_folder($zip, app()->getPath('image') . '/patrimonio', 'Site/Images/patrimonio/');

            // Close and send to users
            $zip->close();

            $filename = 'GrandChef_'.date('Y-m-d_H-i-s').'.Site.Backup.zip';
            header('Content-Type: application/zip');
            header('Content-Length: ' . filesize($file));
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($filename));
            readfile($file);
            unlink($file);
        } catch (\Exception $e) {
            return $this->json()->error($e->getMessage());
        }
    }

    public function restore()
    {
        $this->needPermission([Permissao::NOME_RESTAURACAO]);

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
                    'Site/Upload' => app()->getPath('public') . '/static/upload',
                    'Site/Documents' => app()->getPath('docs'),
                    'Site/Images/header' => app()->getPath('image') . '/header',
                    'Site/Images/patrimonio' => app()->getPath('image') . '/patrimonio'
                ]
            );

            // Close and release file
            $zip->close();

            return $this->json()->success();
        } catch (\Exception $e) {
            return $this->json()->error($e->getMessage());
        }
    }

    public function tasks()
    {
        define('TASK_TOKEN', 'WWHxIdzDakrea921zGveQkKccrf80mDp');
        set_time_limit(0);
        if ($this->getRequest()->query->get('token') != TASK_TOKEN) {
            $this->needPermission([Permissao::NOME_ENTREGAPEDIDOS]);
        }
        try {
            $runner = new Runner();
            $runner->execute();
            return $this->json()->success(['result' => [
                'processed' => $runner->getProcessed(),
                'pending' => $runner->getPending(),
                'failed' => $runner->getFailed(),
                'errors' => $runner->getErrors()
            ]]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->json()->error($e->getMessage());
        }
    }

    public function upgrade()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);

        try {
            $outputs = [];
            return $this->json()->success($outputs);
        } catch (\Exception $e) {
            return $this->json()->error($e->getMessage());
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
                'name' => 'app_sistema_backup',
                'path' => '/gerenciar/sistema/backup',
                'method' => 'GET',
                'controller' => 'backup',
            ],
            [
                'name' => 'app_sistema_restore',
                'path' => '/gerenciar/sistema/restore',
                'method' => 'POST',
                'controller' => 'restore',
            ],
            [
                'name' => 'app_sistema_tasks',
                'path' => '/gerenciar/sistema/tarefa',
                'method' => 'GET',
                'controller' => 'tasks',
            ],
            [
                'name' => 'app_sistema_upgrade',
                'path' => '/app/sistema/upgrade',
                'method' => 'GET',
                'controller' => 'upgrade',
            ]
        ];
    }
}
