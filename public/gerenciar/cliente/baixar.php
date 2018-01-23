<?php
/*
	Copyright 2016 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do cliente, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
require_once(dirname(__DIR__) . '/app.php');

need_permission(
    array(
        PermissaoNome::CADASTROCLIENTES,
        array('||'),
        PermissaoNome::RELATORIOCLIENTES
    ),
    isset($_GET['saida']) && is_output('json')
);

set_time_limit(0);

try {
    $query = isset($_GET['query'])?$_GET['query']:null;
    $cpf = isset($_GET['cpf'])?$_GET['cpf']:null;
    $fone = isset($_GET['fone'])?$_GET['fone']:null;
    $email = isset($_GET['email'])?$_GET['email']:null;
    $genero = isset($_GET['genero'])?$_GET['genero']:null;
    $aniversariantes = isset($_GET['aniversariantes'])?$_GET['aniversariantes']:null;
    $formato = isset($_GET['formato'])?$_GET['formato']:null;

    $clientes = ZCliente::getTodos(
        $query,
        null, // tipo
        $genero,
        null, // mes_inicio
        null, // mes_fim
        $cpf,
        $fone,
        $email,
        $aniversariantes
    );
    // Coluna dos dados
    $columns = array(
        'Nome/Fantasia',
        'Telefone',
        'Celular',
        'E-mail',
        'Aniversário/Fundação',
        vsprintf('%s/%s', array(_p('Titulo', 'CPF'), _p('Titulo', 'CNPJ'))),
        'RG/IE',
        'Gênero',
        'Apelido',
        'CEP',
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
    );
    $data = array($columns);
    $column = 'B';
    $last = chr(ord($column) + count($columns) - 1);
    $line = 4;
    $i = $line;
    foreach ($clientes as $value) {
        $i++;
        $localizacao = ZLocalizacao::getPeloClienteID($value->getID());
        $bairro = ZBairro::getPeloID($localizacao->getBairroID());
        $cidade = ZCidade::getPeloID($bairro->getCidadeID());
        $estado = ZEstado::getPeloID($cidade->getEstadoID());

        $row = array();
        $row[] = $value->getNomeCompleto();
        $row[] = \MZ\Util\Mask::phone($value->getFone(1));
        $row[] = \MZ\Util\Mask::phone($value->getFone(2));
        $row[] = $value->getEmail();
        if (is_null($value->getDataAniversario())) {
            $row[] = null;
        } elseif ($value->getTipo() == ClienteTipo::FISICA) {
            $row[] = human_date($value->getDataAniversario());
        } else {
            $row[] = $value->getDataAniversario();
        }
        if ($value->getTipo() == ClienteTipo::FISICA) {
            $row[] = \MZ\Util\Mask::cpf($value->getCPF());
        } else {
            $row[] = \MZ\Util\Mask::cnpj($value->getCPF());
        }
        $row[] = $value->getRG();
        if ($value->getTipo() == ClienteTipo::FISICA) {
            $row[] = ZCliente::getGeneroOptions($value->getGenero());
        } else {
            $row[] = 'Empresa';
        }
        $row[] = $localizacao->getApelido();
        $row[] = \MZ\Util\Mask::cep($localizacao->getCEP());
        $row[] = $bairro->getNome();
        $row[] = $localizacao->getLogradouro();
        $row[] = $localizacao->getNumero();
        $row[] = ZLocalizacao::getTipoOptions($localizacao->getTipo());
        $row[] = $localizacao->getCondominio();
        $row[] = $localizacao->getBloco();
        $row[] = $localizacao->getApartamento();
        $row[] = $localizacao->getComplemento();
        $row[] = $localizacao->getReferencia();
        $row[] = $cidade->getNome();
        $row[] = $estado->getNome();
        $row[] = $localizacao->isMostrar()?'Sim':'Não';
        $data[] = $row;
    }
    // footer
    $row = array();
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
    $objPHPExcel->getActiveSheet()->getStyle("B3:{$last}4")->getBorders()->applyFromArray(array(
        'allborders' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
    ));
    // Format footer
    $objPHPExcel->getActiveSheet()->getStyle("B{$j}:{$last}{$j}")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle("B{$j}:{$last}{$j}")->getBorders()->applyFromArray(array(
        'allborders' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
    ));
    foreach ($columns as $value) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getStyle("{$column}4:{$column}{$i}")->getBorders()->applyFromArray(array(
            'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
            'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
        ));
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
