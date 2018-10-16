<?php
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

\MZ\Sale\PedidoApiController::addRoutes($collection);
\MZ\Sale\ItemApiController::addRoutes($collection);
\MZ\Product\ServicoApiController::addRoutes($collection);

return $collection;
