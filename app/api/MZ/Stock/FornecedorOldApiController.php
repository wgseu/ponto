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

/**
 * Allow application to serve system resources
 */
class FornecedorOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        app()->needManager();

        $limite = max(1, min(20, $this->getRequest()->query->getInt('limite', 5)));
        $primeiro = $this->getRequest()->query->get('primeiro');
        $search = $this->getRequest()->query->get('busca');
        if ($primeiro || check_fone($search, true)) {
            $limite = 1;
        }
        $condition = [];
        if ($search) {
            $condition['search'] = $search;
        }
        $fornecedores = Fornecedor::findAll($condition, [], $limite);
        $items = [];
        $domask = $this->getRequest()->query->getBoolean('format');
        foreach ($fornecedores as $fornecedor) {
            $cliente = $fornecedor->findEmpresaID();
            $cliente_item = $cliente->publish();
            $item = $fornecedor->publish();
            $item['nome'] = $cliente->getNome();
            $item['fone1'] = $cliente->getFone(1);
            $item['cnpj'] = $cliente->getCPF();
            if ($domask) {
                $item['fone1'] = $cliente_item['fone1'];
                $item['cnpj'] = $cliente_item['cpf'];
            }
            $item['email'] = $cliente->getEmail();
            $items[] = $item;
        }
        return $this->json()->success(['items' => $items]);
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'app_fornecedor_find',
                'path' => '/app/fornecedor/',
                'method' => 'GET',
                'controller' => 'find',
            ]
        ];
    }
}
