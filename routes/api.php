<?php
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

\MZ\Sale\PedidoApiController::addRoutes($collection);
\MZ\Sale\ItemApiController::addRoutes($collection);
\MZ\Product\PropriedadeApiController::addRoutes($collection);
\MZ\Product\PacoteApiController::addRoutes($collection);
\MZ\Product\ServicoApiController::addRoutes($collection);
\MZ\Payment\CartaoApiController::addRoutes($collection);
\MZ\Payment\FormaPagtoApiController::addRoutes($collection);

return $collection;
