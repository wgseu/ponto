<?php
require_once(dirname(dirname(__DIR__)) . '/app.php'); // main app file

need_permission(PermissaoNome::ALTERARCONFIGURACOES, isset($_POST));

$tab_opcoes = 'active';
$opcoes_comportamento = [
    ['section' => 'Sistema', 'key' => 'Auto.Logout', 'default' => false, 'title' => 'Fazer logout automaticamente após inatividade'],
    ['section' => 'Comandas', 'key' => 'PrePaga', 'default' => false, 'title' => 'Comanda pré-paga'],
    ['section' => 'Vendas', 'key' => 'Exibir.Cancelados', 'default' => false, 'title' => 'Mostrar produtos cancelados nas vendas'],
    ['section' => 'Vendas', 'key' => 'Lembrar.Atendente', 'default' => false, 'title' => 'Lembrar o último atendente nas vendas'],
    ['section' => 'Estoque', 'key' => 'Estoque.Negativo', 'default' => false, 'title' => 'Permitir estoque negativo'],
    ['section' => 'Vendas', 'key' => 'Tela.Cheia', 'default' => true, 'title' => 'Exibir a tela de venda rápida em tela cheia'],
    ['section' => 'Sistema', 'key' => 'Backup.Auto', 'default' => true, 'title' => 'Realizar backup automaticamente'],
    ['section' => 'Vendas', 'key' => 'Balcao.Comissao', 'default' => false, 'title' => 'Comissão na venda balcão'],
    ['section' => 'Sistema', 'key' => 'Tablet.Logout', 'default' => false, 'title' => 'Fazer logout no tablet após lançar pedido'],
    ['section' => 'Vendas', 'key' => 'Mesas.Juntar', 'default' => true, 'title' => 'Reservar mesas ao juntar'],
    ['section' => 'Vendas', 'key' => 'Mesas.Redirecionar', 'default' => false, 'title' => 'Redirecionar para a mesa principal'],
    ['section' => 'Vendas', 'key' => 'Comanda.Observacao', 'default' => false, 'title' => 'Observação como nome de comanda'],
    ['section' => 'Vendas', 'key' => 'Quantidade.Perguntar', 'default' => true, 'title' => 'Confirmar ao lançar quantidades elevadas'],
    ['section' => 'Sistema', 'key' => 'Fiscal.Mostrar', 'default' => false, 'title' => 'Mostrar campos fiscais e tributários'],
    ['section' => 'Vendas', 'key' => 'Lancar.Peso.Auto', 'default' => true, 'title' => 'Lançar produtos pesáveis automaticamente'],
];
#    ['section' => 'Sistema', 'key' => 'Logout.Timeout', 'default' => 3, 'title' => 'Minutos de inatividade'],

if (is_post()) {
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
        json(['status' => 'ok']);
    } catch (Exception $e) {
        json($e->getMessage());
    }
}
include template('gerenciar_sistema_opcoes');
