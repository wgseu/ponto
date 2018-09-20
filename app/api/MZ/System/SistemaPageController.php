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
namespace MZ\System;

use MZ\Account\Cliente;

/**
 * Allow application to serve system resources
 */
class SistemaPageController extends \MZ\Core\Controller
{
    public function manage()
    {
        need_manager(is_output('json'));
        if (is_owner()) {
            $controller = new \MZ\Payment\PagamentoPageController($this->getApplication());
            return $controller->dashboard();
        } elseif (logged_provider()->has(Permissao::NOME_PAGAMENTO)) {
            $controller = new \MZ\Sale\PedidoPageController($this->getApplication());
            return $controller->find();
        } else {
            $controller = new \MZ\Provider\PrestadorPageController($this->getApplication());
            return $controller->find();
        }
    }

    public function display()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

        $tab = 'empresa';
        $cliente = $this->getApplication()->getSystem()->getCompany();
        if (!$cliente->exists()) {
            $cliente->setTipo(Cliente::TIPO_JURIDICA);
        }
        $localizacao = \MZ\Location\Localizacao::find(['clienteid' => $this->getApplication()->getSystem()->getCompany()->getID()]);
        $localizacao->setClienteID($cliente->getID());
        if (!$localizacao->exists()) {
            $localizacao->setMostrar('Y');
        }
        $bairro = $localizacao->findBairroID();
        $cidade = $bairro->findCidadeID();
        $estado = $cidade->findEstadoID();
        $_paises = \MZ\Location\Pais::findAll();
        if (!$estado->exists() && count($_paises) > 0) {
            $estado->setPaisID(reset($_paises)->getID());
        }
        $pais_id = $estado->getPaisID();
        $focusctrl = 'nome';
        $_estados = \MZ\Location\Estado::findAll(['paisid' => $pais_id]);

