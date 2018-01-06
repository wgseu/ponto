<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php'); // main app file

need_permission(PermissaoNome::ALTERARCONFIGURACOES, isset($_POST));

$tab_opcoes = 'active';
$opcoes_comportamento = array(
    array('section' => 'Sistema', 'key' => 'Auto.Logout', 'default' => false, 'title' => 'Fazer logout automaticamente após inatividade'),
    array('section' => 'Comandas', 'key' => 'PrePaga', 'default' => false, 'title' => 'Comanda pré-paga'),
    array('section' => 'Vendas', 'key' => 'Exibir.Cancelados', 'default' => false, 'title' => 'Mostrar produtos cancelados nas vendas'),
    array('section' => 'Vendas', 'key' => 'Lembrar.Atendente', 'default' => false, 'title' => 'Lembrar o último atendente nas vendas'),
    array('section' => 'Estoque', 'key' => 'Estoque.Negativo', 'default' => false, 'title' => 'Permitir estoque negativo'),
    array('section' => 'Vendas', 'key' => 'Tela.Cheia', 'default' => true, 'title' => 'Exibir a tela de venda rápida em tela cheia'),
    array('section' => 'Sistema', 'key' => 'Backup.Auto', 'default' => true, 'title' => 'Realizar backup automaticamente'),
    array('section' => 'Vendas', 'key' => 'Balcao.Comissao', 'default' => false, 'title' => 'Comissão na venda balcão'),
    array('section' => 'Sistema', 'key' => 'Tablet.Logout', 'default' => false, 'title' => 'Fazer logout no tablet após lançar pedido'),
    array('section' => 'Vendas', 'key' => 'Mesas.Juntar', 'default' => true, 'title' => 'Reservar mesas ao juntar'),
    array('section' => 'Vendas', 'key' => 'Mesas.Redirecionar', 'default' => false, 'title' => 'Redirecionar para a mesa principal'),
    array('section' => 'Vendas', 'key' => 'Comanda.Observacao', 'default' => false, 'title' => 'Observação como nome de comanda'),
    array('section' => 'Vendas', 'key' => 'Quantidade.Perguntar', 'default' => true, 'title' => 'Confirmar ao lançar quantidades elevadas'),
);
#    array('section' => 'Sistema', 'key' => 'Logout.Timeout', 'default' => 3, 'title' => 'Minutos de inatividade'),

if ($_POST) {
    try {
        if (!config_values_exists($opcoes_comportamento, $_POST['secao'], $_POST['chave'])) {
            throw new Exception('A opção de comportamento informada não existe', 1);
        }
        set_boolean_config($_POST['secao'], $_POST['chave'], $_POST['marcado'] == 'Y');
        $__sistema__->salvarOpcoes($__options__);
        try {
            $appsync = new AppSync();
            $appsync->systemOptionsChanged();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        json(array('status' => 'ok'));
    } catch (Exception $e) {
        json($e->getMessage());
    }
}
include template('gerenciar_sistema_opcoes');
