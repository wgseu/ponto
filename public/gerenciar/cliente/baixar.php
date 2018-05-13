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
require_once(dirname(__DIR__) . '/app.php');

use MZ\Location\Localizacao;
use MZ\System\Permissao;
use MZ\Account\Cliente;
use MZ\Util\Filter;
use MZ\Util\Mask;

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
    $objPHPExcel = new PHPExcel();
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
        // Redirect output to a client’s web browser (Excel2007)
        $filename = $title . '.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    } elseif ($formato == 'ods') {
        // Redirect output to a client’s web browser (OpenDocument)
        $filename = $title . '.ods';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'OpenDocument');
        header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
    } else {
        // Redirect output to a client’s web browser (Excel5)
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
