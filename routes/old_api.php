<?php
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

\MZ\Product\CategoriaOldApiController::addRoutes($collection);
\MZ\Product\ProdutoOldApiController::addRoutes($collection);
\MZ\Environment\MesaOldApiController::addRoutes($collection);
\MZ\Sale\ComandaOldApiController::addRoutes($collection);
\MZ\Product\ComposicaoOldApiController::addRoutes($collection);
\MZ\Product\GrupoOldApiController::addRoutes($collection);
\MZ\Product\PacoteOldApiController::addRoutes($collection);
\MZ\Sale\PedidoOldApiController::addRoutes($collection);
\MZ\Account\ClienteOldApiController::addRoutes($collection);
\MZ\Employee\FuncionarioOldApiController::addRoutes($collection);
\MZ\Location\EstadoOldApiController::addRoutes($collection);
\MZ\Location\CidadeOldApiController::addRoutes($collection);
\MZ\Location\BairroOldApiController::addRoutes($collection);
\MZ\Location\LocalizacaoOldApiController::addRoutes($collection);
\MZ\Sale\ProdutoPedidoOldApiController::addRoutes($collection);
\MZ\Payment\PagamentoOldApiController::addRoutes($collection);
\MZ\Stock\FornecedorOldApiController::addRoutes($collection);
\MZ\Stock\EstoqueOldApiController::addRoutes($collection);
\MZ\System\SistemaOldApiController::addRoutes($collection);
\MZ\Invoice\EmitenteOldApiController::addRoutes($collection);
\MZ\Invoice\NotaOldApiController::addRoutes($collection);
\MZ\System\IntegracaoOldApiController::addRoutes($collection);

return $collection;
