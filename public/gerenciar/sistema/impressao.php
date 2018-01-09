<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php'); // main app file

need_permission(PermissaoNome::ALTERARCONFIGURACOES, isset($_POST));

$tab_impressao = 'active';
$opcoes_impressao = array(
    array('section' => 'Imprimir', 'key' => 'Empresa.CNPJ', 'default' => true, 'title' => vsprintf('Imprimir o %s', array(_p('Titulo', 'CNPJ')))),
    array('section' => 'Imprimir', 'key' => 'Empresa.Endereco', 'default' => true, 'title' => 'Imprimir o endereço'),
    array('section' => 'Imprimir', 'key' => 'Empresa.Telefone_1', 'default' => true, 'title' => 'Imprimir o telefone 1'),
    array('section' => 'Imprimir', 'key' => 'Empresa.Telefone_2', 'default' => true, 'title' => 'Imprimir o telefone 2'),
    array('section' => 'Imprimir', 'key' => 'Garcom.Todos', 'default' => false, 'title' => 'Imprimir todos os garçons'),
    array('section' => 'Imprimir', 'key' => 'Garcom', 'default' => true, 'title' => 'Imprimir garçons no relatório'),
    array('section' => 'Imprimir', 'key' => 'Atendente', 'default' => true, 'title' => 'Imprimir o(a) atendente de caixa'),
    array('section' => 'Imprimir', 'key' => 'Empresa.Slogan', 'default' => true, 'title' => 'Imprimir slogan'),
    array('section' => 'Imprimir', 'key' => 'Permanencia', 'default' => true, 'title' => 'Imprimir permanência'),
    array('section' => 'Imprimir', 'key' => 'Empresa.Logomarca', 'default' => false, 'title' => 'Imprimir logo da empresa'),
    array('section' => 'Imprimir', 'key' => 'Conta.Divisao', 'default' => true, 'title' => 'Imprimir divisão da conta'),
    array('section' => 'Imprimir', 'key' => 'Cozinha.Produto.Codigo', 'default' => false, 'title' => 'Imprimir o código nos serviços'),
    array('section' => 'Imprimir', 'key' => 'Cozinha.Produto.Detalhes', 'default' => false, 'title' => 'Imprimir os detalhes do produto'),
    array('section' => 'Imprimir', 'key' => 'Mesa.Atendente_Dividir', 'default' => false, 'title' => 'Imprimir local e atendente separados'),
    array('section' => 'Imprimir', 'key' => 'Relatorio.Grafico_3D', 'default' => true, 'title' => 'Imprimir gráficos em 3D'),
    array('section' => 'Imprimir', 'key' => 'Vendas.Cancelamentos', 'default' => false, 'title' => 'Imprimir cancelamentos'),
    array('section' => 'Imprimir', 'key' => 'Fechamento.Produtos', 'default' => false, 'title' => 'Imprimir produtos no fechamento'),
    array('section' => 'Imprimir', 'key' => 'Senha.Paineis', 'default' => false, 'title' => 'Imprimir senha para painéis'),
    array('section' => 'Imprimir', 'key' => 'Comanda.Senha', 'default' => false, 'title' => 'Imprimir senha nas comandas'),
    array('section' => 'Imprimir', 'key' => 'Cozinha.Fonte.Gigante', 'default' => false, 'title' => 'Imprimir serviços em letra grande'),
    array('section' => 'Imprimir', 'key' => 'Servico.Detalhado', 'default' => true, 'title' => 'Imprimir serviços detalhadamente'),
    array('section' => 'Imprimir', 'key' => 'Endereco.Destacado', 'default' => true, 'title' => 'Imprimir endereço destacado'),
    array('section' => 'Imprimir', 'key' => 'Cozinha.Local_Destacado', 'default' => true, 'title' => 'Imprimir local destacado'),
    array('section' => 'Imprimir', 'key' => 'Servicos.Pessoas', 'default' => true, 'title' => 'Imprimir quantidade de pessoas nos serviços'),
    array('section' => 'Imprimir', 'key' => 'Vendas.Resumo.Entrega', 'default' => false, 'title' => 'Imprimir resumo de entrega'),
    array('section' => 'Cupom'   , 'key' => 'Pedido.Fechamento', 'default' => true, 'title' => 'Imprimir conta ao fechar pedidos'),
    array('section' => 'Imprimir', 'key' => 'Guia.Pagamento', 'default' => true, 'title' => 'Imprimir guia de pagamento'),
    array('section' => 'Imprimir', 'key' => 'Contas.Comprovantes', 'default' => true, 'title' => 'Imprimir comprovante de contas'),
    array('section' => 'Imprimir', 'key' => 'Caixa.Operacoes', 'default' => true, 'title' => 'Imprimir operações financeiras'),
    array('section' => 'Cupom'   , 'key' => 'Servicos.Perguntar', 'default' => false, 'title' => 'Imprimir serviços sem perguntar'),
    array('section' => 'Imprimir', 'key' => 'Cozinha.Cliente', 'default' => false, 'title' => 'Imprimir cliente nos serviços'),
    array('section' => 'Imprimir', 'key' => 'Cozinha.Pedido.Descricao', 'default' => false, 'title' => 'Imprimir observação do pedido nos serviços'),
    array('section' => 'Imprimir', 'key' => 'Pedido.Descricao', 'default' => false, 'title' => 'Imprimir observações no pedido'),
    array('section' => 'Imprimir', 'key' => 'Pacotes.Agrupados', 'default' => true, 'title' => 'Imprimir pacotes agrupados'),
    array('section' => 'Imprimir', 'key' => 'Caixa.Fechamento', 'default' => true, 'title' => 'Imprimir fechamento de caixa'),
    array('section' => 'Cupom'   , 'key' => 'Perguntar', 'default' => false, 'title' => 'Exibir pegunta de impressão'),
    array('section' => 'Imprimir', 'key' => 'Cozinha.Separar', 'default' => false, 'title' => 'Imprimir linha separadora de serviços'),
);
if ($_POST) {
    try {
        if (!config_values_exists($opcoes_impressao, $_POST['secao'], $_POST['chave'])) {
            throw new Exception('A opção de impressão informada não existe', 1);
        }
        set_boolean_config($_POST['secao'], $_POST['chave'], $_POST['marcado'] == 'Y');
        $__sistema__->salvarOpcoes($__options__);
        try {
            $appsync = new AppSync();
            $appsync->printOptionsChanged();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        json(array('status' => 'ok'));
    } catch (Exception $e) {
        json($e->getMessage());
    }
}

include template('gerenciar_sistema_impressao');
