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
namespace MZ\Product;

/**
 * Allow application to serve system resources
 */
class ComposicaoOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        $produto_id = isset($_GET['produto']) ? $_GET['produto'] : null;
        $produto = Produto::findByID($produto_id);
        if (!$produto->exists()) {
            json('O produto não foi informado ou não existe');
        }
        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : null;
        if (!is_null($limite) && $limite < 1) {
            $limite = null;
        }
        $selecionaveis = isset($_GET['selecionaveis']) ? $_GET['selecionaveis'] : false;
        $adicionais = isset($_GET['adicionais']) ? $_GET['adicionais'] : false;
        $sem_opcionais = isset($_GET['sem_opcionais']) ? $_GET['sem_opcionais'] : false;
        $tipo = [
            Composicao::TIPO_COMPOSICAO => true,
            Composicao::TIPO_ADICIONAL => true,
            Composicao::TIPO_OPCIONAL => true
        ];
        if ($selecionaveis) {
            unset($tipo[Composicao::TIPO_COMPOSICAO]);
        }
        if ($selecionaveis && !$adicionais) {
            unset($tipo[Composicao::TIPO_ADICIONAL]);
        }
        if ($selecionaveis && $sem_opcionais) {
            unset($tipo[Composicao::TIPO_OPCIONAL]);
        }
        $tipo = array_keys($tipo);
        $condition = [];
        $condition['search'] = isset($_GET['busca']) ? $_GET['busca'] : null;
        $condition['tipo'] = $tipo;
        $condition['composicaoid'] = $produto->getID();
        $condition['ativa'] = 'Y';
        $composicoes = Composicao::findAll($condition, [], $limite);
        $items = [];
        foreach ($composicoes as $composicao) {
            $produto = $composicao->findProdutoID();
            $unidade = $produto->findUnidadeID();
            $item = $composicao->publish();
            $item['imagemurl'] = $produto->makeImagem(false, null);
            $item['produtodescricao'] = $produto->getDescricao();
            $item['produtoabreviacao'] = $produto->getAbreviacao();
            $item['produtoconteudo'] = $produto->getConteudo();
            $item['unidadesigla'] = $unidade->getSigla();
            $item['produtodataatualizacao'] = $produto->getDataAtualizacao();
            $item['selecionavel'] = $composicao->getTipo() == Composicao::TIPO_COMPOSICAO ? 'N' : 'Y';
            $items[] = $item;
        }
        json('composicoes', $items);
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'composicao_find',
                'path' => '/app/composicao/',
                'method' => 'GET',
                'controller' => 'find',
            ]
        ];
    }
}
