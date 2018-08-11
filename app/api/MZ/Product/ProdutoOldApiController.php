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
namespace MZ\Product;

use MZ\Stock\Estoque;
use MZ\System\Permissao;
use MZ\Util\Mask;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class ProdutoOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        $limit = isset($_GET['limite']) ? intval($_GET['limite']) : 5;
        if ($limit < 1 || $limit > 10) {
            $limit = 5;
        }
        $primeiro = isset($_GET['primeiro']) ? $_GET['primeiro'] : null;
        if ($primeiro) {
            $limit = 1;
        }
        $condition = [
            'promocao' => 'Y'
        ];
        if (isset($_GET['todos']) && $_GET['todos'] == 'Y') {
            $limit = null;
        }
        if (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
            $limit = null;
            $condition['categoria'] = intval($_GET['categoria']);
        }
        if (isset($_GET['busca'])) {
            $condition['search'] = $_GET['busca'];
        }
        $estoque = isset($_GET['estoque']) ? intval($_GET['estoque']) : 0;
        // estoque == -1 mostra todos os produtos para funcionários
        // estoque ==  0 mostra todos os produtos disponíveis
        // estoque ==  1 mostra apenas produtos de estoque para funcionários
        $negativo = is_boolean_config('Estoque', 'Estoque.Negativo');
        if ($estoque > 0 && is_manager()) {
            $condition['tipo'] = Produto::TIPO_PRODUTO;
        } elseif ($estoque == 0 || !is_manager()) {
            if (!$negativo) {
                $condition['disponivel'] = 'Y';
            }
            $condition['permitido'] = 'Y';
            $condition['visivel'] = 'Y';
        }
        $produtos = Produto::rawFindAll($condition, [], $limit);
        $campos = [
            'id',
            'categoriaid',
            'descricao',
            'detalhes',
            'precovenda',
            'tipo',
            'conteudo',
            'divisivel',
            'dataatualizacao',
            // extras
            'estrelas',
            'estoque',
            'imagemurl',
            'categoria',
            'unidade',
        ];
        $items = [];
        foreach ($produtos as $item) {
            // TODO implementar estrelas de mais vendido
            $item['estrelas'] = 3;
            $item['imagemurl'] = get_image_url($item['imagem'], 'produto', null);
            $items[] = array_intersect_key($item, array_flip($campos));
        }
        json('produtos', $items);
    }

    public function export()
    {
        need_permission(Permissao::NOME_CADASTROPRODUTOS, isset($_GET['saida']) && is_output('json'));

        set_time_limit(0);

        try {
            $formato = isset($_GET['formato']) ? $_GET['formato'] : null;
            $condition = Filter::query($_GET);
            $condition['promocao'] = 'N';
            $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
            $produtos = Produto::findAll($condition, $order);
            // Coluna dos dados
            $columns = [
                'Código',
                'Descrição',
                'Preço de Venda ('. $this->getApplication()->getSystem()->getCurrency()->getSimbolo() . ')',
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
            ];
            $data = [$columns];
            $column = 'B';
            $last = chr(ord($column) + count($columns) - 1);
            $line = 3;
            $i = $line;
            $unidade = new Unidade();
            foreach ($produtos as $value) {
                $i++;
                $unidade = $value->findUnidadeID();
                $setor_estoque = $value->findSetorEstoqueID();
                $setor_preparo = $value->findSetorPreparoID();
                $estoque = Estoque::sumByProdutoID($value->getID());
                $row = [];
                $row[] = $value->getID();
                $row[] = $value->getDescricao();
                $row[] = $value->getPrecoVenda();
                $row[] = $value->findCategoriaID()->getDescricao();
                $row[] = Produto::getTipoOptions($value->getTipo());
                $row[] = $estoque;
                $row[] = $unidade->formatar($estoque, $value->getConteudo());
                $row[] = Mask::bool($value->isDivisivel());
                $row[] = Mask::bool($value->isCobrarServico());
                $row[] = $value->getCodigoBarras();
                $row[] = $value->getConteudo();
                $row[] = $value->getQuantidadeLimite();
                $row[] = $value->getQuantidadeMaxima();
                $row[] = $value->getCustoProducao();
                $row[] = Mask::bool($value->isPerecivel());
                $row[] = $unidade->getNome();
                $row[] = Mask::bool($value->isPesavel());
                $row[] = $setor_estoque->getNome() ?: 'Automático';
                $row[] = $setor_preparo->getNome() ?: 'Não imprimir';
                $row[] = $value->getAbreviacao();
                $row[] = $value->getDetalhes();
                $row[] = Mask::bool($value->isVisivel());
                $data[] = $row;
            }
            // footer
            $row = [];
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
            $objPHPExcel = new \PHPExcel();
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
            $objPHPExcel->getActiveSheet()->fromArray($data, null, $column . $line);
            // Format styles
            // Format Title
            $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(30);
            $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()
                ->setName('Tahoma')->setBold(true)->setSize(16);
            // Align cells
            $objPHPExcel->getActiveSheet()->getStyle("B3:C{$j}")->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("D3:D{$j}")->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle("E3:N{$j}")->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("O3:O{$j}")->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle("P3:{$last}{$j}")->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // Format header
            $objPHPExcel->getActiveSheet()->getStyle("B3:{$last}3")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("B3:{$last}3")->getBorders()->applyFromArray([
                'allborders' => ['style' => \PHPExcel_Style_Border::BORDER_MEDIUM]
            ]);
            // Format footer
            $objPHPExcel->getActiveSheet()->getStyle("B{$j}:{$last}{$j}")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("B{$j}:{$last}{$j}")->getBorders()->applyFromArray([
                'allborders' => ['style' => \PHPExcel_Style_Border::BORDER_MEDIUM]
            ]);
            // Format Currency data columns
            $objPHPExcel->getActiveSheet()->getStyle("D4:D{$i}")->getNumberFormat()->setFormatCode(
                \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
            );
            $objPHPExcel->getActiveSheet()->getStyle("O4:O{$j}")->getNumberFormat()->setFormatCode(
                \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
            );
            foreach ($columns as $value) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle("{$column}4:{$column}{$i}")->getBorders()->applyFromArray([
                    'left' => ['style' => \PHPExcel_Style_Border::BORDER_MEDIUM],
                    'right' => ['style' => \PHPExcel_Style_Border::BORDER_MEDIUM]
                ]);
                $column++;
            }
            if ($formato == 'xlsx') {
                // Redirect output to a client's web browser (Excel2007)
                $filename = $title . '.xlsx';
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            } elseif ($formato == 'ods') {
                // Redirect output to a client's web browser (OpenDocument)
                $filename = $title . '.ods';
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'OpenDocument');
                header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
            } else {
                // Redirect output to a client's web browser (Excel5)
                $filename = $title . '.xls';
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
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
                'name' => 'produto_find',
                'path' => '/app/produto/listar',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'produto_find',
                'path' => '/app/produto/procurar',
                'method' => 'GET',
                'controller' => 'find',
            ],
            [
                'name' => 'produto_export',
                'path' => '/gerenciar/produto/baixar',
                'method' => 'GET',
                'controller' => 'export',
            ]
        ];
    }
}
