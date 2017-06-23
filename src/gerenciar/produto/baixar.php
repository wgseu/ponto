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
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(PermissaoNome::CADASTROPRODUTOS, isset($_GET['saida']) && $_GET['saida'] == 'json');

set_time_limit(0);

try {
	$query = isset($_GET['query'])?$_GET['query']:null;
	$setor_id = isset($_GET['setor_id'])?intval($_GET['setor_id']):null;
	$categoria_id = isset($_GET['categoria_id'])?intval($_GET['categoria_id']):null;
	$promocao = isset($_GET['promocao'])?$_GET['promocao']:null;
	$visivel = isset($_GET['visivel'])?$_GET['visivel']:null;
	$limitado = isset($_GET['limitado'])?$_GET['limitado']:null;
	$pesavel = isset($_GET['pesavel'])?$_GET['pesavel']:null;
	$tipo = isset($_GET['tipo'])?$_GET['tipo']:null;
	$formato = isset($_GET['formato'])?$_GET['formato']:null;

	$produtos = ZProduto::getTodos(
		$query,
		$categoria_id,
		null, // unidade
		$tipo,
		null, // estoque?
		$setor_id,
		$promocao,
		$visivel,
		$limitado,
		$pesavel,
		true // raw
	);
    // Coluna dos dados
	$columns = array(
		'Código',
		'Descrição',
		'Preço de Venda ('. $__moeda__->getSimbolo() . ')',
		'Categoria',
		'Tipo',
		'Unidades',
		'Estoque',
		'Divisível',
		'Cobrar serviço',
		'Código de Barras',
		'Conteúdo',
		'Quantidade Limite',
		'Quantidade Máxima',
		'Custo Base',
		'Perecível',
		'Unidade',
		'Peso automático',
		'Setor de estoque',
		'Setor de impressão',
		'Abreviação',
		'Detalhes',
		'Visível'
	);
	$data = array($columns);
	$column = 'B';
	$last = chr(ord($column) + count($columns) - 1);
	$line = 3;
	$i = $line;
	$value = new ZProduto();
	$unidade = new ZUnidade();
	foreach ($produtos as $key => $item) {
		$i++;
		$value->fromArray($item);
		$unidade->setSigla($item['unidade']);

		$row = array();
		$row[] = $value->getID();
		$row[] = $value->getDescricao($item);
		$row[] = $value->getPrecoVenda();
		$row[] = $item['categoria'];
		$row[] = ZProduto::getTipoOptions($value->getTipo());
		$row[] = $item['estoque'];
		$row[] = $unidade->formatar($item['estoque'], $value->getConteudo());
		$row[] = $value->isDivisivel()?'Sim':'Não';
		$row[] = $value->isCobrarServico()?'Sim':'Não';
		$row[] = $value->getCodigoBarras();
		$row[] = $value->getConteudo();
		$row[] = $value->getQuantidadeLimite();
		$row[] = $value->getQuantidadeMaxima();
		$row[] = $value->getCustoProducao();
		$row[] = $value->isPerecivel()?'Sim':'Não';
		$row[] = $item['unidade_nome'];
		$row[] = $value->isPesavel()?'Sim':'Não';
		$row[] = isset($item['setor_estoque'])?$item['setor_estoque']:'Automático';
		$row[] = isset($item['setor_preparo'])?$item['setor_preparo']:'Não imprimir';
		$row[] = $value->getAbreviacao();
		$row[] = $value->getDetalhes();
		$row[] = $value->isVisivel()?'Sim':'Não';
		$data[] = $row;
	}
	// footer
	$row = array();
	$row[] = count($produtos) . ' Produtos';
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

	$title = 'Relatório de Produtos';
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	// Set document properties
	$objPHPExcel->getProperties()->setCreator('GrandChef')
								 ->setTitle($title);
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Produtos');
	// Title
	$objPHPExcel->getActiveSheet()->mergeCells("B2:{$last}2");
	$objPHPExcel->getActiveSheet()->setCellValue($column . ($line - 1), $title);
	// Merge footer cells
	$objPHPExcel->getActiveSheet()->mergeCells("B{$j}:D{$j}");
	$objPHPExcel->getActiveSheet()->mergeCells("E{$j}:{$last}{$j}");
	$objPHPExcel->getActiveSheet()->fromArray($data, NULL, $column . $line);
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
	$objPHPExcel->getActiveSheet()->getStyle("B3:C{$j}")->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle("D3:D{$j}")->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle("E3:N{$j}")->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle("O3:O{$j}")->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle("P3:{$last}{$j}")->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	// Format header
	$objPHPExcel->getActiveSheet()->getStyle("B3:{$last}3")->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("B3:{$last}3")->getBorders()->applyFromArray(array(
		'allborders' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
	));
	// Format footer
	$objPHPExcel->getActiveSheet()->getStyle("B{$j}:{$last}{$j}")->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("B{$j}:{$last}{$j}")->getBorders()->applyFromArray(array(
		'allborders' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
	));
	// Format Currency data columns
	$objPHPExcel->getActiveSheet()->getStyle("D4:D{$i}")->getNumberFormat()->setFormatCode(
		PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
	);
	$objPHPExcel->getActiveSheet()->getStyle("O4:O{$j}")->getNumberFormat()->setFormatCode(
		PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
	);
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