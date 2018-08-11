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

/**
 * Allow application to serve system resources
 */
class SistemaOldApiController extends \MZ\Core\ApiController
{
    public function backup()
    {
        need_permission(Permissao::NOME_BACKUP, true);

        try {
            set_time_limit(0);
            // Prepare File
            $file = tempnam(sys_get_temp_dir(), 'zip');
            $zip = new \ZipArchive();
            $zip->open($file, \ZipArchive::OVERWRITE);

            zip_add_folder($zip, $this->getApplication()->getPath('public') . '/static/upload', 'Site/Upload/');
            zip_add_folder($zip, $this->getApplication()->getPath('docs'), 'Site/Documents/');
            zip_add_folder($zip, $this->getApplication()->getPath('image') . '/header', 'Site/Images/header/');
            zip_add_folder($zip, $this->getApplication()->getPath('image') . '/patrimonio', 'Site/Images/patrimonio/');

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
    }

    public function restore()
    {
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
                    'Site/Upload' => $this->getApplication()->getPath('public') . '/static/upload',
                    'Site/Documents' => $this->getApplication()->getPath('docs'),
                    'Site/Images/header' => $this->getApplication()->getPath('image') . '/header',
                    'Site/Images/patrimonio' => $this->getApplication()->getPath('image') . '/patrimonio'
                ]
            );

            // Close and release file
            $zip->close();

            json(null, []);
        } catch (\Exception $e) {
            json($e->getMessage());
        }
    }

    public function tasks()
    {


        define('TASK_TOKEN', 'WWHxIdzDakrea921zGveQkKccrf80mDp');

        set_time_limit(0);

        if (!isset($_GET['token']) || $_GET['token'] != TASK_TOKEN) {
            need_permission(Permissao::NOME_ENTREGAPEDIDOS, true);
        }
        try {
            $runner = new Runner();
            $runner->execute();
            json(
                'result',
                [
                    'processed' => $runner->getProcessed(),
                    'pending' => $runner->getPending(),
                    'failed' => $runner->getFailed(),
                    'errors' => $runner->getErrors()
                ]
            );
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            json($e->getMessage());
        }
    }

    public function upgrade()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, true);

        try {
            $outputs = [];
            $products = [];
            $produtos = Produto::findAll();
            foreach ($produtos as $produto) {
                if (is_null($produto->getImagem())) {
                    continue;
                }
                $produto->loadImagem();
                $imagebytes = $produto->getImagem();
                $name = $produto->getDescricao().'.png';
                $name = iconv("UTF-8//IGNORE", "WINDOWS-1252//IGNORE", $name);
                $type = 'produto';
                $dir = $this->getApplication()->getPath('image') . '/' . $type . '/';
                $name = generate_file_name($dir, '.png', $name, true);
                $path = $dir . $name;
                file_put_contents($path, $imagebytes);
                $name = iconv("WINDOWS-1252//IGNORE", "UTF-8//IGNORE", $name);
                $imagemurl = get_image_url($name, $type, null);
                $products[] = ['id' => $produto->getID(), 'imagemurl' => $imagemurl];
            }
            $outputs['produtos'] = $products;
            json(null, $outputs);
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
                'name' => 'sistema_backup',
                'path' => '/gerenciar/sistema/backup',
                'method' => 'GET',
                'controller' => 'backup',
            ],
            [
                'name' => 'sistema_restore',
                'path' => '/gerenciar/sistema/restore',
                'method' => 'POST',
                'controller' => 'restore',
            ],
            [
                'name' => 'sistema_tasks',
                'path' => '/gerenciar/sistema/tarefa',
                'method' => 'GET',
                'controller' => 'tasks',
            ],
            [
                'name' => 'sistema_upgrade',
                'path' => '/app/sistema/upgrade',
                'method' => 'GET',
                'controller' => 'upgrade',
            ]
        ];
    }
}
