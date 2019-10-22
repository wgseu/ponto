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
use MZ\Provider\Prestador;
use MZ\Device\Dispositivo;
use MZ\System\Sistema;
use MZ\System\Acesso;
use MZ\Logger\Log;

/**
 * Allow application to serve system resources
 */
class ClienteOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        app()->needManager();

        $limit = max(1, min(20, $this->getRequest()->query->getInt('limite', 5)));
        $primeiro = $this->getRequest()->query->getBoolean('primeiro');
        $busca = $this->getRequest()->query->get('busca');
        if ($primeiro || check_fone($busca, true)) {
            $limit = 1;
        }
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['limite']);
        unset($condition['primeiro']);
        unset($condition['ordem']);
        if ($this->getRequest()->query->get('busca')) {
            $condition['search'] = $this->getRequest()->query->get('busca');
        }
        $clientes = Cliente::findAll($condition, $order, $limit);
        $items = [];
        foreach ($clientes as $cliente) {
            $cliente->loadTelefone();
            $item = $cliente->publish(app()->auth->provider);
            $items[] = $item;
        }
        return $this->json()->success(['clientes' => $items]);
    }

    public function register()
    {
        if (!app()->getAuthentication()->isLogin()) {
            return $this->json()->error('Usuário não autenticado!');
        }
        $old_cliente = new Cliente();
        try {
            if (!app()->auth->has([Permissao::NOME_PEDIDOMESA]) &&
                !app()->auth->has([Permissao::NOME_PEDIDOCOMANDA]) &&
                !app()->auth->has([Permissao::NOME_PAGAMENTO]) &&
                !app()->auth->has([Permissao::NOME_CADASTROCLIENTES])
            ) {
                throw new \Exception('Você não tem permissão para cadastrar clientes');
            }
            $cliente = new Cliente($this->getRequest()->request->get('cliente'));
            $cliente->setSenha(Generator::token().'a123Z');
            $cliente->filter($old_cliente, app()->auth->provider);
            $cliente->insert();
            $old_cliente->clean($cliente);
            $item = $cliente->publish(app()->auth->provider);
            return $this->json()->success(['cliente' => $item]);
        } catch (\Exception $e) {
            return $this->json()->error($e->getMessage());
        }
    }

    public function login()
    {
        $usuario = $this->getRequest()->request->get('usuario');
        $senha = $this->getRequest()->request->get('senha');
        $cliente = Cliente::findByLoginSenha($usuario, $senha);
        if (!$cliente->exists()) {
            return $this->json()->error('Usuário ou senha incorretos!');
        }
        $prestador = Prestador::findByClienteID($cliente->getID());
        $dispositivo = new Dispositivo();
        if ($prestador->exists()) {
            if (!$prestador->has([Permissao::NOME_SISTEMA])) {
                return $this->json()->error('Você não tem permissão para acessar o sistema!');
            }
            try {
                $device = $this->getRequest()->request->get('device');
                $serial = $this->getRequest()->request->get('serial');
                $dispositivo->setNome($device);
                $dispositivo->setSerial($serial);
                $dispositivo->register($prestador);
                if (!$dispositivo->checkValidacao()) {
                    throw new \Exception(
                        'Este dispositivo ainda não foi validado, ' .
                        'acesse o menu "Configurações" -> "Computadores e Tablets", ' .
                        'selecione o tablet e clique no botão validar!',
                        403
                    );
                }
            } catch (\Exception $e) {
                return $this->json()->error($e->getMessage());
            }
        }
        if (app()->getAuthentication()->isLogin()) {
            app()->getAuthentication()->logout();
        }
        app()->getAuthentication()->login($cliente);
        $status = [];
        $status['token'] = app()->getAuthentication()->makeToken();
        $status['versao'] = Sistema::VERSAO;
        $status['cliente'] = app()->auth->user->getID();
        $status['info'] = [
            'usuario' => [
                'nome' => app()->auth->user->getNome(),
                'email' => app()->auth->user->getEmail(),
                'login' => app()->auth->user->getLogin(),
                'imagemurl' => app()->auth->user->makeImagemURL(false, null)
            ],
        ];
        $status['funcionario'] = intval(app()->auth->provider->getID());
        $status['validacao'] = strval($dispositivo->getValidacao());
        $status['autologout'] = is_boolean_config('Sistema', 'Tablet.Logout');
        if (app()->auth->isManager()) {
            $status['acesso'] = 'funcionario';
        } elseif (app()->getAuthentication()->isLogin()) {
            $status['acesso'] = 'cliente';
        } else {
            $status['acesso'] = 'visitante';
        }
        $status['permissoes'] = app()->getAuthentication()->getPermissions();
        return $this->json()->success($status, 'Login efetuado com sucesso!');
    }

    public function logout()
    {
        app()->getAuthentication()->logout();
        return $this->json()->success([], 'Logout efetuado com sucesso!');
    }

    public function status()
    {
        $company = app()->getSystem()->getCompany();
        $status = [];
        $status['info'] = [
            'empresa' => [
                'nome' => $company->getNome(),
                'imagemurl' => $company->makeImagemURL(false, null)
            ],
            'moeda' => app()->system->currency->publish(app()->auth->provider),
        ];
        $status['versao'] = Sistema::VERSAO;
        $status['validacao'] = '';
        $status['autologout'] = is_boolean_config('Sistema', 'Tablet.Logout');
        if (app()->auth->isManager()) {
            $status['acesso'] = 'funcionario';
        } elseif (app()->getAuthentication()->isLogin()) {
            $status['acesso'] = 'cliente';
        } else {
            $status['acesso'] = 'visitante';
        }
        if (app()->getAuthentication()->isLogin()) {
            $status['cliente'] = app()->auth->user->getID();
            $status['info']['usuario'] = [
                'nome' => app()->auth->user->getNome(),
                'email' => app()->auth->user->getEmail(),
                'login' => app()->auth->user->getLogin(),
                'imagemurl' => app()->auth->user->makeImagemURL(false, null)
            ];
            $status['funcionario'] = intval(app()->auth->provider->getID());
            try {
                $status['permissoes'] = app()->getAuthentication()->getPermissions();
                $dispositivo = new Dispositivo();
                if (app()->auth->isManager()) {
                    $dispositivo->setNome($this->getRequest()->query->get('device'));
                    $dispositivo->setSerial($this->getRequest()->query->get('serial'));
                    $dispositivo->register(app()->auth->provider);
                }
                $status['validacao'] = $dispositivo->getValidacao();
            } catch (\Exception $e) {
                return $this->json()->error($e->getMessage());
            }
            $status['token'] = app()->getAuthentication()->updateToken();
        } else {
            $status['permissoes'] = [];
        }
        return $this->json()->success($status);
    }

    public function export()
    {
        app()->needManager();
        $this->needPermission([Permissao::NOME_RELATORIOCLIENTES]);
        set_time_limit(0);
        try {
            $formato = $this->getRequest()->query->get('formato');
            $condition = Filter::query($this->getRequest()->query->all());
            unset($condition['ordem']);
            unset($condition['formato']);
            $cliente = new Cliente($condition);
            $order = Filter::order($this->getRequest()->query->get('ordem', ''));
            $clientes = Cliente::findAll($condition, $order);
            // Coluna dos dados
            $columns = [
                'Nome/Fantasia',
                'Telefone',
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
                $row[] = Mask::phone($value->getTelefone()->getNumero());
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
            Log::error($e->getMessage());
            return $this->json()->error($e->getMessage());
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
                'name' => 'app_cliente_find',
                'path' => '/app/cliente/procurar',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'app_cliente_register',
                'path' => '/app/cliente/',
                'method' => 'POST',
                'controller' => 'register',
            ],
            [
                'name' => 'app_cliente_login',
                'path' => '/app/conta/entrar',
                'method' => 'POST',
                'controller' => 'login',
            ],
            [
                'name' => 'app_cliente_logout',
                'path' => '/app/conta/sair',
                'method' => 'GET',
                'controller' => 'logout',
            ],
            [
                'name' => 'app_cliente_status',
                'path' => '/app/conta/status',
                'method' => 'GET',
                'controller' => 'status',
            ],
            [
                'name' => 'app_cliente_export',
                'path' => '/gerenciar/cliente/baixar',
                'method' => 'GET',
                'controller' => 'export',
            ],
        ];
    }
}
