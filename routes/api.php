<?php
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

\MZ\Product\ServicoApiController::addRoutes($collection);

return $collection;
