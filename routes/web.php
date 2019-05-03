<?php
use Symfony\Component\Routing\RouteCollection;

$main_collection = new RouteCollection();

\MZ\System\PaginaPageController::addRoutes($main_collection);

$main_collection->addCollection(require_once(__DIR__ . '/api.php'));
$main_collection->addCollection(require_once(__DIR__ . '/old_api.php'));

\MZ\Wallet\CarteiraPageController::addRoutes($main_collection);
\MZ\Location\PaisPageController::addRoutes($main_collection);
\MZ\Location\EstadoPageController::addRoutes($main_collection);
\MZ\Product\UnidadePageController::addRoutes($main_collection);
\MZ\Product\ProdutoPageController::addRoutes($main_collection);
\MZ\System\SistemaPageController::addRoutes($main_collection);

return $main_collection;
