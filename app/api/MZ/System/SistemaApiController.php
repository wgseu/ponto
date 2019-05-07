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

use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Classe que informa detalhes da empresa, parceiro e opções do sistema
 * como a versão do banco de dados e a licença de uso
 */
class SistemaApiController extends \MZ\Core\ApiController
{
    /**
     * Find all Sistemas
     * @Get("/api/sistemas", name="api_sistema_find")
     */
    public function find()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $limit = max(1, min(100, $this->getRequest()->query->getInt('limit', 10)));
        $page = max(1, $this->getRequest()->query->getInt('page', 1));
        $condition = Filter::query($this->getRequest()->query->all());
        $order = $this->getRequest()->query->get('order', '');
        $count = Sistema::count($condition);
        $pager = new \Pager($count, $limit, $page);
        $sistemas = Sistema::findAll($condition, $order, $limit, $pager->offset);
        $itens = [];
        foreach ($sistemas as $sistema) {
            $itens[] = $sistema->publish(app()->auth->provider);
        }
        return $this->getResponse()->success(['items' => $itens, 'pages' => $pager->pageCount]);
    }

    /**
     * Create a new Sistema
     * @Post("/api/sistemas", name="api_sistema_add")
     */
    public function add()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $sistema = new Sistema($this->getData());
        $sistema->filter(new Sistema(), app()->auth->provider, $localized);
        $sistema->insert();
        return $this->getResponse()->success(['item' => $sistema->publish(app()->auth->provider)]);
    }

    /**
     * Modify parts of an existing Sistema
     * @Patch("/api/sistemas/{id}", name="api_sistema_update", params={ "id": "\d+" })
     *
     * @param int $id Sistema id
     */
    public function modify($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $old_sistema = Sistema::findOrFail(['id' => $id]);
        $localized = $this->getRequest()->query->getBoolean('localized', false);
        $data = $this->getData($old_sistema->toArray());
        $sistema = new Sistema($data);
        $sistema->filter($old_sistema, app()->auth->provider, $localized);
        $sistema->update();
        $old_sistema->clean($sistema);
        return $this->getResponse()->success(['item' => $sistema->publish(app()->auth->provider)]);
    }

    /**
     * Delete existing Sistema
     * @Delete("/api/sistemas/{id}", name="api_sistema_delete", params={ "id": "\d+" })
     *
     * @param int $id Sistema id to delete
     */
    public function delete($id)
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $sistema = Sistema::findOrFail(['id' => $id]);
        $sistema->delete();
        $sistema->clean(new Sistema());
        return $this->getResponse()->success([]);
    }

    public function advanced()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $erro = [];
        $maps_api = get_string_config('Site', 'Maps.API');
        $dropbox_token = get_string_config('Sistema', 'Dropbox.AccessKey');
        if ($this->getRequest()->isMethod('POST')) {
            try {
                $maps_api = trim($this->getRequest()->request->get('mapskey'));
                set_string_config('Site', 'Maps.API', $maps_api);
                $dropbox_token = trim($this->getRequest()->request->get('dropboxtoken'));
                set_string_config('Sistema', 'Dropbox.AccessKey', $dropbox_token);
                app()->getSystem()->getBusiness()->filter(
                    app()->getSystem()->getBusiness(),
                    app()->auth->provider
                );
                app()->getSystem()->getBusiness()->update(['opcoes']);
                return $this->getResponse()->success([]);
            } catch (\ValidationException $e) {
                return $this->getResponse()->error($e->getMessage());
            }
        }
    }

    public function mail()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $destinatario = get_string_config('Email', 'Remetente');
        $servidor = get_string_config('Email', 'Servidor');
        $porta = get_int_config('Email', 'Porta', 587);
        $encriptacao = get_int_config('Email', 'Criptografia', 2);
        $usuario = get_string_config('Email', 'Usuario');
        if ($this->getRequest()->isMethod('POST')) {
            try {
                $destinatario = trim($this->getRequest()->request->get('destinatario'));
                set_string_config('Email', 'Remetente', $destinatario);
                $servidor = trim($this->getRequest()->request->get('servidor'));
                set_string_config('Email', 'Servidor', $servidor);
                $porta = trim($this->getRequest()->request->getInt('porta', null));
                if ($porta < 0 || $porta > 65535) {
                    throw new \Exception('A porta é inválida, informe um número entre 0 e 65535');
                }
                set_int_config('Email', 'Porta', $porta);
                $encriptacao = trim($this->getRequest()->request->getInt('encriptacao', null));
                set_int_config('Email', 'Criptografia', $encriptacao);
                $usuario = trim($this->getRequest()->request->get('usuario'));
                set_string_config('Email', 'Usuario', $usuario);
                $senha = $this->getRequest()->request->get('senha');
                if (strlen($senha) > 0) {
                    set_string_config('Email', 'Senha', $senha);
                }
                app()->getSystem()->getBusiness()->filter(
                    app()->getSystem()->getBusiness(),
                    app()->auth->provider
                );
                app()->getSystem()->getBusiness()->update(['opcoes']);
                return $this->getResponse()->success([]);
            } catch (\Exception $e) {
                $sistema->clean($old_sistema);
                return $this->getResponse()->error($e->getMessage());
            }
        }
    }

    public function invoice()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
        $focusctrl = 'fiscal_timeout';
        $tab = 'fiscal';
        $erros = [];
        $fiscal_timeout = get_int_config('Sistema', 'Fiscal.Timeout', 30);
        if ($this->getRequest()->isMethod('POST')) {
            try {
                $fiscal_timeout = \MZ\Util\Filter::number($this->getRequest()->request->get('fiscal_timeout'));
                if (intval($fiscal_timeout) < 2) {
                    throw new \MZ\Exception\ValidationException(
                        ['fiscal_timeout' => 'O tempo limite não pode ser menor que 2 segundos']
                    );
                }
                set_int_config('Sistema', 'Fiscal.Timeout', $fiscal_timeout);
                app()->getSystem()->getBusiness()->filter(
                    app()->getSystem()->getBusiness(),
                    app()->auth->provider
                );
                app()->getSystem()->getBusiness()->update(['opcoes']);
            } catch (\Exception $e) {
                $sistema->clean($old_sistema);
                return $this->getResponse()->error($e->getMessage());
            }
        }
    }

    public function printing()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
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

        if ($this->getRequest()->isMethod('POST')) {
            try {
                $secao = $this->getRequest()->request->get('secao');
                $chave = $this->getRequest()->request->get('chave');
                if (!config_values_exists($opcoes_impressao, $secao, $chave)) {
                    throw new \Exception('A opção de impressão informada não existe', 1);
                }
                $marcado = $this->getRequest()->request->get('marcado');
                set_boolean_config($secao, $chave, $marcado == 'Y');
                app()->getSystem()->getBusiness()->filter(
                    app()->getSystem()->getBusiness(),
                    app()->auth->provider
                );
                app()->getSystem()->getBusiness()->update(['opcoes']);
                return $this->getResponse()->success();
            } catch (\Exception $e) {
                return $this->getResponse()->error($e->getMessage());
            }
        }
    }

    public function layout()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
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
        if ($this->getRequest()->isMethod('POST')) {
            foreach ($images_info as $key => &$value) {
                $value['save'] = $value['url'];
            }
            try {
                foreach ($images_info as $key => &$value) {
                    $old_url = $this->getRequest()->request->get($value['field']);
                    $value['save'] = upload_image($value['image'], $base_url);
                    if (!is_null($value['save'])) {
                        set_string_config('Site', $value['section'], $value['save']);
                    } elseif ($old_url == '') {
                        set_string_config('Site', $value['section'], null);
                    } else {
                        $value['save'] = $value['url'];
                    }
                }
                $text_bemvindo = trim($this->getRequest()->request->get('bemvindo'));
                set_string_config('Site', 'Text.BemVindo', $text_bemvindo);
                $text_chamada = trim($this->getRequest()->request->get('chamada'));
                set_string_config('Site', 'Text.Chamada', $text_chamada);
                app()->getSystem()->getBusiness()->filter(
                    app()->getSystem()->getBusiness(),
                    app()->auth->provider
                );
                app()->getSystem()->getBusiness()->update(['opcoes']);
                foreach ($images_info as $key => $value) {
                    // exclui a imagem antiga, pois uma nova foi informada
                    if (!is_null($value['url']) &&
                        $value['save'] != $value['url']
                    ) {
                        unlink(app()->getPath('public') . get_image_url($value['url'], $base_url));
                    }
                }
                return $this->getResponse()->success(['item' => $sistema->publish(app()->auth->provider)]);
            } catch (\Exception $e) {
                $sistema->clean($old_sistema);
                foreach ($images_info as $key => $value) {
                    // remove imagem enviada
                    if (!is_null($value['save']) &&
                        $value['url'] != $value['save']
                    ) {
                        unlink(app()->getPath('public') . get_image_url($value['save'], $base_url));
                    }
                    return $this->getResponse()->error($e->getMessage());
                }
            }
        }
    }

    public function options()
    {
        $this->needPermission([Permissao::NOME_ALTERARCONFIGURACOES]);
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

        if ($this->getRequest()->isMethod('POST')) {
            try {
                $secao = $this->getRequest()->request->get('secao');
                $chave = $this->getRequest()->request->get('chave');
                if (!config_values_exists($opcoes_comportamento, $secao, $chave)) {
                    throw new \Exception('A opção de comportamento informada não existe', 1);
                }
                $marcado = $this->getRequest()->request->get('marcado');
                set_boolean_config($secao, $chave, $marcado == 'Y');
                app()->getSystem()->getBusiness()->filter(
                    app()->getSystem()->getBusiness(),
                    app()->auth->provider
                );
                app()->getSystem()->getBusiness()->update(['opcoes']);
                return $this->getResponse()->success();
            } catch (\Exception $e) {
                return $this->getResponse()->error($e->getMessage());
            }
        }
    }

}