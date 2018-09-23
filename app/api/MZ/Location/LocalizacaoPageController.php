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
namespace MZ\Location;

use MZ\System\Permissao;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class LocalizacaoPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);
        $localizacao = new Localizacao($condition);
        $cliente = $localizacao->findClienteID();
        if (!$cliente->exists()) {
            $msg = 'O cliente não foi informado ou não existe!';
            return $this->json()->error($msg);
        }
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Localizacao::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genBasic();
        $localizacoes = Localizacao::findAll($condition, $order, $limite, $pager->offset);

        $items = [];
        foreach ($localizacoes as $_localizacao) {
            $items[] = $_localizacao->publish();
        }
        return $this->json()->success(['items' => $items]);
    }

    public function add()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $localizacao = new Localizacao();
        $old_localizacao = $localizacao;
        if (is_post()) {
            $localizacao = new Localizacao($this->getData());
            try {
                DB::beginTransaction();
                if ($localizacao->getClienteID() == app()->getSystem()->getCompany()->getID() &&
                    !app()->auth->has([Permissao::NOME_ALTERARCONFIGURACOES])
                ) {
                    throw new \Exception('Você não tem permissão para atribuir um endereço a essa empresa!');
                }
                $old_localizacao->setClienteID($localizacao->getClienteID());
                $localizacao->filter($old_localizacao, true);
                $estado_id = $this->getRequest()->request->get('estadoid');
                $estado = \MZ\Location\Estado::findByID($estado_id);
                if (!$estado->exists()) {
                    throw new \MZ\Exception\ValidationException(
                        ['estadoid' => 'O estado não foi informado ou não existe!']
                    );
                }
                $cidade_id = $this->getRequest()->request->get('cidade');
                $cidade = \MZ\Location\Cidade::findOrInsert($estado->getID(), $cidade_id);
                $bairro_id = $this->getRequest()->request->get('bairro');
                $bairro = \MZ\Location\Bairro::findOrInsert($cidade->getID(), $bairro_id);
                $localizacao->setBairroID($bairro->getID());
                $localizacao->insert();
                $old_localizacao->clean($localizacao);
                DB::commit();
                $msg = sprintf(
                    'Localização "%s" atualizada com sucesso!',
                    $localizacao->getLogradouro()
                );
                return $this->json()->success(['item' => $localizacao->publish()], $msg);
            } catch (\Exception $e) {
                DB::rollBack();
                $localizacao->clean($old_localizacao);
                $errors = [];
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                return $this->json()->error($e->getMessage(), null, $errors);
            }
        } else {
            return $this->json()->error('Nenhum dado foi enviado');
        }
    }

    public function update()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);

        $id = $this->getRequest()->query->getInt('id', null);
        $localizacao = Localizacao::findByID($id);
        if (!$localizacao->exists()) {
            $msg = 'A localização não foi informada ou não existe';
            return $this->json()->error($msg);
        }
        if ($localizacao->getClienteID() == app()->getSystem()->getCompany()->getID() &&
            !app()->auth->has([Permissao::NOME_ALTERARCONFIGURACOES])
        ) {
            $msg = 'Você não tem permissão para alterar o endereço dessa empresa!';
            return $this->json()->error($msg);
        }
        $old_localizacao = $localizacao;
        if (is_post()) {
            $localizacao = new Localizacao($this->getData());
            try {
                DB::beginTransaction();
                $localizacao->filter($old_localizacao, true);
                $estado_id = $this->getRequest()->request->get('estadoid');
                $estado = Estado::findByID($estado_id);
                if (!$estado->exists()) {
                    throw new \MZ\Exception\ValidationException(
                        ['estadoid' => 'O estado não foi informado ou não existe!']
                    );
                }
                $cidade_id = $this->getRequest()->request->get('cidade');
                $cidade = Cidade::findOrInsert($estado->getID(), $cidade_id);
                $bairro_id = $this->getRequest()->request->get('bairro');
                $bairro = Bairro::findOrInsert($cidade->getID(), $bairro_id);
                $localizacao->setBairroID($bairro->getID());
                $localizacao->save();
                $old_localizacao->clean($localizacao);
                DB::commit();
                $msg = sprintf(
                    'Localização "%s" atualizada com sucesso!',
                    $localizacao->getLogradouro()
                );
                return $this->json()->success(['item' => $localizacao->publish()], $msg);
            } catch (\Exception $e) {
                DB::rollBack();
                $localizacao->clean($old_localizacao);
                $errors = [];
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                return $this->json()->error($e->getMessage(), null, $errors);
            }
        } else {
            return $this->json()->error('Nenhum dado foi enviado');
        }
    }

    public function delete()
    {
        $this->needPermission([Permissao::NOME_CADASTROCLIENTES]);
        $id = $this->getRequest()->query->getInt('id', null);
        $localizacao = Localizacao::findByID($id);
        if (!$localizacao->exists()) {
            $msg = 'A localização não foi informada ou não existe';
            return $this->json()->error($msg);
        }
        try {
            $localizacao->delete();
            $localizacao->clean(new Localizacao());
            $msg = sprintf(
                'Localização "%s" excluída com sucesso!',
                $localizacao->getApelido() ?: $localizacao->getLogradouro()
            );
            return $this->json()->success([], $msg);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir a Localização "%s"!',
                $localizacao->getApelido() ?: $localizacao->getLogradouro()
            );
            return $this->json()->error($msg);
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
                'name' => 'localizacao_find',
                'path' => '/gerenciar/localizacao/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'localizacao_add',
                'path' => '/gerenciar/localizacao/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'localizacao_update',
                'path' => '/gerenciar/localizacao/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'localizacao_delete',
                'path' => '/gerenciar/localizacao/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
        ];
    }
}
