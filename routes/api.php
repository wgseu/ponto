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
\MZ\Wallet\MoedaApiController::addRoutes($collection);
\MZ\Location\ViagemApiController::addRoutes($collection);
\MZ\Account\TelefoneApiController::addRoutes($collection);
\MZ\Location\ZonaApiController::addRoutes($collection);
\MZ\Company\HorarioApiController::addRoutes($collection);
\MZ\Location\EnderecoApiController::addRoutes($collection);
\MZ\Device\DispositivoApiController::addRoutes($collection);
\MZ\Account\ClassificacaoApiController::addRoutes($collection);
\MZ\Account\ClienteApiController::addRoutes($collection);
\MZ\Account\ContaApiController::addRoutes($collection);
\MZ\Account\CreditoApiController::addRoutes($collection);
\MZ\Wallet\BancoApiController::addRoutes($collection);
\MZ\Wallet\CarteiraApiController::addRoutes($collection);
\MZ\System\AuditoriaApiController::addRoutes($collection);
\MZ\System\IntegracaoApiController::addRoutes($collection);
\MZ\System\ModuloApiController::addRoutes($collection);
\MZ\System\SistemaApiController::addRoutes($collection);
\MZ\Stock\EstoqueApiController::addRoutes($collection);
\MZ\Stock\FornecedorApiController::addRoutes($collection);
\MZ\Session\CaixaApiController::addRoutes($collection);
\MZ\Session\MovimentacaoApiController::addRoutes($collection);
\MZ\Sale\ComandaApiController::addRoutes($collection);
\MZ\Provider\FuncaoApiController::addRoutes($collection);
\MZ\Provider\PrestadorApiController::addRoutes($collection);
\MZ\Product\CategoriaApiController::addRoutes($collection);
\MZ\Product\ComposicaoApiController::addRoutes($collection);
\MZ\Product\ProdutoApiController::addRoutes($collection);
\MZ\Product\UnidadeApiController::addRoutes($collection);
\MZ\Payment\ChequeApiController::addRoutes($collection);
\MZ\Location\BairroApiController::addRoutes($collection);
\MZ\Location\CidadeApiController::addRoutes($collection);
\MZ\Location\LocalizacaoApiController::addRoutes($collection);
\MZ\Location\PaisApiController::addRoutes($collection);
\MZ\Environment\PatrimonioApiController::addRoutes($collection);
\MZ\Environment\MesaApiController::addRoutes($collection);

return $collection;
