<?php
use Symfony\Component\Routing\RouteCollection;

$main_collection = new RouteCollection();

\MZ\System\PaginaPageController::addRoutes($main_collection);

$main_collection->addCollection(require_once(__DIR__ . '/api.php'));
$main_collection->addCollection(require_once(__DIR__ . '/old_api.php'));

\MZ\System\SistemaPageController::addRoutes($main_collection);

return $main_collection;
