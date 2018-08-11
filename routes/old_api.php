<?php
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

\MZ\Environment\MesaOldApiController::addRoutes($collection);
\MZ\Session\SessaoOldApiController::addRoutes($collection);
\MZ\Session\CaixaOldApiController::addRoutes($collection);
\MZ\Wallet\BancoOldApiController::addRoutes($collection);
\MZ\Wallet\CarteiraOldApiController::addRoutes($collection);
\MZ\Payment\FormaPagtoOldApiController::addRoutes($collection);
\MZ\Payment\CartaoOldApiController::addRoutes($collection);
\MZ\Employee\FuncaoOldApiController::addRoutes($collection);
\MZ\Account\ClienteOldApiController::addRoutes($collection);
\MZ\Employee\FuncionarioOldApiController::addRoutes($collection);
\MZ\Wallet\MoedaOldApiController::addRoutes($collection);
\MZ\Location\PaisOldApiController::addRoutes($collection);
\MZ\Location\EstadoOldApiController::addRoutes($collection);
\MZ\Location\CidadeOldApiController::addRoutes($collection);
\MZ\Location\BairroOldApiController::addRoutes($collection);
\MZ\Location\LocalizacaoOldApiController::addRoutes($collection);
\MZ\Sale\ComandaOldApiController::addRoutes($collection);
\MZ\Session\MovimentacaoOldApiController::addRoutes($collection);
\MZ\Sale\PedidoOldApiController::addRoutes($collection);
\MZ\Product\CategoriaOldApiController::addRoutes($collection);
\MZ\Product\UnidadeOldApiController::addRoutes($collection);
\MZ\Environment\SetorOldApiController::addRoutes($collection);
\MZ\Invoice\OrigemOldApiController::addRoutes($collection);
\MZ\Invoice\OperacaoOldApiController::addRoutes($collection);
\MZ\Invoice\ImpostoOldApiController::addRoutes($collection);
\MZ\Invoice\TributacaoOldApiController::addRoutes($collection);
\MZ\Product\ProdutoOldApiController::addRoutes($collection);
\MZ\Product\ServicoOldApiController::addRoutes($collection);
\MZ\Sale\ProdutoPedidoOldApiController::addRoutes($collection);
\MZ\Payment\ChequeOldApiController::addRoutes($collection);
\MZ\Account\ClassificacaoOldApiController::addRoutes($collection);
\MZ\Account\ContaOldApiController::addRoutes($collection);
\MZ\Account\CreditoOldApiController::addRoutes($collection);
\MZ\Payment\PagamentoOldApiController::addRoutes($collection);
\MZ\System\AuditoriaOldApiController::addRoutes($collection);
\MZ\Payment\FolhaChequeOldApiController::addRoutes($collection);
\MZ\Product\ComposicaoOldApiController::addRoutes($collection);
\MZ\Stock\FornecedorOldApiController::addRoutes($collection);
\MZ\Stock\EstoqueOldApiController::addRoutes($collection);
\MZ\Product\GrupoOldApiController::addRoutes($collection);
\MZ\Product\PropriedadeOldApiController::addRoutes($collection);
\MZ\Product\PacoteOldApiController::addRoutes($collection);
\MZ\Device\DispositivoOldApiController::addRoutes($collection);
\MZ\Device\ImpressoraOldApiController::addRoutes($collection);
\MZ\Sale\PromocaoOldApiController::addRoutes($collection);
\MZ\System\FuncionalidadeOldApiController::addRoutes($collection);
\MZ\System\PermissaoOldApiController::addRoutes($collection);
\MZ\Employee\AcessoOldApiController::addRoutes($collection);
\MZ\Stock\CatalogoOldApiController::addRoutes($collection);
\MZ\System\SistemaOldApiController::addRoutes($collection);
\MZ\Product\InformacaoOldApiController::addRoutes($collection);
\MZ\Session\ResumoOldApiController::addRoutes($collection);
\MZ\Sale\FormacaoOldApiController::addRoutes($collection);
\MZ\Stock\ListaOldApiController::addRoutes($collection);
\MZ\System\ModuloOldApiController::addRoutes($collection);
\MZ\Stock\CompraOldApiController::addRoutes($collection);
\MZ\Stock\RequisitoOldApiController::addRoutes($collection);
\MZ\Location\EnderecoOldApiController::addRoutes($collection);
\MZ\Company\HorarioOldApiController::addRoutes($collection);
\MZ\Product\ValorNutricionalOldApiController::addRoutes($collection);
\MZ\Sale\TransferenciaOldApiController::addRoutes($collection);
\MZ\Environment\PatrimonioOldApiController::addRoutes($collection);
\MZ\System\PaginaOldApiController::addRoutes($collection);
\MZ\Sale\JuncaoOldApiController::addRoutes($collection);
\MZ\Invoice\RegimeOldApiController::addRoutes($collection);
\MZ\Invoice\EmitenteOldApiController::addRoutes($collection);
\MZ\Invoice\NotaOldApiController::addRoutes($collection);
\MZ\Invoice\EventoOldApiController::addRoutes($collection);
\MZ\System\IntegracaoOldApiController::addRoutes($collection);

return $collection;
