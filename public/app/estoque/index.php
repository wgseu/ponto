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

use MZ\Stock\Estoque;
use MZ\Environment\Setor;
use MZ\System\Permissao;

if (!is_login()) {
    json('Usuário não autenticado!');
}
$values = isset($_POST['estoque']) ? $_POST['estoque'] : [];
try {
    if (!logged_employee()->has(Permissao::NOME_ESTOQUE)) {
        throw new \Exception('Você não tem permissão para inserir no estoque');
    }
    $setor = Setor::findDefault();
    $estoque = new Estoque($values);
    $estoque->filter(new Estoque());
    $estoque->setTipoMovimento(Estoque::TIPO_MOVIMENTO_ENTRADA);
    $estoque->setSetorID($setor->getID());
    $estoque->setFuncionarioID(logged_employee()->getID());
    $estoque->setCancelado('N');
    $estoque->insert();
    json('estoque', $estoque->publish());
} catch (\Exception $e) {
    json($e->getMessage());
}
