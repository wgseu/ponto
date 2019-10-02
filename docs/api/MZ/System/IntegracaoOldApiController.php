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

/**
 * Allow application to serve system resources
 */
class IntegracaoOldApiController extends \MZ\Core\ApiController
{
    public function update()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        if (!$this->getRequest()->isMethod('POST')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }
        $id = $this->getRequest()->request->get('id');
        $integracao = Integracao::findByID($id);
        if (!$integracao->exists()) {
            $msg = 'A integração não foi informada ou não existe';
            return $this->json()->error($msg);
        }
        $old_integracao = $integracao;
        try {
            $integracao = new Integracao($this->getRequest()->request->all());
            $integracao->filter($old_integracao, app()->auth->provider);
            $integracao->save(array_keys($this->getRequest()->request->all()));
            $old_integracao->clean($integracao);
            $msg = sprintf(
                'Integração "%s" atualizada com sucesso!',
                $integracao->getNome()
            );
            return $this->json()->success(['item' => $integracao->publish(app()->auth->provider)], $msg);
        } catch (\Exception $e) {
            $integracao->clean($old_integracao);
            $errors = [];
            if ($e instanceof \MZ\Exception\ValidationException) {
                $errors = $e->getErrors();
            }
            return $this->json()->error($e->getMessage(), null, $errors);
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
                'name' => 'app_integracao_update',
                'path' => '/gerenciar/integracao/opcoes',
                'method' => 'POST',
                'controller' => 'update',
            ]
        ];
    }
}
