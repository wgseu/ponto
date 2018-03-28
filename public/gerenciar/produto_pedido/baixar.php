<?php
/*
	Copyright 2016 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
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

need_permission(Permissao::NOME_RELATORIOVENDAS, isset($_GET['saida']) && is_output('json'));

try {
    $funcionario_id = isset($_GET['funcionario_id'])?intval($_GET['funcionario_id']):null;
    $datahora_inicio = isset($_GET['datahora_inicio'])?strtotime($_GET['datahora_inicio']):null;
    $datahora_fim = isset($_GET['datahora_fim'])?strtotime($_GET['datahora_fim']):null;
    $sessao_id = isset($_GET['sessao_id'])?intval($_GET['sessao_id']):null;
    $movimentacao_id = isset($_GET['movimentacao_id'])?intval($_GET['movimentacao_id']):null;
    $pedido_id = isset($_GET['pedido_id'])?intval($_GET['pedido_id']):null;
    $produto_id = isset($_GET['produto_id'])?intval($_GET['produto_id']):null;
    $cancelado = isset($_GET['cancelado'])?$_GET['cancelado']:null;
    $servicos = isset($_GET['servicos'])?$_GET['servicos']:null;
    $formato = isset($_GET['formato'])?$_GET['formato']:null;

    $estado = null;
    if ($cancelado == 'Y') {
        $estado = 'Cancelado';
    } elseif ($cancelado == 'N') {
        $estado = 'Valido';
    }
    $tipo = 'Produtos';
    if ($servicos == 'Y') {
        $tipo = null;
    }
    $itens_do_pedido = ProdutoPedido::getTodos(
        $pedido_id,
        $produto_id,
        $funcionario_id,
        $sessao_id,
        $movimentacao_id,
        $tipo,
        $estado,
        null, // módulo de vendas (todos)
        $datahora_inicio,
        $datahora_fim,
        true // raw enabled
    );
    // Coluna dos dados
    $columns = [
        'Pedido',
        'Destino',
        'Atendente',
        'Código',
        'Descrição',
        'Preço ('. $__moeda__->getSimbolo() . ')',
        'Quantidade',
        'Comissão ('. $__moeda__->getSimbolo() . ')',
        'Subtotal ('. $__moeda__->getSimbolo() . ')',
        'Total ('. $__moeda__->getSimbolo() . ')',
        'Preço de venda ('. $__moeda__->getSimbolo() . ')',
        'Custo ('. $__moeda__->getSimbolo() . ')',
        'Lucro ('. $__moeda__->getSimbolo() . ')',
        'Custo total ('. $__moeda__->getSimbolo() . ')',
        'Lucro total ('. $__moeda__->getSimbolo() . ')',
        'Observações',
        'Data do pedido'
    ];
    $data = [$columns];
    $line = 3;
    $column = 'B';
    $i = $line;
    $value = new ProdutoPedido();
    foreach ($itens_do_pedido as $key => $item) {
        $i++;
        $value->fromArray($item);

        $tz = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $datahora = strtotime($value->getDataHora());
        date_default_timezone_set($tz);

        $row = [];
        $row[] = $value->getPedidoID();
        $row[] = $value->getDestino($item);
        $row[] = $item['funcionario_login'];
        $row[] = $value->getProdutoID();
        $row[] = $item['produto_descricao'];
        $row[] = $value->getPreco();
        $row[] = $value->getQuantidade();
        $row[] = $value->getComissao();
        $row[] = "=G{$i} * H{$i}";
        $row[] = "=I{$i} + J{$i}";
        $row[] = $value->getPrecoVenda();
        $row[] = $value->getPrecoCompra();
        $row[] = "=G{$i} - M{$i}";
        $row[] = "=H{$i} * M{$i}";
        $row[] = "=J{$i} - O{$i}";
        $row[] = $value->getDetalhes();
        $row[] = PHPExcel_Shared_Date::PHPToExcel($datahora);
        $data[] = $row;
    }
    // footer
    $row = [];
    $row[] = count($itens_do_pedido) . ' Vendas';
    $row[] = null;
    $row[] = null;
    $row[] = null;
    $row[] = null;
    $row[] = null;
    $row[] = "=SUM(H4:H{$i})";
    $row[] = "=SUM(I4:I{$i})";
    $row[] = "=SUM(J4:J{$i})";
    $row[] = "=SUM(K4:K{$i})";
    $row[] = null;
    $row[] = null;
    $row[] = null;
    $row[] = "=SUM(O4:O{$i})";
    $row[] = "=SUM(P4:P{$i})";
    $row[] = null;
    $row[] = null;
    $data[] = $row;
    $j = $i + 1;

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("GrandChef")
                                 ->setTitle("Relatório de Vendas");
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Vendas');
    // Title
    $objPHPExcel->getActiveSheet()->mergeCells('B2:R2');
    $objPHPExcel->getActiveSheet()->setCellValue($column . ($line - 1), 'Relatório de Vendas');
    // Add some data
    $objPHPExcel->getActiveSheet()->mergeCells("B{$j}:G{$j}");
    $objPHPExcel->getActiveSheet()->mergeCells("L{$j}:N{$j}");
    $objPHPExcel->getActiveSheet()->mergeCells("Q{$j}:R{$j}");
    $objPHPExcel->getActiveSheet()->fromArray($data, null, $column . $line);
    // Format styles
    $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()
        ->setName('Tahoma')->setBold(true)->setSize(16);
    $objPHPExcel->getActiveSheet()->getStyle("B3:F{$j}")->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("H3:H{$j}")->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("Q3:R{$j}")->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("G3:G{$j}")->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle("I3:P{$j}")->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle("I{$j}:P{$j}")->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle('B3:R3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B3:R3')->getBorders()->applyFromArray([
        'allborders' => ['style' => PHPExcel_Style_Border::BORDER_MEDIUM]
    ]);
    $objPHPExcel->getActiveSheet()->getStyle("B{$j}:R{$j}")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle("B{$j}:R{$j}")->getBorders()->applyFromArray([
        'allborders' => ['style' => PHPExcel_Style_Border::BORDER_MEDIUM]
    ]);
    $objPHPExcel->getActiveSheet()->getStyle("R4:R{$i}")->getNumberFormat()->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME
    );
    $objPHPExcel->getActiveSheet()->getStyle("G4:G{$i}")->getNumberFormat()->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
    );
    $objPHPExcel->getActiveSheet()->getStyle("I4:P{$j}")->getNumberFormat()->setFormatCode(
        PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
    );
    foreach ($columns as $title) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getStyle("{$column}4:{$column}{$i}")->getBorders()->applyFromArray([
            'left' => ['style' => PHPExcel_Style_Border::BORDER_MEDIUM],
            'right' => ['style' => PHPExcel_Style_Border::BORDER_MEDIUM]
        ]);
        $column++;
    }
    if ($formato == 'xlsx') {
        // Redirect output to a client’s web browser (Excel2007)
        $filename = 'Relatório de Vendas.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    } elseif ($formato == 'ods') {
        // Redirect output to a client’s web browser (OpenDocument)
        $filename = 'Relatório de Vendas.ods';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'OpenDocument');
        header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
    } else {
        // Redirect output to a client’s web browser (Excel5)
        $filename = 'Relatório de Vendas.xls';
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
