<?php
use Symfony\Component\Routing\RouteCollection;

$main_collection = new RouteCollection();

\MZ\System\PaginaPageController::addRoutes($main_collection);

$main_collection->addCollection(require_once(__DIR__ . '/api.php'));
$main_collection->addCollection(require_once(__DIR__ . '/old_api.php'));

\MZ\Environment\MesaPageController::addRoutes($main_collection);
\MZ\Session\CaixaPageController::addRoutes($main_collection);
\MZ\Wallet\BancoPageController::addRoutes($main_collection);
\MZ\Wallet\CarteiraPageController::addRoutes($main_collection);
\MZ\Payment\FormaPagtoPageController::addRoutes($main_collection);
\MZ\Payment\CartaoPageController::addRoutes($main_collection);
\MZ\Provider\FuncaoPageController::addRoutes($main_collection);
\MZ\Account\ClientePageController::addRoutes($main_collection);
\MZ\Provider\PrestadorPageController::addRoutes($main_collection);
\MZ\Wallet\MoedaPageController::addRoutes($main_collection);
\MZ\Location\PaisPageController::addRoutes($main_collection);
\MZ\Location\EstadoPageController::addRoutes($main_collection);
\MZ\Location\CidadePageController::addRoutes($main_collection);
\MZ\Location\BairroPageController::addRoutes($main_collection);
\MZ\Location\LocalizacaoPageController::addRoutes($main_collection);
\MZ\Sale\ComandaPageController::addRoutes($main_collection);
\MZ\Session\MovimentacaoPageController::addRoutes($main_collection);
\MZ\Sale\PedidoPageController::addRoutes($main_collection);
\MZ\Product\CategoriaPageController::addRoutes($main_collection);
\MZ\Product\UnidadePageController::addRoutes($main_collection);
\MZ\Environment\SetorPageController::addRoutes($main_collection);
\MZ\Product\ProdutoPageController::addRoutes($main_collection);
\MZ\Product\ServicoPageController::addRoutes($main_collection);
\MZ\Sale\ItemPageController::addRoutes($main_collection);
\MZ\Account\ClassificacaoPageController::addRoutes($main_collection);
\MZ\Account\ContaPageController::addRoutes($main_collection);
\MZ\Account\CreditoPageController::addRoutes($main_collection);
\MZ\Payment\PagamentoPageController::addRoutes($main_collection);
\MZ\System\AuditoriaPageController::addRoutes($main_collection);
\MZ\Payment\ChequePageController::addRoutes($main_collection);
\MZ\Stock\FornecedorPageController::addRoutes($main_collection);
\MZ\Stock\EstoquePageController::addRoutes($main_collection);
\MZ\System\AcessoPageController::addRoutes($main_collection);
\MZ\System\SistemaPageController::addRoutes($main_collection);
\MZ\System\ModuloPageController::addRoutes($main_collection);
\MZ\Environment\PatrimonioPageController::addRoutes($main_collection);
\MZ\System\IntegracaoPageController::addRoutes($main_collection);

return $main_collection;
