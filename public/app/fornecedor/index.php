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

need_manager(true);

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 5;
$primeiro = isset($_GET['primeiro']) ? $_GET['primeiro'] : null;
if ($primeiro || check_fone($_GET['busca'], true)) {
    $limite = 1;
} elseif ($limite < 1) {
    $limite = 5;
} elseif ($limite > 20) {
    $limite = 20;
}
$condition = [];
if (isset($_GET['busca'])) {
    $condition['search'] = $_GET['busca'];
}
$fornecedores = Fornecedor::findAll($condition, [], $limite);
$response = ['status' => 'ok'];
$campos = [
            'id',
            'nome',
            'fone1',
            'cnpj',
            'email',
            'prazopagamento',
            'imagemurl',
        ];
$_fornecedores = [];
$domask = intval($_GET['format']) != 0;
foreach ($fornecedores as $fornecedor) {
    $_fornecedor = $fornecedor->publish();
    $cliente = $fornecedor->findEmpresaID();
    $_fornecedor['nome'] = $cliente->getNome();
    $_fornecedor['fone1'] = $cliente->getFone(1);
    $_fornecedor['cnpj'] = $cliente->getCPF();
    $_fornecedor['email'] = $cliente->getEmail();
    if ($domask) {
        $_fornecedor['fone1'] = \MZ\Util\Mask::phone($_fornecedor['fone1']);
    }
    $_fornecedor['imagemurl'] = get_image_url($cliente->getImagem(), 'cliente', null);
    $_fornecedores[] = array_intersect_key($_fornecedor, array_flip($campos));
}
$response['items'] = $_fornecedores;
json($response);
