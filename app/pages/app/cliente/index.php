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
use MZ\System\Permissao;
use MZ\Account\Cliente;
use MZ\Util\Generator;

if (!is_login()) {
    json('Usuário não autenticado!');
}
$values = isset($_POST['cliente']) ? $_POST['cliente'] : [];
$old_cliente = new Cliente();
try {
    if (!logged_employee()->has(Permissao::NOME_PEDIDOMESA) &&
        !logged_employee()->has(Permissao::NOME_PEDIDOCOMANDA) &&
        !logged_employee()->has(Permissao::NOME_PAGAMENTO) &&
        !logged_employee()->has(Permissao::NOME_CADASTROCLIENTES)
    ) {
        throw new \Exception('Você não tem permissão para cadastrar clientes');
    }
    $cliente = new Cliente($values);
    $cliente->setSenha(Generator::token().'a123Z');
    $cliente->filter($old_cliente);
    $cliente->insert();
    $old_cliente->clean($cliente);
    $item = $cliente->publish();
    $item['imagemurl'] = $item['imagem'];
    json('cliente', $item);
} catch (\Exception $e) {
    json($e->getMessage());
}
