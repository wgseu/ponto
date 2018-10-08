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
namespace MZ\Stock;

use MZ\Environment\Setor;
use MZ\System\Permissao;

/**
 * Allow application to serve system resources
 */
class EstoqueOldApiController extends \MZ\Core\ApiController
{
    public function add()
    {
        if (!app()->getAuthentication()->isLogin()) {
            return $this->json()->error('Usuário não autenticado!');
        }
        try {
            if (!app()->auth->has([Permissao::NOME_ESTOQUE])) {
                throw new \Exception('Você não tem permissão para inserir no estoque');
            }
            $setor = Setor::findDefault();
            $estoque = new Estoque($this->getRequest()->request->get('estoque'));
            $estoque->filter(new Estoque());
            $estoque->setTipoMovimento(Estoque::TIPO_MOVIMENTO_ENTRADA);
            $estoque->setSetorID($setor->getID());
            $estoque->setPrestadorID(app()->auth->provider->getID());
            $estoque->setCancelado('N');
            $estoque->insert();
            return $this->json()->success(['estoque' => $estoque->publish()]);
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
                'name' => 'app_estoque_add',
                'path' => '/app/estoque/',
                'method' => 'POST',
                'controller' => 'add',
            ]
        ];
    }
}
