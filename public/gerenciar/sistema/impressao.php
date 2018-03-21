<?php
require_once(dirname(dirname(__DIR__)) . '/app.php'); // main app file

need_permission(PermissaoNome::ALTERARCONFIGURACOES, isset($_POST));

$tab_impressao = 'active';
$opcoes_aparencia = [
    ['section' => 'Imprimir', 'key' => 'Empresa.CNPJ', 'default' => true, 'title' => vsprintf('Imprimir o %s', [_p('Titulo', 'CNPJ')])],
    ['section' => 'Imprimir', 'key' => 'Empresa.Endereco', 'default' => true, 'title' => 'Imprimir o endereço'],
    ['section' => 'Imprimir', 'key' => 'Empresa.Telefone_1', 'default' => true, 'title' => 'Imprimir o telefone 1'],
    ['section' => 'Imprimir', 'key' => 'Empresa.Telefone_2', 'default' => true, 'title' => 'Imprimir o telefone 2'],
    ['section' => 'Imprimir', 'key' => 'Garcom.Todos', 'default' => false, 'title' => 'Imprimir todos os garçons'],
    ['section' => 'Imprimir', 'key' => 'Garcom', 'default' => true, 'title' => 'Imprimir garçons no relatório'],
    ['section' => 'Imprimir', 'key' => 'Atendente', 'default' => true, 'title' => 'Imprimir o(a) atendente de caixa'],
    ['section' => 'Imprimir', 'key' => 'Empresa.Slogan', 'default' => true, 'title' => 'Imprimir slogan'],
    ['section' => 'Imprimir', 'key' => 'Permanencia', 'default' => true, 'title' => 'Imprimir permanência'],
    ['section' => 'Imprimir', 'key' => 'Empresa.Logomarca', 'default' => false, 'title' => 'Imprimir logo da empresa'],
    ['section' => 'Imprimir', 'key' => 'Conta.Divisao', 'default' => true, 'title' => 'Imprimir divisão da conta'],
    ['section' => 'Imprimir', 'key' => 'Cozinha.Produto.Codigo', 'default' => false, 'title' => 'Imprimir o código nos serviços'],
    ['section' => 'Imprimir', 'key' => 'Cozinha.Produto.Detalhes', 'default' => false, 'title' => 'Imprimir os detalhes do produto'],
    ['section' => 'Imprimir', 'key' => 'Mesa.Atendente_Dividir', 'default' => false, 'title' => 'Imprimir local e atendente separados'],
    ['section' => 'Imprimir', 'key' => 'Relatorio.Grafico_3D', 'default' => true, 'title' => 'Imprimir gráficos em 3D'],
    ['section' => 'Imprimir', 'key' => 'Fechamento.Produtos', 'default' => false, 'title' => 'Imprimir produtos no fechamento'],
    ['section' => 'Imprimir', 'key' => 'Cozinha.Fonte.Gigante', 'default' => false, 'title' => 'Imprimir serviços em letra grande'],
    ['section' => 'Imprimir', 'key' => 'Servico.Detalhado', 'default' => true, 'title' => 'Imprimir serviços detalhadamente'],
    ['section' => 'Imprimir', 'key' => 'Endereco.Destacado', 'default' => true, 'title' => 'Imprimir endereço destacado'],
    ['section' => 'Imprimir', 'key' => 'Cozinha.Local_Destacado', 'default' => true, 'title' => 'Imprimir local destacado'],
    ['section' => 'Imprimir', 'key' => 'Servicos.Pessoas', 'default' => true, 'title' => 'Imprimir quantidade de pessoas nos serviços'],
    ['section' => 'Imprimir', 'key' => 'Cozinha.Cliente', 'default' => false, 'title' => 'Imprimir cliente nos serviços'],
    ['section' => 'Imprimir', 'key' => 'Cozinha.Pedido.Descricao', 'default' => false, 'title' => 'Imprimir observação do pedido nos serviços'],
    ['section' => 'Imprimir', 'key' => 'Pedido.Descricao', 'default' => false, 'title' => 'Imprimir observações no pedido'],
    ['section' => 'Imprimir', 'key' => 'Pacotes.Agrupados', 'default' => true, 'title' => 'Imprimir pacotes agrupados'],
    ['section' => 'Imprimir', 'key' => 'Cozinha.Separar', 'default' => false, 'title' => 'Imprimir linha separadora de serviços'],
    ['section' => 'Imprimir', 'key' => 'Cozinha.Saldo', 'default' => false, 'title' => 'Imprimir saldo restante da comanda nos serviços'],
];
$opcoes_guias = [
    ['section' => 'Imprimir', 'key' => 'Caixa.Fechamento', 'default' => true, 'title' => 'Imprimir fechamento de caixa'],
    ['section' => 'Imprimir', 'key' => 'Contas.Comprovantes', 'default' => true, 'title' => 'Imprimir comprovante de contas'],
    ['section' => 'Imprimir', 'key' => 'Vendas.Cancelamentos', 'default' => false, 'title' => 'Imprimir cancelamentos'],
    ['section' => 'Imprimir', 'key' => 'Caixa.Operacoes', 'default' => true, 'title' => 'Imprimir operações financeiras'],
    ['section' => 'Imprimir', 'key' => 'Guia.Pagamento', 'default' => true, 'title' => 'Imprimir guia de pagamento'],
    ['section' => 'Imprimir', 'key' => 'Senha.Paineis', 'default' => false, 'title' => 'Imprimir senha para painéis'],
    ['section' => 'Imprimir', 'key' => 'Comanda.Senha', 'default' => false, 'title' => 'Imprimir senha nas comandas'],
    ['section' => 'Imprimir', 'key' => 'Vendas.Resumo.Entrega', 'default' => false, 'title' => 'Imprimir resumo de entrega'],
    ['section' => 'Cupom'   , 'key' => 'Pedido.Fechamento', 'default' => true, 'title' => 'Imprimir conta ao fechar pedidos'],
];
$opcoes_comportamento = [
    ['section' => 'Cupom'   , 'key' => 'Perguntar', 'default' => false, 'title' => 'Exibir pegunta de impressão'],
    ['section' => 'Cupom'   , 'key' => 'Servicos.Perguntar', 'default' => false, 'title' => 'Perguntar antes de imprimir serviços'],
];
$opcoes_impressao = array_merge($opcoes_aparencia, $opcoes_guias, $opcoes_comportamento);

if (is_post()) {
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
        json(['status' => 'ok']);
    } catch (Exception $e) {
        json($e->getMessage());
    }
}

include template('gerenciar_sistema_impressao');