        return $this->view('gerenciar_sistema_index', get_defined_vars());
    }

    public function advanced()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

        $focusctrl = 'mapskey';
        $tab = 'avancado';

        $erro = [];
        $maps_api = get_string_config('Site', 'Maps.API');
        $dropbox_token = get_string_config('Sistema', 'Dropbox.AccessKey');
        if (is_post()) {
            try {
                $maps_api = isset($_POST['mapskey']) ? trim($_POST['mapskey']) : null;
                set_string_config('Site', 'Maps.API', $maps_api);
                $dropbox_token = isset($_POST['dropboxtoken']) ? trim($_POST['dropboxtoken']) : null;
                set_string_config('Sistema', 'Dropbox.AccessKey', $dropbox_token);
                $this->getApplication()->getSystem()->filter($this->getApplication()->getSystem());
                $this->getApplication()->getSystem()->update(['opcoes']);
                \Thunder::success('Opções avançadas atualizadas com sucesso!', true);
                return $this->redirect('/gerenciar/sistema/avancado');
            } catch (\ValidationException $e) {
                $erro = $e->getErrors();
            } catch (\Exception $e) {
                $erro['unknow'] = $e->getMessage();
            }
            foreach ($erro as $key => $value) {
                $focusctrl = $key;
                \Thunder::error($erro[$focusctrl]);
                break;
            }
        }

        return $this->view('gerenciar_sistema_avancado', get_defined_vars());
    }

    public function mail()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

        $tab = 'email';
        $focusctrl = 'destinatario';
        $errors = [];

        $destinatario = get_string_config('Email', 'Remetente');
        $servidor = get_string_config('Email', 'Servidor');
        $porta = get_int_config('Email', 'Porta', 587);
        $encriptacao = get_int_config('Email', 'Criptografia', 2);
        $usuario = get_string_config('Email', 'Usuario');
        if (is_post()) {
            try {
                $destinatario = isset($_POST['destinatario']) ? trim($_POST['destinatario']) : null;
                set_string_config('Email', 'Remetente', $destinatario);
                $servidor = isset($_POST['servidor']) ? trim($_POST['servidor']) : null;
                set_string_config('Email', 'Servidor', $servidor);
                $porta = isset($_POST['porta']) ? intval($_POST['porta']) : null;
                if ($porta < 0 || $porta > 65535) {
                    throw new \Exception('A porta é inválida, informe um número entre 0 e 65535');
                }
                set_int_config('Email', 'Porta', $porta);
                $encriptacao = isset($_POST['encriptacao']) ? intval($_POST['encriptacao']) : null;
                set_int_config('Email', 'Criptografia', $encriptacao);
                $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : null;
                set_string_config('Email', 'Usuario', $usuario);
                $senha = isset($_POST['senha']) ? strval($_POST['senha']) : null;
                if (strlen($senha) > 0) {
                    set_string_config('Email', 'Senha', $senha);
                }
                $this->getApplication()->getSystem()->filter($this->getApplication()->getSystem());
                $this->getApplication()->getSystem()->update(['opcoes']);
                $msg = 'E-mail atualizado com sucesso!';
                if (is_output('json')) {
                    return $this->json()->success([], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/sistema/email');
            } catch (\Exception $e) {
                $sistema->clean($old_sistema);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }

        return $this->view('gerenciar_sistema_email', get_defined_vars());
    }

    public function invoice()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

        $focusctrl = 'fiscal_timeout';
        $tab = 'fiscal';

        $erros = [];
        $fiscal_timeout = get_int_config('Sistema', 'Fiscal.Timeout', 30);
        if (is_post()) {
            try {
                $fiscal_timeout = \MZ\Util\Filter::number(isset($_POST['fiscal_timeout'])?$_POST['fiscal_timeout']:null);
                if (intval($fiscal_timeout) < 2) {
                    throw new \MZ\Exception\ValidationException(
                        ['fiscal_timeout' => 'O tempo limite não pode ser menor que 2 segundos']
                    );
                }
                set_int_config('Sistema', 'Fiscal.Timeout', $fiscal_timeout);
                $this->getApplication()->getSystem()->filter($this->getApplication()->getSystem());
                $this->getApplication()->getSystem()->update(['opcoes']);
                \Thunder::success('Opções fiscais atualizadas com sucesso!', true);
                return $this->redirect('/gerenciar/sistema/fiscal');
            } catch (\Exception $e) {
                $sistema->clean($old_sistema);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }

        return $this->view('gerenciar_sistema_fiscal', get_defined_vars());
    }

    public function printing()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_post() || is_output('json'));

        $tab = 'impressao';
        $opcoes_aparencia = [
            [
                'section' => 'Imprimir',
                'key' => 'Empresa.CNPJ',
                'default' => true,
                'title' => sprintf('Imprimir o %s', _p('Titulo', 'CNPJ'))
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Empresa.Endereco',
                'default' => true,
                'title' => 'Imprimir o endereço'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Empresa.Telefone_1',
                'default' => true,
                'title' => 'Imprimir o telefone 1'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Empresa.Telefone_2',
                'default' => true,
                'title' => 'Imprimir o telefone 2'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Garcom.Todos',
                'default' => false,
                'title' => 'Imprimir todos os garçons'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Garcom',
                'default' => true,
                'title' => 'Imprimir garçons no relatório'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Atendente',
                'default' => true,
                'title' => 'Imprimir o(a) atendente de caixa'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Empresa.Slogan',
                'default' => true,
                'title' => 'Imprimir slogan'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Permanencia',
                'default' => true,
                'title' => 'Imprimir permanência'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Empresa.Logomarca',
                'default' => false,
                'title' => 'Imprimir logo da empresa'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Conta.Divisao',
                'default' => true,
                'title' => 'Imprimir divisão da conta'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Cozinha.Produto.Codigo',
                'default' => false,
                'title' => 'Imprimir o código nos serviços'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Cozinha.Produto.Detalhes',
                'default' => false,
                'title' => 'Imprimir os detalhes do produto'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Mesa.Atendente_Dividir',
                'default' => false,
                'title' => 'Imprimir local e atendente separados'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Relatorio.Grafico_3D',
                'default' => true,
                'title' => 'Imprimir gráficos em 3D'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Fechamento.Produtos',
                'default' => false,
                'title' => 'Imprimir produtos no fechamento'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Cozinha.Fonte.Gigante',
                'default' => false,
                'title' => 'Imprimir serviços em letra grande'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Servico.Detalhado',
                'default' => true,
                'title' => 'Imprimir serviços detalhadamente'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Endereco.Destacado',
                'default' => true,
                'title' => 'Imprimir endereço destacado'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Cozinha.Local_Destacado',
                'default' => true,
                'title' => 'Imprimir local destacado'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Servicos.Pessoas',
                'default' => true,
                'title' => 'Imprimir quantidade de pessoas nos serviços'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Cozinha.Cliente',
                'default' => false,
                'title' => 'Imprimir cliente nos serviços'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Cozinha.Pedido.Descricao',
                'default' => false,
                'title' => 'Imprimir observação do pedido nos serviços'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Pedido.Descricao',
                'default' => false,
                'title' => 'Imprimir observações no pedido'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Pacotes.Agrupados',
                'default' => true,
                'title' => 'Imprimir pacotes agrupados'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Cozinha.Separar',
                'default' => false,
                'title' => 'Imprimir linha separadora de serviços'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Cozinha.Saldo',
                'default' => false,
                'title' => 'Imprimir saldo restante da comanda nos serviços'
            ],
        ];
        $opcoes_guias = [
            [
                'section' => 'Imprimir',
                'key' => 'Caixa.Fechamento',
                'default' => true,
                'title' => 'Imprimir fechamento de caixa'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Contas.Comprovantes',
                'default' => true,
                'title' => 'Imprimir comprovante de contas'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Vendas.Cancelamentos',
                'default' => false,
                'title' => 'Imprimir cancelamentos'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Caixa.Operacoes',
                'default' => true,
                'title' => 'Imprimir operações financeiras'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Guia.Pagamento',
                'default' => true,
                'title' => 'Imprimir guia de pagamento'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Senha.Paineis',
                'default' => false,
                'title' => 'Imprimir senha para painéis'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Comanda.Senha',
                'default' => false,
                'title' => 'Imprimir senha nas comandas'
            ],
            [
                'section' => 'Imprimir',
                'key' => 'Vendas.Resumo.Entrega',
                'default' => false,
                'title' => 'Imprimir resumo de entrega'
            ],
            [
                'section' => 'Cupom',
                'key' => 'Pedido.Fechamento',
                'default' => true,
                'title' => 'Imprimir conta ao fechar pedidos'
            ],
        ];
        $opcoes_comportamento = [
            [
                'section' => 'Cupom',
                'key' => 'Perguntar',
                'default' => false,
                'title' => 'Exibir pegunta de impressão'
            ],
            [
                'section' => 'Cupom',
                'key' => 'Servicos.Perguntar',
                'default' => false,
                'title' => 'Perguntar antes de imprimir serviços'
            ],
        ];
        $opcoes_impressao = array_merge($opcoes_aparencia, $opcoes_guias, $opcoes_comportamento);

        if (is_post()) {
            try {
                $secao = isset($_POST['secao']) ? $_POST['secao'] : null;
                $chave = isset($_POST['chave']) ? $_POST['chave'] : null;
                if (!config_values_exists($opcoes_impressao, $secao, $chave)) {
                    throw new \Exception('A opção de impressão informada não existe', 1);
                }
                $marcado = isset($_POST['marcado']) ? $_POST['marcado'] : null;
                set_boolean_config($secao, $chave, $marcado == 'Y');
                $this->getApplication()->getSystem()->filter($this->getApplication()->getSystem());
                $this->getApplication()->getSystem()->update(['opcoes']);
                return $this->json()->success();
            } catch (\Exception $e) {
                return $this->json()->error($e->getMessage());
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }

        return $this->view('gerenciar_sistema_impressao', get_defined_vars());
    }

    public function layout()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

        $focusctrl = 'bemvindo';
        $base_url = 'header';
        $tab = 'layout';

        $erro = [];
        $images_info = [
            'header' => [
                'section' => 'Image.Header',
                'field' => 'header_url',
                'image' => 'image_header',
            ],
            'login' => [
                'section' => 'Image.Login',
                'field' => 'login_url',
                'image' => 'image_login',
            ],
            'cadastrar' => [
                'section' => 'Image.Cadastrar',
                'field' => 'cadastrar_url',
                'image' => 'image_cadastrar',
            ],
            'produtos' => [
                'section' => 'Image.Produtos',
                'field' => 'produtos_url',
                'image' => 'image_produtos',
            ],
            'sobre' => [
                'section' => 'Image.Sobre',
                'field' => 'sobre_url',
                'image' => 'image_sobre',
            ],
            'privacidade' => [
                'section' => 'Image.Privacidade',
                'field' => 'privacidade_url',
                'image' => 'image_privacidade',
            ],
            'termos' => [
                'section' => 'Image.Termos',
                'field' => 'termos_url',
                'image' => 'image_termos',
            ],
            'contato' => [
                'section' => 'Image.Contato',
                'field' => 'contato_url',
                'image' => 'image_contato',
            ],
        ];
        foreach ($images_info as $key => &$value) {
            $value['url'] = get_string_config('Site', $value['section']);
        }
        $text_bemvindo = get_string_config('Site', 'Text.BemVindo', 'Bem-vindo ao nosso restaurante!');
        $text_chamada = get_string_config('Site', 'Text.Chamada', 'Conheça nosso cardápio!');
        if (is_post()) {
            foreach ($images_info as $key => &$value) {
                $value['save'] = $value['url'];
            }
            try {
                foreach ($images_info as $key => &$value) {
                    $old_url = isset($_POST[$value['field']]) ? trim($_POST[$value['field']]) : null;
                    $value['save'] = upload_image($value['image'], $base_url);
                    if (!is_null($value['save'])) {
                        set_string_config('Site', $value['section'], $value['save']);
                    } elseif ($old_url == '') {
                        set_string_config('Site', $value['section'], null);
                    } else {
                        $value['save'] = $value['url'];
                    }
                }
                $text_bemvindo = isset($_POST['bemvindo']) ? trim($_POST['bemvindo']) : null;
                set_string_config('Site', 'Text.BemVindo', $text_bemvindo);
                $text_chamada = isset($_POST['chamada']) ? trim($_POST['chamada']) : null;
                set_string_config('Site', 'Text.Chamada', $text_chamada);
                $this->getApplication()->getSystem()->filter($this->getApplication()->getSystem());
                $this->getApplication()->getSystem()->update(['opcoes']);
                foreach ($images_info as $key => $value) {
                    // exclui a imagem antiga, pois uma nova foi informada
                    if (!is_null($value['url']) &&
                        $value['save'] != $value['url']
                    ) {
                        unlink($this->getApplication()->getPath('public') . get_image_url($value['url'], $base_url));
                    }
                }
                $msg = 'Layout atualizado com sucesso!';
                if (is_output('json')) {
                    return $this->json()->success(['item' => $sistema->publish()], $msg);
                }
                \Thunder::success($msg, true);
                return $this->redirect('/gerenciar/sistema/layout');
            } catch (\Exception $e) {
                $sistema->clean($old_sistema);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                foreach ($images_info as $key => $value) {
                    // remove imagem enviada
                    if (!is_null($value['save']) &&
                        $value['url'] != $value['save']
                    ) {
                        unlink($this->getApplication()->getPath('public') . get_image_url($value['save'], $base_url));
                    }
                }
                if (is_output('json')) {
                    return $this->json()->error($e->getMessage(), null, $errors);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }

        return $this->view('gerenciar_sistema_layout', get_defined_vars());
    }

    public function options()
    {
        need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_post() || is_output('json'));

        $tab = 'opcoes';
        $opcoes_comportamento = [
            [
                'section' => 'Sistema',
                'key' => 'Auto.Logout',
                'default' => false,
                'title' => 'Fazer logout automaticamente após inatividade'
            ],
            [
                'section' => 'Comandas',
                'key' => 'PrePaga',
                'default' => false,
                'title' => 'Comanda pré-paga'
            ],
            [
                'section' => 'Vendas',
                'key' => 'Exibir.Cancelados',
                'default' => false,
                'title' => 'Mostrar produtos cancelados nas vendas'
            ],
            [
                'section' => 'Vendas',
                'key' => 'Lembrar.Atendente',
                'default' => false,
                'title' => 'Lembrar o último atendente nas vendas'
            ],
            [
                'section' => 'Estoque',
                'key' => 'Estoque.Negativo',
                'default' => false,
                'title' => 'Permitir estoque negativo'
            ],
            [
                'section' => 'Vendas',
                'key' => 'Tela.Cheia',
                'default' => true,
                'title' => 'Exibir a tela de venda rápida em tela cheia'
            ],
            [
                'section' => 'Sistema',
                'key' => 'Backup.Auto',
                'default' => true,
                'title' => 'Realizar backup automaticamente'
            ],
            [
                'section' => 'Vendas',
                'key' => 'Balcao.Comissao',
                'default' => false,
                'title' => 'Comissão na venda balcão'],
            [
                'section' => 'Sistema',
                'key' => 'Tablet.Logout',
                'default' => false,
                'title' => 'Fazer logout no tablet após lançar pedido'
            ],
            [
                'section' => 'Vendas',
                'key' => 'Mesas.Juntar',
                'default' => true,
                'title' => 'Reservar mesas ao juntar'
            ],
            [
                'section' => 'Vendas',
                'key' => 'Mesas.Redirecionar',
                'default' => false,
                'title' => 'Redirecionar para a mesa principal'
            ],
            [
                'section' => 'Vendas',
                'key' => 'Comanda.Observacao',
                'default' => false,
                'title' => 'Observação como nome de comanda'
            ],
            [
                'section' => 'Vendas',
                'key' => 'Quantidade.Perguntar',
                'default' => true,
                'title' => 'Confirmar ao lançar quantidades elevadas'
            ],
            [
                'section' => 'Sistema',
                'key' => 'Fiscal.Mostrar',
                'default' => false,
                'title' => 'Mostrar campos fiscais e tributários'
            ],
            [
                'section' => 'Vendas',
                'key' => 'Lancar.Peso.Auto',
                'default' => true,
                'title' => 'Lançar produtos pesáveis automaticamente'
            ],
        ];
        #    ['section' => 'Sistema', 'key' => 'Logout.Timeout', 'default' => 3, 'title' => 'Minutos de inatividade'],

        if (is_post()) {
            try {
                $secao = isset($_POST['secao']) ? $_POST['secao'] : null;
                $chave = isset($_POST['chave']) ? $_POST['chave'] : null;
                if (!config_values_exists($opcoes_comportamento, $secao, $chave)) {
                    throw new \Exception('A opção de comportamento informada não existe', 1);
                }
                $marcado = isset($_POST['marcado']) ? $_POST['marcado'] : null;
                set_boolean_config($secao, $chave, $marcado == 'Y');
                $this->getApplication()->getSystem()->filter($this->getApplication()->getSystem());
                $this->getApplication()->getSystem()->update(['opcoes']);
                return $this->json()->success();
            } catch (\Exception $e) {
                return $this->json()->error($e->getMessage());
            }
        } elseif (is_output('json')) {
            return $this->json()->error('Nenhum dado foi enviado');
        }

        return $this->view('gerenciar_sistema_opcoes', get_defined_vars());
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'sistema_manage',
                'path' => '/gerenciar/',
                'method' => 'GET',
                'controller' => 'manage',
            ],
            [
                'name' => 'sistema_display',
                'path' => '/gerenciar/sistema/',
                'method' => 'GET',
                'controller' => 'display',
            ],
            [
                'name' => 'sistema_advanced',
                'path' => '/gerenciar/sistema/avancado',
                'method' => ['GET', 'POST'],
                'controller' => 'advanced',
            ],
            [
                'name' => 'sistema_mail',
                'path' => '/gerenciar/sistema/email',
                'method' => ['GET', 'POST'],
                'controller' => 'mail',
            ],
            [
                'name' => 'sistema_invoice',
                'path' => '/gerenciar/sistema/fiscal',
                'method' => ['GET', 'POST'],
                'controller' => 'invoice',
            ],
            [
                'name' => 'sistema_printing',
                'path' => '/gerenciar/sistema/impressao',
                'method' => ['GET', 'POST'],
                'controller' => 'printing',
            ],
            [
                'name' => 'sistema_layout',
                'path' => '/gerenciar/sistema/layout',
                'method' => ['GET', 'POST'],
                'controller' => 'layout',
            ],
            [
                'name' => 'sistema_options',
                'path' => '/gerenciar/sistema/opcoes',
                'method' => ['GET', 'POST'],
                'controller' => 'options',
            ],
        ];
    }
}
