<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
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
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

function exitNotFound() {
	header('HTTP/1.0 404 Not Found');
    echo "<h1>404 Imagem não encontrada</h1>";
    echo "A imagem ou o produto não existe.";
    exit;
}
if(!isset($_GET['produto']) || !is_numeric($_GET['produto']))
	exitNotFound();
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
	$imagem = ZProduto::getImagemPeloID(intval($_GET['produto']), true);
    if(strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= strtotime($imagem['dataatualizacao'])) {
	    header('HTTP/1.0 304 Not Modified');
		header('Cache-Control: max-age=12096000, public');
		header('Expires: ' . gmdate('D, d M Y H:i:s T', time() + 12096000));
	    exit;
	}
}
$imagem = ZProduto::getImagemPeloID(intval($_GET['produto']));
$imagebytes = $imagem['imagem'];
if($imagebytes == null)
	exitNotFound();
header('Content-type: image/png');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', strtotime($imagem['dataatualizacao'])));
header('Cache-Control: max-age=12096000, public');
header('Expires: ' . gmdate('D, d M Y H:i:s T', time() + 12096000));
header('Pragma: cache');
print $imagebytes;