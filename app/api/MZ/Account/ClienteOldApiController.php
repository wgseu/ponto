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

use MZ\System\Permissao;
use MZ\Util\Generator;
use MZ\Util\Filter;
use MZ\Employee\Funcionario;
use MZ\Device\Dispositivo;
use MZ\System\Sistema;
use MZ\Employee\Acesso;

/**
 * Allow application to serve system resources
 */
class ClienteOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        need_manager(true);

        $limit = intval(isset($_GET['limite'])?$_GET['limite']:5);
        $primeiro = isset($_GET['primeiro']) ? $_GET['primeiro']: false;
        $busca = isset($_GET['busca']) ? $_GET['busca'] : null;
        if ($primeiro || check_fone($busca, true)) {
            $limit = 1;
        } elseif ($limit < 1) {
            $limit = 5;
        } elseif ($limit > 20) {
            $limit = 20;
        }
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem']: '');
        $condition = Filter::query($_GET);
        unset($condition['limite']);
        unset($condition['primeiro']);
        unset($condition['ordem']);
        if (isset($_GET['busca'])) {
            $condition['search'] = $_GET['busca'];
        }
        $clientes = Cliente::findAll($condition, $order, $limit);
        $items = [];
        $domask = intval(isset($_GET['formatar']) ? $_GET['formatar'] : 0) != 0;
        foreach ($clientes as $cliente) {
            $item = $cliente->publish();
            if (!$domask) {
                $item['fone1'] = $cliente->getFone(1);
                $item['fone2'] = $cliente->getFone(2);
                $item['cpf'] = $cliente->getCPF();
            }
            $item['imagemurl'] = $item['imagem'];
            $items[] = $item;
        }
        json('clientes', $items);
    }

    public function register()
    {
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
    }

    public function login()
    {
        if (!is_post()) {
            json('Método incorreto');
        }
        $usuario = isset($_POST['usuario']) ? strval($_POST['usuario']) : null;
        $senha = isset($_POST['senha']) ? strval($_POST['senha']) : null;
        $lembrar = isset($_POST['lembrar']) ? strval($_POST['lembrar']) : null;
        $metodo = isset($_POST['metodo']) ? strval($_POST['metodo']) : null;
        $token = isset($_POST['token']) ? strval($_POST['token']) : null;
        $cliente = Cliente::findByLoginSenha($usuario, $senha);
        if (!$cliente->exists()) {
            if ($metodo == 'desktop') {
                $msg = 'Token inválido!';
            } else {
                $msg = 'Usuário ou senha incorretos!';
            }
            if (isset($_POST['metodo'])) {
                \Thunder::error($msg);
                return $this->view('conta_entrar', get_defined_vars());
            } else {
                json($msg);
            }
        }
        $funcionario = Funcionario::findByClienteID($cliente->getID());
        $dispositivo = new Dispositivo();
        if (!isset($_POST['metodo']) && $funcionario->exists()) {
            if (!$funcionario->has(Permissao::NOME_SISTEMA)) {
                json('Você não tem permissão para acessar o sistema!');
            }
            try {
                $device = isset($_POST['device']) ? $_POST['device'] : null;
                $serial = isset($_POST['serial']) ? $_POST['serial'] : null;
                $dispositivo->setNome($device);
                $dispositivo->setSerial($serial);
                $dispositivo->register();
                if (is_null($dispositivo->getValidacao())) {
                    throw new \Exception(
                        'Este dispositivo ainda não foi validado, acesse o menu "Configurações" -> "Computadores e Tablets", ' .
                        'selecione o tablet e clique no botão validar!',
                        1
                    );
                }
            } catch (\Exception $e) {
                json($e->getMessage());
            }
        }
        
        if (is_login()) {
            $this->getApplication()->getAuthentication()->logout();
        }
        $token = null;
        $this->getApplication()->getAuthentication()->login($cliente);
        if ($lembrar == 'true') {
            $token = $this->getApplication()->getAuthentication()->getAuthorization();
        }
        if (isset($_POST['metodo'])) {
            $url = isset($_POST['redirect']) ? strval($_POST['redirect']) : get_redirect_page();
            redirect($url);
        }
        $status = ['status' => 'ok', 'msg' => 'Login efetuado com sucesso!'];
        $status['versao'] = Sistema::VERSAO;
        $status['cliente'] = logged_user()->getID();
        $status['info'] = [
            'usuario' => [
                'nome' => logged_user()->getNome(),
                'email' => logged_user()->getEmail(),
                'login' => logged_user()->getLogin(),
                'imagemurl' => get_image_url(logged_user()->getImagem(), 'cliente', null)
            ]
        ];
        $status['funcionario'] = intval(logged_employee()->getID());
        $status['validacao'] = strval($dispositivo->getValidacao());
        $status['autologout'] = is_boolean_config('Sistema', 'Tablet.Logout');
        if (is_manager()) {
            $status['acesso'] = 'funcionario';
        } elseif (is_login()) {
            $status['acesso'] = 'cliente';
        } else {
            $status['acesso'] = 'visitante';
        }
        $status['permissoes'] = $this->getApplication()->getAuthentication()->getPermissions();
        $status['token'] = $token;
        json($status);
    }

    public function logout()
    {
        $this->getApplication()->getAuthentication()->logout();
        json(['status' => 'ok', 'msg' => 'Logout efetuado com sucesso!']);
    }

    public function status()
    {
        $company = $this->getApplication()->getSystem()->getCompany();
        $status = [];
        $status['status'] = 'ok';
        $status['info'] = [
            'empresa' => [
                'nome' => $company->getNome(),
                'imagemurl' => $company->makeImagem(false, null)
            ]
        ];
        $status['versao'] = Sistema::VERSAO;
        $status['validacao'] = '';
        $status['autologout'] = is_boolean_config('Sistema', 'Tablet.Logout');
        if (is_manager()) {
            $status['acesso'] = 'funcionario';
        } elseif (is_login()) {
            $status['acesso'] = 'cliente';
        } else {
            $status['acesso'] = 'visitante';
        }
        if (is_login()) {
            $status['cliente'] = logged_user()->getID();
            $status['info']['usuario'] = [
                'nome' => logged_user()->getNome(),
                'email' => logged_user()->getEmail(),
                'login' => logged_user()->getLogin(),
                'imagemurl' => get_image_url(logged_user()->getImagem(), 'cliente', null)
            ];
            $status['funcionario'] = intval(logged_employee()->getID());
            try {
                $status['permissoes'] = $this->getApplication()->getAuthentication()->getPermissions();
                $dispositivo = new Dispositivo();
                if (is_manager()) {
                    $dispositivo->setNome(isset($_GET['device']) ? $_GET['device'] : null);
                    $dispositivo->setSerial(isset($_GET['serial']) ? $_GET['serial'] : null);
                    $dispositivo->register();
                }
                $status['validacao'] = $dispositivo->getValidacao();
            } catch (\Exception $e) {
                $status['status'] = 'error';
                $status['msg'] = $e->getMessage();
            }
            $status['token'] = $this->getApplication()->getAuthentication()->updateAuthorization();
        } else {
            $status['permissoes'] = [];
        }
        json($status);
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
                'name' => 'cliente_find',
                'path' => '/app/cliente/procurar',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'cliente_register',
                'path' => '/app/cliente/',
                'method' => 'POST',
                'controller' => 'register',
            ],
            [
                'name' => 'cliente_login',
                'path' => '/app/conta/entrar',
                'method' => 'POST',
                'controller' => 'login',
            ],
            [
                'name' => 'cliente_logout',
                'path' => '/app/conta/sair',
                'method' => 'GET',
                'controller' => 'logout',
            ],
            [
                'name' => 'cliente_status',
                'path' => '/app/conta/status',
                'method' => 'GET',
                'controller' => 'status',
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
