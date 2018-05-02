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
require_once(dirname(dirname(__DIR__)) . '/app.php');

use MZ\System\Integracao;
use MZ\System\Permissao;
use MZ\Payment\Cartao;

need_permission(Permissao::NOME_CADASTROCARTOES, is_output('json'));

$integracao = Integracao::findByAcessoURL(\MZ\Integrator\Kromax::NAME);
$codigos = \MZ\Integrator\Kromax::CARDS;
$association = new \MZ\Association\Card($integracao, $codigos);

if (isset($_GET['action']) && is_post() && $_GET['action'] == 'update') {
    need_permission(Permissao::NOME_CADASTROCARTOES, true);
    try {
        $codigo = isset($_POST['codigo'])?$_POST['codigo']:null;
        $id = array_key_exists('id', $_POST)?$_POST['id']:null;
        $cartao = $association->update($codigo, $id);
        json(null, ['cartao' => $cartao->toArray()]);
    } catch (\Exception $e) {
        json($e->getMessage());
    }
}
$codigos = $association->findAll();
$_imagens = Cartao::getImages();
$app->getResponse('html')->output('gerenciar_cartao_associar');
