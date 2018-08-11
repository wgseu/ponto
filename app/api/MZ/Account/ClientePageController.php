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
namespace MZ\Account;

use MZ\Database\DB;
use MZ\System\Permissao;
use MZ\System\Synchronizer;
use MZ\Employee\Funcionario;
use MZ\Location\Localizacao;
use MZ\Util\Filter;
use MZ\Util\Mask;

/**
 * Allow application to serve system resources
 */
class ClientePageController extends \MZ\Core\Controller
{
    public function register()
    {
        if (is_login()) {
            $msg = 'Você já está cadastrado e autenticado!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::information($msg, true);
            redirect('/');
        }
        $cliente = new Cliente();
        $errors = [];
        $focusctrl = 'nome';
        $old_cliente = $cliente;
        $aceitar = null;
        if (is_post()) {
            $cliente = new Cliente($_POST);
            $aceitar = isset($_POST['aceitar']) ? $_POST['aceitar'] : null;
            try {
                if ($aceitar != 'true') {
                    throw new \MZ\Exception\ValidationException(
                        ['aceitar' => 'Os termos não foram aceitos']
                    );
                }
                $senha = isset($_POST['confirmarsenha']) ? $_POST['confirmarsenha'] : '';
                $cliente->passwordMatch($senha);
                $cliente->filter($old_cliente);
                $cliente->insert();
                $old_cliente->clean($cliente);
                if (is_output('json')) {
                    json('item', $cliente->publish());
                }
                $this->getApplication()->getAuthentication()->login($cliente);
                redirect(get_redirect_page());
            } catch (\Exception $e) {
                $cliente->clean($old_cliente);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    json($e->getMessage(), null, ['errors' => $errors]);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower(Cliente::GENERO_MASCULINO);
                }
            }
        } elseif (is_output('json')) {
            json('Nenhum dado foi enviado');
        }
        $response = $this->getApplication()->getResponse();
        $response->setTitle('Registrar');
        $response->getEngine()->cliente = $cliente;
        $response->getEngine()->aceitar = $aceitar;
        $response->getEngine()->focusctrl = $focusctrl;
        return $response->output('conta_cadastrar');
    }

    public function logout()
    {
        $this->getApplication()->getAuthentication()->logout();
        redirect('/conta/entrar');
    }

    public function login()
    {
        if (is_login()) {
            $url = (is_post() && isset($_POST['redirect'])) ? strval($_POST['redirect']) : null;
            redirect($url);
        }
        if (is_post()) {
            $usuario = isset($_POST['usuario']) ? strval($_POST['usuario']) : null;
            $senha = isset($_POST['senha']) ? strval($_POST['senha']) : null;
            $cliente = Cliente::findByLoginSenha($usuario, $senha);
            if ($cliente->exists()) {
                $this->getApplication()->getAuthentication()->login($cliente);
                $url = isset($_POST['redirect']) ? strval($_POST['redirect']) : get_redirect_page();
                redirect($url);
            }
            $msg = 'Usuário ou senha incorretos!';
            \Thunder::error($msg);
        }
        $response = $this->getApplication()->getResponse();
        $response->setTitle('Entrar');
        return $response->output('conta_entrar');
    }

    public function edit()
    {
        need_login(is_output('json'));
        $cliente = logged_user();
        
        $tab = 'dados';
        $gerenciando = false;
        $cadastrar_cliente = false;
        $aceitar = 'true';
        
        $focusctrl = 'nome';
        $errors = [];
        $old_cliente = $cliente;
        if (is_post()) {
            $cliente = new Cliente($_POST);
            try {
                // não deixa o usuário alterar os dados abaixo
                $cliente->setEmail($old_cliente->getEmail());
                $cliente->setTipo($old_cliente->getTipo());
                $cliente->setAcionistaID($old_cliente->getAcionistaID());
                $cliente->setSlogan($old_cliente->getSlogan());
        
                $senha = isset($_POST['confirmarsenha']) ? $_POST['confirmarsenha'] : '';
                $cliente->passwordMatch($senha);
        
                $cliente->filter($old_cliente);
                $cliente->update();
                $old_cliente->clean($cliente);
                $msg = 'Conta atualizada com sucesso!';
                if (is_output('json')) {
                    json(null, ['item' => $cliente->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/conta/editar');
            } catch (\Exception $e) {
                $cliente->clean($old_cliente);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    json($e->getMessage(), null, ['errors' => $errors]);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower($cliente->getGenero());
                }
            }
        } elseif (is_output('json')) {
            json('Nenhum dado foi enviado');
        }
        $response = $this->getApplication()->getResponse();
        $response->setTitle('Editar Conta');
        $response->getEngine()->old_cliente = $old_cliente;
        $response->getEngine()->cliente = $cliente;
        $response->getEngine()->aceitar = $aceitar;
        $response->getEngine()->focusctrl = $focusctrl;
        $response->getEngine()->cadastrar_cliente = $cadastrar_cliente;
        $response->getEngine()->gerenciando = $gerenciando;
        $response->getEngine()->tab = $tab;
        return $response->output('conta_editar');
    }

    public function find()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROCLIENTES, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        $genero = isset($condition['genero']) ? $condition['genero'] : null;
        if ($genero == 'Empresa') {
            $condition['tipo'] = Cliente::TIPO_JURIDICA;
            unset($condition['genero']);
        }
        $cliente = new Cliente($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = Cliente::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $clientes = Cliente::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($clientes as $_cliente) {
                $items[] = $_cliente->publish();
            }
            json(['status' => 'ok', 'items' => $items]);
        }

        $tipos = Cliente::getGeneroOptions();
        $tipos = ['Empresa' => 'Empresa'] + $tipos;

        return $app->getResponse()->output('gerenciar_cliente_index');
    }

    public function add()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROCLIENTES, is_output('json'));
        $focusctrl = 'tipo';
        $errors = [];
        $cliente = new Cliente();
        $old_cliente = $cliente;
        if (is_post()) {
            $cliente = new Cliente($_POST);
            try {
                DB::beginTransaction();
                if (isset($_GET['sistema']) &&
                    intval($_GET['sistema']) == 1 &&
                    $cliente->getTipo() != Cliente::TIPO_JURIDICA
                ) {
                    throw new \MZ\Exception\ValidationException(['tipo' => 'O tipo da empresa deve ser jurídica']);
                }
                if (isset($_GET['sistema']) &&
                    intval($_GET['sistema']) == 1 &&
                    !is_null($app->getSystem()->getEmpresaID())
                ) {
                    throw new \Exception(
                        'Você deve alterar a empresa do sistema em vez de cadastrar uma nova'
                    );
                }
                $cliente->filter($old_cliente);
                $cliente->insert();
                $old_cliente->clean($cliente);
                if (isset($_GET['sistema']) && intval($_GET['sistema']) == 1) {
                    $app->getSystem()->setEmpresaID($cliente->getID());
                    $app->getSystem()->update();

                    try {
                        $sync = new Synchronizer();
                        $sync->systemOptionsChanged();
                        $sync->enterpriseChanged();
                    } catch (\Exception $e) {
                        \Log::error($e->getMessage());
                    }
                }
                DB::commit();
                $msg = sprintf(
                    'Cliente "%s" cadastrado com sucesso!',
                    $cliente->getNomeCompleto()
                );
                if (is_output('json')) {
                    json(null, ['item' => $cliente->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/cliente/');
            } catch (\Exception $e) {
                DB::rollBack();
                $cliente->clean($old_cliente);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    json($e->getMessage(), null, ['errors' => $errors]);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower(Cliente::GENERO_MASCULINO);
                }
            }
        } elseif (is_output('json')) {
            json('Nenhum dado foi enviado');
        }
        return $app->getResponse()->output('gerenciar_cliente_cadastrar');
    }

    public function update()
    {
        need_manager(is_output('json'));

        need_manager(is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $cliente = Cliente::findByID($id);
        if (!$cliente->exists()) {
            $msg = 'O cliente não foi informado ou não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/cliente/');
        }
        if ($cliente->getID() != logged_user()->getID()) {
            need_permission(Permissao::NOME_CADASTROCLIENTES, is_output('json'));
        }
        if ($cliente->getID() == $app->getSystem()->getCompany()->getID() &&
            !logged_employee()->has(Permissao::NOME_ALTERARCONFIGURACOES)
        ) {
            $msg = 'Você não tem permissão para alterar essa empresa!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/cliente/');
        }
        $funcionario = Funcionario::findByClienteID($cliente->getID());
        if ($funcionario->exists() &&
            (
                (!logged_employee()->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
                    logged_employee()->getID() != $funcionario->getID()
                ) ||
                ($funcionario->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
                    logged_employee()->getID() != $funcionario->getID() &&
                    !is_owner()
                )
            )
        ) {
            $msg = 'Você não tem permissão para alterar as informações desse cliente!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/cliente/');
        }
        $focusctrl = 'nome';
        $errors = [];
        $old_cliente = $cliente;
        if (is_post()) {
            $cliente = new Cliente($_POST);
            try {
                if ($cliente->getID() == $app->getSystem()->getCompany()->getID() &&
                    $cliente->getTipo() != Cliente::TIPO_JURIDICA
                ) {
                    throw new \MZ\Exception\ValidationException(['tipo' => 'O tipo da empresa deve ser jurídica']);
                }
                $senha = isset($_POST['confirmarsenha']) ? $_POST['confirmarsenha'] : '';
                $cliente->passwordMatch($senha);
                $cliente->filter($old_cliente);
                $cliente->update();
                $old_cliente->clean($cliente);
                try {
                    if ($cliente->getID() == $app->getSystem()->getCompany()->getID()) {
                        $appsync = new \MZ\System\Synchronizer();
                        $appsync->enterpriseChanged();
                    }
                } catch (\Exception $e) {
                    \Log::error($e->getMessage());
                }
                $msg = sprintf(
                    'Cliente "%s" atualizado com sucesso!',
                    $cliente->getNomeCompleto()
                );
                if (is_output('json')) {
                    json(null, ['item' => $cliente->publish(), 'msg' => $msg]);
                }
                \Thunder::success($msg, true);
                redirect('/gerenciar/cliente/');
            } catch (\Exception $e) {
                $cliente->clean($old_cliente);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if (is_output('json')) {
                    json($e->getMessage(), null, ['errors' => $errors]);
                }
                \Thunder::error($e->getMessage());
                foreach ($errors as $key => $value) {
                    $focusctrl = $key;
                    break;
                }
                if ($focusctrl == 'genero') {
                    $focusctrl = $focusctrl . '-' . strtolower($cliente->getGenero());
                }
            }
        } elseif (is_output('json')) {
            json('Nenhum dado foi enviado');
        }
        return $app->getResponse()->output('gerenciar_cliente_editar');
    }

    public function delete()
    {
        need_manager(is_output('json'));

        need_permission(Permissao::NOME_CADASTROCLIENTES, is_output('json'));
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $cliente = Cliente::findByID($id);
        if (!$cliente->exists()) {
            $msg = 'O cliente não foi informado ou não existe!';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/cliente/');
        }
        if ($cliente->getID() == $app->getSystem()->getCompany()->getID()) {
            $msg = 'Essa empresa não pode ser excluída';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/cliente/');
        }
        $funcionario = Funcionario::findByClienteID($cliente->getID());
        if ($funcionario->exists() &&
            (
                (
                    !logged_employee()->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
                    logged_employee()->getID() != $funcionario->getID()
                ) ||
                (
                    $funcionario->has(Permissao::NOME_CADASTROFUNCIONARIOS) &&
                    logged_employee()->getID() != $funcionario->getID() &&
                    !is_owner()
                )
            )
        ) {
            $msg = 'Você não tem permissão para excluir esse cliente';
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::warning($msg);
            redirect('/gerenciar/cliente/');
        }
        try {
            $cliente->delete();
            $cliente->clean(new Cliente());
            $msg = sprintf('Cliente "%s" excluído com sucesso!', $cliente->getNomeCompleto());
            if (is_output('json')) {
                json('msg', $msg);
            }
            \Thunder::success($msg, true);
        } catch (\Exception $e) {
            $msg = sprintf(
                'Não foi possível excluir o cliente "%s"',
                $cliente->getNome()
            );
            if (is_output('json')) {
                json($msg);
            }
            \Thunder::error($msg);
        }
        redirect('/gerenciar/cliente/');
    }

    public function export()
    {
        need_manager(is_output('json'));
        need_permission(Permissao::NOME_RELATORIOCLIENTES, is_output('json'));
        set_time_limit(0);
        try {
            $formato = isset($_GET['formato']) ? $_GET['formato'] : null;
            $condition = Filter::query($_GET);
            unset($condition['ordem']);
            unset($condition['formato']);
            $cliente = new Cliente($condition);
            $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
            $clientes = Cliente::findAll($condition, $order);
            // Coluna dos dados
            $columns = [
                'Nome/Fantasia',
                'Telefone',
                'Celular',
                'E-mail',
                'Aniversário/Fundação',
                sprintf('%s/%s', _p('Titulo', 'CPF'), _p('Titulo', 'CNPJ')),
                'RG/IE',
                'Gênero',
                'Apelido',
                _p('Titulo', 'CEP'),
                'Bairro',
                'Logradouro',
                'Numero',
                'Tipo',
                'Condomínio',
                'Bloco',
                'Apartamento',
                'Complemento',
                'Referência',
                'Cidade',
                'Estado',
                'Ativo'
            ];
            $data = [$columns];
            $column = 'B';
            $last = chr(ord($column) + count($columns) - 1);
            $line = 4;
            $i = $line;
            foreach ($clientes as $value) {
                $i++;
                $localizacao = Localizacao::find(['clienteid' => $value->getID()]);
                $bairro = $localizacao->findBairroID();
                $cidade = $bairro->findCidadeID();
                $estado = $cidade->findEstadoID();
        
                $row = [];
                $row[] = $value->getNomeCompleto();
                $row[] = Mask::phone($value->getFone(1));
                $row[] = Mask::phone($value->getFone(2));
                $row[] = $value->getEmail();
                if (is_null($value->getDataAniversario())) {
                    $row[] = null;
                } elseif ($value->getTipo() == Cliente::TIPO_FISICA) {
                    $row[] = human_date($value->getDataAniversario());
                } else {
                    $row[] = Mask::date($value->getDataAniversario());
                }
                if ($value->getTipo() == Cliente::TIPO_FISICA) {
                    $row[] = Mask::cpf($value->getCPF());
                } else {
                    $row[] = Mask::cnpj($value->getCPF());
                }
                $row[] = $value->getRG();
                if ($value->getTipo() == Cliente::TIPO_FISICA) {
                    $row[] = Cliente::getGeneroOptions($value->getGenero());
                } else {
                    $row[] = 'Empresa';
                }
                $row[] = $localizacao->getApelido();
                $row[] = Mask::cep($localizacao->getCEP());
                $row[] = $bairro->getNome();
                $row[] = $localizacao->getLogradouro();
                $row[] = $localizacao->getNumero();
                $row[] = Localizacao::getTipoOptions($localizacao->getTipo());
                $row[] = $localizacao->getCondominio();
                $row[] = $localizacao->getBloco();
                $row[] = $localizacao->getApartamento();
                $row[] = $localizacao->getComplemento();
                $row[] = $localizacao->getReferencia();
                $row[] = $cidade->getNome();
                $row[] = $estado->getNome();
                $row[] = $localizacao->isMostrar() ? 'Sim' : 'Não';
                $data[] = $row;
            }
            // footer
            $row = [];
            $row[] = count($clientes) . ' Clientes';
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $row[] = null;
            $data[] = $row;
            $j = $i + 1;
        
            $title = 'Relatório de Clientes';
            // Create new PHPExcel object
            $objPHPExcel = new \PHPExcel();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator('GrandChef')
                                         ->setTitle($title);
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Clientes');
            // Title
            $objPHPExcel->getActiveSheet()->mergeCells("B2:{$last}2");
            $objPHPExcel->getActiveSheet()->setCellValue($column . ($line - 2), $title);
            // Merge header cells
            $objPHPExcel->getActiveSheet()->mergeCells("B3:I3");
            $objPHPExcel->getActiveSheet()->setCellValue($column . ($line - 1), 'Cliente');
            $objPHPExcel->getActiveSheet()->mergeCells("J3:{$last}3");
            $objPHPExcel->getActiveSheet()->setCellValue('J' . ($line - 1), 'Localização');
            // Merge footer cells
            $objPHPExcel->getActiveSheet()->mergeCells("C{$j}:{$last}{$j}");
            $objPHPExcel->getActiveSheet()->fromArray($data, null, $column . $line);
            // Format styles
            // Format Title
            $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()
                ->setName('Tahoma')->setBold(true)->setSize(16);
            // Align cells
            $objPHPExcel->getActiveSheet()->getStyle("B2:{$last}4")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("C5:L{$j}")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("N5:R{$j}")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("U5:{$last}{$j}")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("B{$j}")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Format header
            $objPHPExcel->getActiveSheet()->getStyle("B3:{$last}4")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("B3:{$last}4")->getBorders()->applyFromArray([
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_MEDIUM]
            ]);
            // Format footer
            $objPHPExcel->getActiveSheet()->getStyle("B{$j}:{$last}{$j}")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("B{$j}:{$last}{$j}")->getBorders()->applyFromArray([
                'allborders' => ['style' => PHPExcel_Style_Border::BORDER_MEDIUM]
            ]);
            foreach ($columns as $value) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle("{$column}4:{$column}{$i}")->getBorders()->applyFromArray([
                    'left' => ['style' => PHPExcel_Style_Border::BORDER_MEDIUM],
                    'right' => ['style' => PHPExcel_Style_Border::BORDER_MEDIUM]
                ]);
                $column++;
            }
            if ($formato == 'xlsx') {
                // Redirect output to a client's web browser (Excel2007)
                $filename = $title . '.xlsx';
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            } elseif ($formato == 'ods') {
                // Redirect output to a client's web browser (OpenDocument)
                $filename = $title . '.ods';
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'OpenDocument');
                header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
            } else {
                // Redirect output to a client's web browser (Excel5)
                $filename = $title . '.xls';
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                header('Content-Type: application/vnd.ms-excel');
            }
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($filename));
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            json($e->getMessage());
        }
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'cliente_view',
                'path' => '/conta/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'register',
            ],
            [
                'name' => 'cliente_edit',
                'path' => '/conta/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'edit',
            ],
            [
                'name' => 'cliente_logout',
                'path' => '/conta/sair',
                'method' => 'GET',
                'controller' => 'logout',
            ],
            [
                'name' => 'cliente_login',
                'path' => '/conta/entrar',
                'method' => ['GET', 'POST'],
                'controller' => 'login',
            ],
            [
                'name' => 'cliente_find',
                'path' => '/gerenciar/cliente/',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'cliente_add',
                'path' => '/gerenciar/cliente/cadastrar',
                'method' => ['GET', 'POST'],
                'controller' => 'add',
            ],
            [
                'name' => 'cliente_update',
                'path' => '/gerenciar/cliente/editar',
                'method' => ['GET', 'POST'],
                'controller' => 'update',
            ],
            [
                'name' => 'cliente_delete',
                'path' => '/gerenciar/cliente/excluir',
                'method' => 'GET',
                'controller' => 'delete',
            ],
            [
                'name' => 'cliente_export',
                'path' => '/gerenciar/cliente/baixar',
                'method' => 'GET',
                'controller' => 'export',
            ],
        ];
    }
}
