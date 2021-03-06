<?php

declare(strict_types=1);

return [
    // The prefix for routes
    'prefix' => 'graphql',

    // The routes to make GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Route
    //
    // Example:
    //
    // Same route for both query and mutation
    //
    // 'routes' => 'path/to/query/{graphql_schema?}',
    //
    // or define each route
    //
    // 'routes' => [
    //     'query' => 'query/{graphql_schema?}',
    //     'mutation' => 'mutation/{graphql_schema?}',
    // ]
    //
    'routes' => '{graphql_schema?}',

    // The controller to use in GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Controller and method
    //
    // Example:
    //
    // 'controllers' => [
    //     'query' => '\Rebing\GraphQL\GraphQLController@query',
    //     'mutation' => '\Rebing\GraphQL\GraphQLController@mutation'
    // ]
    //
    'controllers' => \Rebing\GraphQL\GraphQLController::class . '@query',

    // Any middleware for the graphql route group
    'middleware' => ['cors'],

    // Additional route group attributes
    //
    // Example:
    //
    // 'route_group_attributes' => ['guard' => 'api']
    //
    'route_group_attributes' => [],

    // The name of the default schema used when no argument is provided
    // to GraphQL::schema() or when the route is used without the graphql_schema
    // parameter.
    'default_schema' => 'default',

    // The schemas for query and/or mutation. It expects an array of schemas to provide
    // both the 'query' fields and the 'mutation' fields.
    //
    // You can also provide a middleware that will only apply to the given schema
    //
    // Example:
    //
    //  'schema' => 'default',
    //
    //  'schemas' => [
    //      'default' => [
    //          'query' => [
    //              'users' => 'App\GraphQL\Query\UsersQuery'
    //          ],
    //          'mutation' => [
    //
    //          ]
    //      ],
    //      'user' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\ProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //      'user/me' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\MyProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //  ]
    //
    'schemas' => [
        'default' => [
            'query' => [
                'setores' => 'App\GraphQL\Queries\SetorQuery',
                'mesas' => 'App\GraphQL\Queries\MesaQuery',
                'sessoes' => 'App\GraphQL\Queries\SessaoQuery',
                'bancos' => 'App\GraphQL\Queries\BancoQuery',
                'carteiras' => 'App\GraphQL\Queries\CarteiraQuery',
                'caixas' => 'App\GraphQL\Queries\CaixaQuery',
                'formas' => 'App\GraphQL\Queries\FormaQuery',
                'cartoes' => 'App\GraphQL\Queries\CartaoQuery',
                'funcoes' => 'App\GraphQL\Queries\FuncaoQuery',
                'clientes' => 'App\GraphQL\Queries\ClienteQuery',
                'prestadores' => 'App\GraphQL\Queries\PrestadorQuery',
                'moedas' => 'App\GraphQL\Queries\MoedaQuery',
                'paises' => 'App\GraphQL\Queries\PaisQuery',
                'estados' => 'App\GraphQL\Queries\EstadoQuery',
                'cidades' => 'App\GraphQL\Queries\CidadeQuery',
                'bairros' => 'App\GraphQL\Queries\BairroQuery',
                'zonas' => 'App\GraphQL\Queries\ZonaQuery',
                'localizacoes' => 'App\GraphQL\Queries\LocalizacaoQuery',
                'comandas' => 'App\GraphQL\Queries\ComandaQuery',
                'viagens' => 'App\GraphQL\Queries\ViagemQuery',
                'integracoes' => 'App\GraphQL\Queries\IntegracaoQuery',
                'associacoes' => 'App\GraphQL\Queries\AssociacaoQuery',
                'pedidos' => 'App\GraphQL\Queries\PedidoQuery',
                'categorias' => 'App\GraphQL\Queries\CategoriaQuery',
                'unidades' => 'App\GraphQL\Queries\UnidadeQuery',
                'origens' => 'App\GraphQL\Queries\OrigemQuery',
                'operacoes' => 'App\GraphQL\Queries\OperacaoQuery',
                'impostos' => 'App\GraphQL\Queries\ImpostoQuery',
                'tributacoes' => 'App\GraphQL\Queries\TributacaoQuery',
                'produtos' => 'App\GraphQL\Queries\ProdutoQuery',
                'servicos' => 'App\GraphQL\Queries\ServicoQuery',
                'classificacoes' => 'App\GraphQL\Queries\ClassificacaoQuery',
                'contas' => 'App\GraphQL\Queries\ContaQuery',
                'movimentacoes' => 'App\GraphQL\Queries\MovimentacaoQuery',
                'creditos' => 'App\GraphQL\Queries\CreditoQuery',
                'cheques' => 'App\GraphQL\Queries\ChequeQuery',
                'pagamentos' => 'App\GraphQL\Queries\PagamentoQuery',
                'itens' => 'App\GraphQL\Queries\ItemQuery',
                'modulos' => 'App\GraphQL\Queries\ModuloQuery',
                'funcionalidades' => 'App\GraphQL\Queries\FuncionalidadeQuery',
                'permissoes' => 'App\GraphQL\Queries\PermissaoQuery',
                'auditorias' => 'App\GraphQL\Queries\AuditoriaQuery',
                'composicoes' => 'App\GraphQL\Queries\ComposicaoQuery',
                'listas' => 'App\GraphQL\Queries\ListaQuery',
                'compras' => 'App\GraphQL\Queries\CompraQuery',
                'requisitos' => 'App\GraphQL\Queries\RequisitoQuery',
                'estoques' => 'App\GraphQL\Queries\EstoqueQuery',
                'grupos' => 'App\GraphQL\Queries\GrupoQuery',
                'propriedades' => 'App\GraphQL\Queries\PropriedadeQuery',
                'pacotes' => 'App\GraphQL\Queries\PacoteQuery',
                'dispositivos' => 'App\GraphQL\Queries\DispositivoQuery',
                'impressoras' => 'App\GraphQL\Queries\ImpressoraQuery',
                'promocoes' => 'App\GraphQL\Queries\PromocaoQuery',
                'acessos' => 'App\GraphQL\Queries\AcessoQuery',
                'catalogos' => 'App\GraphQL\Queries\CatalogoQuery',
                'resumos' => 'App\GraphQL\Queries\ResumoQuery',
                'horarios' => 'App\GraphQL\Queries\HorarioQuery',
                'regimes' => 'App\GraphQL\Queries\RegimeQuery',
                'notas' => 'App\GraphQL\Queries\NotaQuery',
                'eventos' => 'App\GraphQL\Queries\EventoQuery',
                'telefones' => 'App\GraphQL\Queries\TelefoneQuery',
                'observacoes' => 'App\GraphQL\Queries\ObservacaoQuery',
                'cupons' => 'App\GraphQL\Queries\CupomQuery',
                'metricas' => 'App\GraphQL\Queries\MetricaQuery',
                'avaliacoes' => 'App\GraphQL\Queries\AvaliacaoQuery',
                'cozinhas' => 'App\GraphQL\Queries\CozinhaQuery',
                'cardapios' => 'App\GraphQL\Queries\CardapioQuery',
                'contagens' => 'App\GraphQL\Queries\ContagemQuery',
                'notificacoes' => 'App\GraphQL\Queries\NotificacaoQuery',
                'saldos' => 'App\GraphQL\Queries\SaldoQuery',
                'conferencias' => 'App\GraphQL\Queries\ConferenciaQuery',
                'juncoes' => 'App\GraphQL\Queries\JuncaoQuery',

                'empresa' => 'App\GraphQL\Queries\EmpresaQuery',
                'sistema' => 'App\GraphQL\Queries\SistemaQuery',
                'emitente' => 'App\GraphQL\Queries\EmitenteQuery',

                'pedido' => 'App\GraphQL\Queries\PedidoSummaryQuery',
                'usuario' => 'App\GraphQL\Queries\UsuarioQuery',
                'dispositivo' => 'App\GraphQL\Queries\DispositivoInfoQuery',
                'cupom' => 'App\GraphQL\Queries\CupomSearchQuery',
            ],
            'mutation' => [
                'LoginCliente' => 'App\GraphQL\Mutations\LoginClienteMutation',
                'LoginGoogle' => 'App\GraphQL\Mutations\LoginGoogleMutation',
                'LoginFacebook' => 'App\GraphQL\Mutations\LoginFacebookMutation',
                'RefreshToken' => 'App\GraphQL\Mutations\RefreshTokenMutation',

                'CreateSetor' => 'App\GraphQL\Mutations\CreateSetorMutation',
                'UpdateSetor' => 'App\GraphQL\Mutations\UpdateSetorMutation',
                'DeleteSetor' => 'App\GraphQL\Mutations\DeleteSetorMutation',

                'CreateMesa' => 'App\GraphQL\Mutations\CreateMesaMutation',
                'UpdateMesa' => 'App\GraphQL\Mutations\UpdateMesaMutation',
                'DeleteMesa' => 'App\GraphQL\Mutations\DeleteMesaMutation',

                'CreateBanco' => 'App\GraphQL\Mutations\CreateBancoMutation',
                'UpdateBanco' => 'App\GraphQL\Mutations\UpdateBancoMutation',
                'DeleteBanco' => 'App\GraphQL\Mutations\DeleteBancoMutation',

                'CreateCarteira' => 'App\GraphQL\Mutations\CreateCarteiraMutation',
                'UpdateCarteira' => 'App\GraphQL\Mutations\UpdateCarteiraMutation',
                'DeleteCarteira' => 'App\GraphQL\Mutations\DeleteCarteiraMutation',

                'CreateCaixa' => 'App\GraphQL\Mutations\CreateCaixaMutation',
                'UpdateCaixa' => 'App\GraphQL\Mutations\UpdateCaixaMutation',
                'DeleteCaixa' => 'App\GraphQL\Mutations\DeleteCaixaMutation',

                'CreateForma' => 'App\GraphQL\Mutations\CreateFormaMutation',
                'UpdateForma' => 'App\GraphQL\Mutations\UpdateFormaMutation',
                'DeleteForma' => 'App\GraphQL\Mutations\DeleteFormaMutation',

                'CreateCartao' => 'App\GraphQL\Mutations\CreateCartaoMutation',
                'UpdateCartao' => 'App\GraphQL\Mutations\UpdateCartaoMutation',
                'DeleteCartao' => 'App\GraphQL\Mutations\DeleteCartaoMutation',

                'CreateFuncao' => 'App\GraphQL\Mutations\CreateFuncaoMutation',
                'UpdateFuncao' => 'App\GraphQL\Mutations\UpdateFuncaoMutation',
                'DeleteFuncao' => 'App\GraphQL\Mutations\DeleteFuncaoMutation',

                'CreateCliente' => 'App\GraphQL\Mutations\CreateClienteMutation',
                'UpdateCliente' => 'App\GraphQL\Mutations\UpdateClienteMutation',
                'DeleteCliente' => 'App\GraphQL\Mutations\DeleteClienteMutation',

                'CreatePrestador' => 'App\GraphQL\Mutations\CreatePrestadorMutation',
                'UpdatePrestador' => 'App\GraphQL\Mutations\UpdatePrestadorMutation',
                'DeletePrestador' => 'App\GraphQL\Mutations\DeletePrestadorMutation',

                'CreateMoeda' => 'App\GraphQL\Mutations\CreateMoedaMutation',
                'UpdateMoeda' => 'App\GraphQL\Mutations\UpdateMoedaMutation',
                'DeleteMoeda' => 'App\GraphQL\Mutations\DeleteMoedaMutation',

                'CreatePais' => 'App\GraphQL\Mutations\CreatePaisMutation',
                'UpdatePais' => 'App\GraphQL\Mutations\UpdatePaisMutation',
                'DeletePais' => 'App\GraphQL\Mutations\DeletePaisMutation',

                'CreateEstado' => 'App\GraphQL\Mutations\CreateEstadoMutation',
                'UpdateEstado' => 'App\GraphQL\Mutations\UpdateEstadoMutation',
                'DeleteEstado' => 'App\GraphQL\Mutations\DeleteEstadoMutation',

                'CreateCidade' => 'App\GraphQL\Mutations\CreateCidadeMutation',
                'UpdateCidade' => 'App\GraphQL\Mutations\UpdateCidadeMutation',
                'DeleteCidade' => 'App\GraphQL\Mutations\DeleteCidadeMutation',

                'CreateBairro' => 'App\GraphQL\Mutations\CreateBairroMutation',
                'UpdateBairro' => 'App\GraphQL\Mutations\UpdateBairroMutation',
                'DeleteBairro' => 'App\GraphQL\Mutations\DeleteBairroMutation',

                'CreateZona' => 'App\GraphQL\Mutations\CreateZonaMutation',
                'UpdateZona' => 'App\GraphQL\Mutations\UpdateZonaMutation',
                'DeleteZona' => 'App\GraphQL\Mutations\DeleteZonaMutation',

                'CreateLocalizacao' => 'App\GraphQL\Mutations\CreateLocalizacaoMutation',
                'UpdateLocalizacao' => 'App\GraphQL\Mutations\UpdateLocalizacaoMutation',
                'DeleteLocalizacao' => 'App\GraphQL\Mutations\DeleteLocalizacaoMutation',

                'CreateComanda' => 'App\GraphQL\Mutations\CreateComandaMutation',
                'UpdateComanda' => 'App\GraphQL\Mutations\UpdateComandaMutation',
                'DeleteComanda' => 'App\GraphQL\Mutations\DeleteComandaMutation',

                'UpdateViagem' => 'App\GraphQL\Mutations\UpdateViagemMutation',
                'UpdateIntegracao' => 'App\GraphQL\Mutations\UpdateIntegracaoMutation',
                'UpdateAssociacao' => 'App\GraphQL\Mutations\UpdateAssociacaoMutation',

                'CreatePedido' => 'App\GraphQL\Mutations\CreatePedidoMutation',
                'UpdatePedido' => 'App\GraphQL\Mutations\UpdatePedidoMutation',

                'CreateCategoria' => 'App\GraphQL\Mutations\CreateCategoriaMutation',
                'UpdateCategoria' => 'App\GraphQL\Mutations\UpdateCategoriaMutation',
                'DeleteCategoria' => 'App\GraphQL\Mutations\DeleteCategoriaMutation',

                'CreateUnidade' => 'App\GraphQL\Mutations\CreateUnidadeMutation',
                'UpdateUnidade' => 'App\GraphQL\Mutations\UpdateUnidadeMutation',
                'DeleteUnidade' => 'App\GraphQL\Mutations\DeleteUnidadeMutation',

                'CreateOrigem' => 'App\GraphQL\Mutations\CreateOrigemMutation',
                'UpdateOrigem' => 'App\GraphQL\Mutations\UpdateOrigemMutation',
                'DeleteOrigem' => 'App\GraphQL\Mutations\DeleteOrigemMutation',

                'CreateOperacao' => 'App\GraphQL\Mutations\CreateOperacaoMutation',
                'UpdateOperacao' => 'App\GraphQL\Mutations\UpdateOperacaoMutation',
                'DeleteOperacao' => 'App\GraphQL\Mutations\DeleteOperacaoMutation',

                'CreateImposto' => 'App\GraphQL\Mutations\CreateImpostoMutation',
                'UpdateImposto' => 'App\GraphQL\Mutations\UpdateImpostoMutation',
                'DeleteImposto' => 'App\GraphQL\Mutations\DeleteImpostoMutation',

                'CreateTributacao' => 'App\GraphQL\Mutations\CreateTributacaoMutation',
                'UpdateTributacao' => 'App\GraphQL\Mutations\UpdateTributacaoMutation',
                'DeleteTributacao' => 'App\GraphQL\Mutations\DeleteTributacaoMutation',

                'CreateProduto' => 'App\GraphQL\Mutations\CreateProdutoMutation',
                'UpdateProduto' => 'App\GraphQL\Mutations\UpdateProdutoMutation',
                'DeleteProduto' => 'App\GraphQL\Mutations\DeleteProdutoMutation',

                'CreateServico' => 'App\GraphQL\Mutations\CreateServicoMutation',
                'UpdateServico' => 'App\GraphQL\Mutations\UpdateServicoMutation',
                'DeleteServico' => 'App\GraphQL\Mutations\DeleteServicoMutation',

                'CreateClassificacao' => 'App\GraphQL\Mutations\CreateClassificacaoMutation',
                'UpdateClassificacao' => 'App\GraphQL\Mutations\UpdateClassificacaoMutation',
                'DeleteClassificacao' => 'App\GraphQL\Mutations\DeleteClassificacaoMutation',

                'CreateConta' => 'App\GraphQL\Mutations\CreateContaMutation',
                'UpdateConta' => 'App\GraphQL\Mutations\UpdateContaMutation',
                'DeleteConta' => 'App\GraphQL\Mutations\DeleteContaMutation',
                'CreateDespesa' => 'App\GraphQL\Mutations\CreateDespesaMutation',

                'CreateMovimentacao' => 'App\GraphQL\Mutations\CreateMovimentacaoMutation',
                'UpdateMovimentacao' => 'App\GraphQL\Mutations\UpdateMovimentacaoMutation',

                'CreateCredito' => 'App\GraphQL\Mutations\CreateCreditoMutation',
                'UpdateCredito' => 'App\GraphQL\Mutations\UpdateCreditoMutation',

                'UpdateCheque' => 'App\GraphQL\Mutations\UpdateChequeMutation',
                'UpdatePagamento' => 'App\GraphQL\Mutations\UpdatePagamentoMutation',
                'CreateTransferencia' => 'App\GraphQL\Mutations\CreateTransferenciaMutation',
                'UpdateModulo' => 'App\GraphQL\Mutations\UpdateModuloMutation',

                'CreateComposicao' => 'App\GraphQL\Mutations\CreateComposicaoMutation',
                'UpdateComposicao' => 'App\GraphQL\Mutations\UpdateComposicaoMutation',
                'DeleteComposicao' => 'App\GraphQL\Mutations\DeleteComposicaoMutation',

                'CreateLista' => 'App\GraphQL\Mutations\CreateListaMutation',
                'UpdateLista' => 'App\GraphQL\Mutations\UpdateListaMutation',
                'DeleteLista' => 'App\GraphQL\Mutations\DeleteListaMutation',

                'CreateCompra' => 'App\GraphQL\Mutations\CreateCompraMutation',
                'UpdateCompra' => 'App\GraphQL\Mutations\UpdateCompraMutation',
                'DeleteCompra' => 'App\GraphQL\Mutations\DeleteCompraMutation',

                'CreateRequisito' => 'App\GraphQL\Mutations\CreateRequisitoMutation',
                'UpdateRequisito' => 'App\GraphQL\Mutations\UpdateRequisitoMutation',
                'DeleteRequisito' => 'App\GraphQL\Mutations\DeleteRequisitoMutation',

                'CreateEstoque' => 'App\GraphQL\Mutations\CreateEstoqueMutation',
                'UpdateEstoque' => 'App\GraphQL\Mutations\UpdateEstoqueMutation',

                'CreateGrupo' => 'App\GraphQL\Mutations\CreateGrupoMutation',
                'UpdateGrupo' => 'App\GraphQL\Mutations\UpdateGrupoMutation',
                'DeleteGrupo' => 'App\GraphQL\Mutations\DeleteGrupoMutation',

                'CreatePropriedade' => 'App\GraphQL\Mutations\CreatePropriedadeMutation',
                'UpdatePropriedade' => 'App\GraphQL\Mutations\UpdatePropriedadeMutation',
                'DeletePropriedade' => 'App\GraphQL\Mutations\DeletePropriedadeMutation',

                'CreatePacote' => 'App\GraphQL\Mutations\CreatePacoteMutation',
                'UpdatePacote' => 'App\GraphQL\Mutations\UpdatePacoteMutation',
                'DeletePacote' => 'App\GraphQL\Mutations\DeletePacoteMutation',

                'CreateDispositivo' => 'App\GraphQL\Mutations\CreateDispositivoMutation',
                'UpdateDispositivo' => 'App\GraphQL\Mutations\UpdateDispositivoMutation',
                'DeleteDispositivo' => 'App\GraphQL\Mutations\DeleteDispositivoMutation',

                'CreateImpressora' => 'App\GraphQL\Mutations\CreateImpressoraMutation',
                'UpdateImpressora' => 'App\GraphQL\Mutations\UpdateImpressoraMutation',
                'DeleteImpressora' => 'App\GraphQL\Mutations\DeleteImpressoraMutation',

                'CreatePromocao' => 'App\GraphQL\Mutations\CreatePromocaoMutation',
                'UpdatePromocao' => 'App\GraphQL\Mutations\UpdatePromocaoMutation',
                'DeletePromocao' => 'App\GraphQL\Mutations\DeletePromocaoMutation',

                'CreateAcesso' => 'App\GraphQL\Mutations\CreateAcessoMutation',
                'DeleteAcesso' => 'App\GraphQL\Mutations\DeleteAcessoMutation',

                'CreateCatalogo' => 'App\GraphQL\Mutations\CreateCatalogoMutation',
                'UpdateCatalogo' => 'App\GraphQL\Mutations\UpdateCatalogoMutation',
                'DeleteCatalogo' => 'App\GraphQL\Mutations\DeleteCatalogoMutation',

                'UpdateSistema' => 'App\GraphQL\Mutations\UpdateSistemaMutation',

                'UpdateResumo' => 'App\GraphQL\Mutations\UpdateResumoMutation',

                'CreateHorario' => 'App\GraphQL\Mutations\CreateHorarioMutation',
                'UpdateHorario' => 'App\GraphQL\Mutations\UpdateHorarioMutation',
                'DeleteHorario' => 'App\GraphQL\Mutations\DeleteHorarioMutation',

                'CreateJuncao' => 'App\GraphQL\Mutations\CreateJuncaoMutation',
                'UpdateJuncao' => 'App\GraphQL\Mutations\UpdateJuncaoMutation',

                'CreateRegime' => 'App\GraphQL\Mutations\CreateRegimeMutation',
                'UpdateRegime' => 'App\GraphQL\Mutations\UpdateRegimeMutation',
                'DeleteRegime' => 'App\GraphQL\Mutations\DeleteRegimeMutation',

                'UpdateEmitente' => 'App\GraphQL\Mutations\UpdateEmitenteMutation',

                'CreateNota' => 'App\GraphQL\Mutations\CreateNotaMutation',
                'UpdateNota' => 'App\GraphQL\Mutations\UpdateNotaMutation',

                'UpdateEmpresa' => 'App\GraphQL\Mutations\UpdateEmpresaMutation',

                'CreateTelefone' => 'App\GraphQL\Mutations\CreateTelefoneMutation',
                'UpdateTelefone' => 'App\GraphQL\Mutations\UpdateTelefoneMutation',
                'DeleteTelefone' => 'App\GraphQL\Mutations\DeleteTelefoneMutation',

                'CreateObservacao' => 'App\GraphQL\Mutations\CreateObservacaoMutation',
                'UpdateObservacao' => 'App\GraphQL\Mutations\UpdateObservacaoMutation',
                'DeleteObservacao' => 'App\GraphQL\Mutations\DeleteObservacaoMutation',

                'CreateCupom' => 'App\GraphQL\Mutations\CreateCupomMutation',
                'UpdateCupom' => 'App\GraphQL\Mutations\UpdateCupomMutation',
                'DeleteCupom' => 'App\GraphQL\Mutations\DeleteCupomMutation',

                'CreateMetrica' => 'App\GraphQL\Mutations\CreateMetricaMutation',
                'UpdateMetrica' => 'App\GraphQL\Mutations\UpdateMetricaMutation',
                'DeleteMetrica' => 'App\GraphQL\Mutations\DeleteMetricaMutation',

                'CreateAvaliacao' => 'App\GraphQL\Mutations\CreateAvaliacaoMutation',
                'UpdateAvaliacao' => 'App\GraphQL\Mutations\UpdateAvaliacaoMutation',

                'CreateCozinha' => 'App\GraphQL\Mutations\CreateCozinhaMutation',
                'UpdateCozinha' => 'App\GraphQL\Mutations\UpdateCozinhaMutation',
                'DeleteCozinha' => 'App\GraphQL\Mutations\DeleteCozinhaMutation',

                'CreateCardapio' => 'App\GraphQL\Mutations\CreateCardapioMutation',
                'UpdateCardapio' => 'App\GraphQL\Mutations\UpdateCardapioMutation',
                'DeleteCardapio' => 'App\GraphQL\Mutations\DeleteCardapioMutation',

                'UpdateNotificacao' => 'App\GraphQL\Mutations\UpdateNotificacaoMutation',

                'CreateConferencia' => 'App\GraphQL\Mutations\CreateConferenciaMutation',
                'UpdateConferencia' => 'App\GraphQL\Mutations\UpdateConferenciaMutation',
            ],
            'middleware' => [],
            'method'     => ['get', 'post'],
        ],
    ],

    // The types available in the application. You can then access it from the
    // facade like this: GraphQL::type('user')
    //
    // Example:
    //
    // 'types' => [
    //     'user' => 'App\GraphQL\Type\UserType'
    // ]
    //
    'types' => [
        'IdFilter' => 'App\GraphQL\Filters\IdFilter',
        'StringFilter' => 'App\GraphQL\Filters\StringFilter',
        'NumberFilter' => 'App\GraphQL\Filters\NumberFilter',
        'NumberRangeFilter' => 'App\GraphQL\Filters\NumberRangeFilter',
        'DateFilter' => 'App\GraphQL\Filters\DateFilter',
        'DateRangeFilter' => 'App\GraphQL\Filters\DateRangeFilter',

        'OrderByEnum' => 'App\GraphQL\Enums\OrderByEnum',

        'SetorFilter' => 'App\GraphQL\Filters\SetorFilter',
        'MesaFilter' => 'App\GraphQL\Filters\MesaFilter',
        'SessaoFilter' => 'App\GraphQL\Filters\SessaoFilter',
        'BancoFilter' => 'App\GraphQL\Filters\BancoFilter',
        'CarteiraFilter' => 'App\GraphQL\Filters\CarteiraFilter',
        'CaixaFilter' => 'App\GraphQL\Filters\CaixaFilter',
        'FormaFilter' => 'App\GraphQL\Filters\FormaFilter',
        'CartaoFilter' => 'App\GraphQL\Filters\CartaoFilter',
        'FuncaoFilter' => 'App\GraphQL\Filters\FuncaoFilter',
        'ClienteFilter' => 'App\GraphQL\Filters\ClienteFilter',
        'PrestadorFilter' => 'App\GraphQL\Filters\PrestadorFilter',
        'MoedaFilter' => 'App\GraphQL\Filters\MoedaFilter',
        'PaisFilter' => 'App\GraphQL\Filters\PaisFilter',
        'EstadoFilter' => 'App\GraphQL\Filters\EstadoFilter',
        'CidadeFilter' => 'App\GraphQL\Filters\CidadeFilter',
        'BairroFilter' => 'App\GraphQL\Filters\BairroFilter',
        'ZonaFilter' => 'App\GraphQL\Filters\ZonaFilter',
        'LocalizacaoFilter' => 'App\GraphQL\Filters\LocalizacaoFilter',
        'ComandaFilter' => 'App\GraphQL\Filters\ComandaFilter',
        'ViagemFilter' => 'App\GraphQL\Filters\ViagemFilter',
        'IntegracaoFilter' => 'App\GraphQL\Filters\IntegracaoFilter',
        'AssociacaoFilter' => 'App\GraphQL\Filters\AssociacaoFilter',
        'PedidoFilter' => 'App\GraphQL\Filters\PedidoFilter',
        'CategoriaFilter' => 'App\GraphQL\Filters\CategoriaFilter',
        'UnidadeFilter' => 'App\GraphQL\Filters\UnidadeFilter',
        'OrigemFilter' => 'App\GraphQL\Filters\OrigemFilter',
        'OperacaoFilter' => 'App\GraphQL\Filters\OperacaoFilter',
        'ImpostoFilter' => 'App\GraphQL\Filters\ImpostoFilter',
        'TributacaoFilter' => 'App\GraphQL\Filters\TributacaoFilter',
        'ProdutoFilter' => 'App\GraphQL\Filters\ProdutoFilter',
        'ServicoFilter' => 'App\GraphQL\Filters\ServicoFilter',
        'ClassificacaoFilter' => 'App\GraphQL\Filters\ClassificacaoFilter',
        'ContaFilter' => 'App\GraphQL\Filters\ContaFilter',
        'MovimentacaoFilter' => 'App\GraphQL\Filters\MovimentacaoFilter',
        'CreditoFilter' => 'App\GraphQL\Filters\CreditoFilter',
        'ChequeFilter' => 'App\GraphQL\Filters\ChequeFilter',
        'PagamentoFilter' => 'App\GraphQL\Filters\PagamentoFilter',
        'ItemFilter' => 'App\GraphQL\Filters\ItemFilter',
        'ModuloFilter' => 'App\GraphQL\Filters\ModuloFilter',
        'FuncionalidadeFilter' => 'App\GraphQL\Filters\FuncionalidadeFilter',
        'PermissaoFilter' => 'App\GraphQL\Filters\PermissaoFilter',
        'AuditoriaFilter' => 'App\GraphQL\Filters\AuditoriaFilter',
        'ComposicaoFilter' => 'App\GraphQL\Filters\ComposicaoFilter',
        'ListaFilter' => 'App\GraphQL\Filters\ListaFilter',
        'CompraFilter' => 'App\GraphQL\Filters\CompraFilter',
        'RequisitoFilter' => 'App\GraphQL\Filters\RequisitoFilter',
        'EstoqueFilter' => 'App\GraphQL\Filters\EstoqueFilter',
        'GrupoFilter' => 'App\GraphQL\Filters\GrupoFilter',
        'PropriedadeFilter' => 'App\GraphQL\Filters\PropriedadeFilter',
        'PacoteFilter' => 'App\GraphQL\Filters\PacoteFilter',
        'DispositivoFilter' => 'App\GraphQL\Filters\DispositivoFilter',
        'ImpressoraFilter' => 'App\GraphQL\Filters\ImpressoraFilter',
        'PromocaoFilter' => 'App\GraphQL\Filters\PromocaoFilter',
        'AcessoFilter' => 'App\GraphQL\Filters\AcessoFilter',
        'CatalogoFilter' => 'App\GraphQL\Filters\CatalogoFilter',
        'ResumoFilter' => 'App\GraphQL\Filters\ResumoFilter',
        'HorarioFilter' => 'App\GraphQL\Filters\HorarioFilter',
        'RegimeFilter' => 'App\GraphQL\Filters\RegimeFilter',
        'NotaFilter' => 'App\GraphQL\Filters\NotaFilter',
        'EventoFilter' => 'App\GraphQL\Filters\EventoFilter',
        'TelefoneFilter' => 'App\GraphQL\Filters\TelefoneFilter',
        'ObservacaoFilter' => 'App\GraphQL\Filters\ObservacaoFilter',
        'CupomFilter' => 'App\GraphQL\Filters\CupomFilter',
        'MetricaFilter' => 'App\GraphQL\Filters\MetricaFilter',
        'AvaliacaoFilter' => 'App\GraphQL\Filters\AvaliacaoFilter',
        'CozinhaFilter' => 'App\GraphQL\Filters\CozinhaFilter',
        'CardapioFilter' => 'App\GraphQL\Filters\CardapioFilter',
        'ContagemFilter' => 'App\GraphQL\Filters\ContagemFilter',
        'NotificacaoFilter' => 'App\GraphQL\Filters\NotificacaoFilter',
        'SaldoFilter' => 'App\GraphQL\Filters\SaldoFilter',
        'ConferenciaFilter' => 'App\GraphQL\Filters\ConferenciaFilter',

        'CarteiraTipoFilter' => 'App\GraphQL\Filters\CarteiraTipoFilter',
        'CarteiraAmbienteFilter' => 'App\GraphQL\Filters\CarteiraAmbienteFilter',
        'FormaTipoFilter' => 'App\GraphQL\Filters\FormaTipoFilter',
        'ClienteTipoFilter' => 'App\GraphQL\Filters\ClienteTipoFilter',
        'ClienteGeneroFilter' => 'App\GraphQL\Filters\ClienteGeneroFilter',
        'ClienteStatusFilter' => 'App\GraphQL\Filters\ClienteStatusFilter',
        'PrestadorVinculoFilter' => 'App\GraphQL\Filters\PrestadorVinculoFilter',
        'LocalizacaoTipoFilter' => 'App\GraphQL\Filters\LocalizacaoTipoFilter',
        'AssociacaoStatusFilter' => 'App\GraphQL\Filters\AssociacaoStatusFilter',
        'PedidoTipoFilter' => 'App\GraphQL\Filters\PedidoTipoFilter',
        'PedidoEstadoFilter' => 'App\GraphQL\Filters\PedidoEstadoFilter',
        'ImpostoGrupoFilter' => 'App\GraphQL\Filters\ImpostoGrupoFilter',
        'ProdutoTipoFilter' => 'App\GraphQL\Filters\ProdutoTipoFilter',
        'ServicoTipoFilter' => 'App\GraphQL\Filters\ServicoTipoFilter',
        'ContaTipoFilter' => 'App\GraphQL\Filters\ContaTipoFilter',
        'ContaFonteFilter' => 'App\GraphQL\Filters\ContaFonteFilter',
        'ContaModoFilter' => 'App\GraphQL\Filters\ContaModoFilter',
        'ContaFormulaFilter' => 'App\GraphQL\Filters\ContaFormulaFilter',
        'ContaEstadoFilter' => 'App\GraphQL\Filters\ContaEstadoFilter',
        'PagamentoEstadoFilter' => 'App\GraphQL\Filters\PagamentoEstadoFilter',
        'ItemEstadoFilter' => 'App\GraphQL\Filters\ItemEstadoFilter',
        'AuditoriaTipoFilter' => 'App\GraphQL\Filters\AuditoriaTipoFilter',
        'AuditoriaPrioridadeFilter' => 'App\GraphQL\Filters\AuditoriaPrioridadeFilter',
        'ComposicaoTipoFilter' => 'App\GraphQL\Filters\ComposicaoTipoFilter',
        'ListaEstadoFilter' => 'App\GraphQL\Filters\ListaEstadoFilter',
        'GrupoTipoFilter' => 'App\GraphQL\Filters\GrupoTipoFilter',
        'GrupoFuncaoFilter' => 'App\GraphQL\Filters\GrupoFuncaoFilter',
        'DispositivoTipoFilter' => 'App\GraphQL\Filters\DispositivoTipoFilter',
        'ImpressoraModoFilter' => 'App\GraphQL\Filters\ImpressoraModoFilter',
        'PromocaoLocalFilter' => 'App\GraphQL\Filters\PromocaoLocalFilter',
        'PromocaoFuncaoVendasFilter' => 'App\GraphQL\Filters\PromocaoFuncaoVendasFilter',
        'PromocaoFuncaoClienteFilter' => 'App\GraphQL\Filters\PromocaoFuncaoClienteFilter',
        'HorarioModoFilter' => 'App\GraphQL\Filters\HorarioModoFilter',
        'NotaTipoFilter' => 'App\GraphQL\Filters\NotaTipoFilter',
        'NotaAmbienteFilter' => 'App\GraphQL\Filters\NotaAmbienteFilter',
        'NotaAcaoFilter' => 'App\GraphQL\Filters\NotaAcaoFilter',
        'NotaEstadoFilter' => 'App\GraphQL\Filters\NotaEstadoFilter',
        'EventoEstadoFilter' => 'App\GraphQL\Filters\EventoEstadoFilter',
        'CupomTipoDescontoFilter' => 'App\GraphQL\Filters\CupomTipoDescontoFilter',
        'CupomFuncaoPedidosFilter' => 'App\GraphQL\Filters\CupomFuncaoPedidosFilter',
        'CupomFuncaoValorFilter' => 'App\GraphQL\Filters\CupomFuncaoValorFilter',
        'MetricaTipoFilter' => 'App\GraphQL\Filters\MetricaTipoFilter',
        'CardapioLocalFilter' => 'App\GraphQL\Filters\CardapioLocalFilter',
        'IntegracaoTipoFilter' => 'App\GraphQL\Filters\IntegracaoTipoFilter',
        'JuncaoFilter' => 'App\GraphQL\Filters\JuncaoFilter',
        'JuncaoEstadoFilter' => 'App\GraphQL\Filters\JuncaoEstadoFilter',

        'SetorOrder' => 'App\GraphQL\Ordering\SetorOrder',
        'MesaOrder' => 'App\GraphQL\Ordering\MesaOrder',
        'SessaoOrder' => 'App\GraphQL\Ordering\SessaoOrder',
        'BancoOrder' => 'App\GraphQL\Ordering\BancoOrder',
        'CarteiraOrder' => 'App\GraphQL\Ordering\CarteiraOrder',
        'CaixaOrder' => 'App\GraphQL\Ordering\CaixaOrder',
        'FormaOrder' => 'App\GraphQL\Ordering\FormaOrder',
        'CartaoOrder' => 'App\GraphQL\Ordering\CartaoOrder',
        'FuncaoOrder' => 'App\GraphQL\Ordering\FuncaoOrder',
        'ClienteOrder' => 'App\GraphQL\Ordering\ClienteOrder',
        'PrestadorOrder' => 'App\GraphQL\Ordering\PrestadorOrder',
        'MoedaOrder' => 'App\GraphQL\Ordering\MoedaOrder',
        'PaisOrder' => 'App\GraphQL\Ordering\PaisOrder',
        'EstadoOrder' => 'App\GraphQL\Ordering\EstadoOrder',
        'CidadeOrder' => 'App\GraphQL\Ordering\CidadeOrder',
        'BairroOrder' => 'App\GraphQL\Ordering\BairroOrder',
        'ZonaOrder' => 'App\GraphQL\Ordering\ZonaOrder',
        'LocalizacaoOrder' => 'App\GraphQL\Ordering\LocalizacaoOrder',
        'ComandaOrder' => 'App\GraphQL\Ordering\ComandaOrder',
        'ViagemOrder' => 'App\GraphQL\Ordering\ViagemOrder',
        'IntegracaoOrder' => 'App\GraphQL\Ordering\IntegracaoOrder',
        'AssociacaoOrder' => 'App\GraphQL\Ordering\AssociacaoOrder',
        'PedidoOrder' => 'App\GraphQL\Ordering\PedidoOrder',
        'CategoriaOrder' => 'App\GraphQL\Ordering\CategoriaOrder',
        'UnidadeOrder' => 'App\GraphQL\Ordering\UnidadeOrder',
        'OrigemOrder' => 'App\GraphQL\Ordering\OrigemOrder',
        'OperacaoOrder' => 'App\GraphQL\Ordering\OperacaoOrder',
        'ImpostoOrder' => 'App\GraphQL\Ordering\ImpostoOrder',
        'TributacaoOrder' => 'App\GraphQL\Ordering\TributacaoOrder',
        'ProdutoOrder' => 'App\GraphQL\Ordering\ProdutoOrder',
        'ServicoOrder' => 'App\GraphQL\Ordering\ServicoOrder',
        'ClassificacaoOrder' => 'App\GraphQL\Ordering\ClassificacaoOrder',
        'ContaOrder' => 'App\GraphQL\Ordering\ContaOrder',
        'MovimentacaoOrder' => 'App\GraphQL\Ordering\MovimentacaoOrder',
        'CreditoOrder' => 'App\GraphQL\Ordering\CreditoOrder',
        'ChequeOrder' => 'App\GraphQL\Ordering\ChequeOrder',
        'PagamentoOrder' => 'App\GraphQL\Ordering\PagamentoOrder',
        'ItemOrder' => 'App\GraphQL\Ordering\ItemOrder',
        'ModuloOrder' => 'App\GraphQL\Ordering\ModuloOrder',
        'FuncionalidadeOrder' => 'App\GraphQL\Ordering\FuncionalidadeOrder',
        'PermissaoOrder' => 'App\GraphQL\Ordering\PermissaoOrder',
        'AuditoriaOrder' => 'App\GraphQL\Ordering\AuditoriaOrder',
        'ComposicaoOrder' => 'App\GraphQL\Ordering\ComposicaoOrder',
        'ListaOrder' => 'App\GraphQL\Ordering\ListaOrder',
        'CompraOrder' => 'App\GraphQL\Ordering\CompraOrder',
        'RequisitoOrder' => 'App\GraphQL\Ordering\RequisitoOrder',
        'EstoqueOrder' => 'App\GraphQL\Ordering\EstoqueOrder',
        'GrupoOrder' => 'App\GraphQL\Ordering\GrupoOrder',
        'PropriedadeOrder' => 'App\GraphQL\Ordering\PropriedadeOrder',
        'PacoteOrder' => 'App\GraphQL\Ordering\PacoteOrder',
        'DispositivoOrder' => 'App\GraphQL\Ordering\DispositivoOrder',
        'ImpressoraOrder' => 'App\GraphQL\Ordering\ImpressoraOrder',
        'PromocaoOrder' => 'App\GraphQL\Ordering\PromocaoOrder',
        'AcessoOrder' => 'App\GraphQL\Ordering\AcessoOrder',
        'CatalogoOrder' => 'App\GraphQL\Ordering\CatalogoOrder',
        'ResumoOrder' => 'App\GraphQL\Ordering\ResumoOrder',
        'HorarioOrder' => 'App\GraphQL\Ordering\HorarioOrder',
        'RegimeOrder' => 'App\GraphQL\Ordering\RegimeOrder',
        'NotaOrder' => 'App\GraphQL\Ordering\NotaOrder',
        'EventoOrder' => 'App\GraphQL\Ordering\EventoOrder',
        'TelefoneOrder' => 'App\GraphQL\Ordering\TelefoneOrder',
        'ObservacaoOrder' => 'App\GraphQL\Ordering\ObservacaoOrder',
        'CupomOrder' => 'App\GraphQL\Ordering\CupomOrder',
        'MetricaOrder' => 'App\GraphQL\Ordering\MetricaOrder',
        'AvaliacaoOrder' => 'App\GraphQL\Ordering\AvaliacaoOrder',
        'CozinhaOrder' => 'App\GraphQL\Ordering\CozinhaOrder',
        'CardapioOrder' => 'App\GraphQL\Ordering\CardapioOrder',
        'ContagemOrder' => 'App\GraphQL\Ordering\ContagemOrder',
        'NotificacaoOrder' => 'App\GraphQL\Ordering\NotificacaoOrder',
        'SaldoOrder' => 'App\GraphQL\Ordering\SaldoOrder',
        'ConferenciaOrder' => 'App\GraphQL\Ordering\ConferenciaOrder',
        'JuncaoOrder' => 'App\GraphQL\Ordering\JuncaoOrder',

        'SetorInput' => 'App\GraphQL\Inputs\SetorInput',
        'MesaInput' => 'App\GraphQL\Inputs\MesaInput',
        'BancoInput' => 'App\GraphQL\Inputs\BancoInput',
        'CarteiraInput' => 'App\GraphQL\Inputs\CarteiraInput',
        'CaixaInput' => 'App\GraphQL\Inputs\CaixaInput',
        'FormaInput' => 'App\GraphQL\Inputs\FormaInput',
        'CartaoInput' => 'App\GraphQL\Inputs\CartaoInput',
        'FuncaoInput' => 'App\GraphQL\Inputs\FuncaoInput',
        'ClienteInput' => 'App\GraphQL\Inputs\ClienteInput',
        'PrestadorInput' => 'App\GraphQL\Inputs\PrestadorInput',
        'MoedaInput' => 'App\GraphQL\Inputs\MoedaInput',
        'PaisInput' => 'App\GraphQL\Inputs\PaisInput',
        'EstadoInput' => 'App\GraphQL\Inputs\EstadoInput',
        'CidadeInput' => 'App\GraphQL\Inputs\CidadeInput',
        'BairroInput' => 'App\GraphQL\Inputs\BairroInput',
        'ZonaInput' => 'App\GraphQL\Inputs\ZonaInput',
        'LocalizacaoInput' => 'App\GraphQL\Inputs\LocalizacaoInput',
        'ComandaInput' => 'App\GraphQL\Inputs\ComandaInput',
        'ViagemInput' => 'App\GraphQL\Inputs\ViagemInput',
        'IntegracaoInput' => 'App\GraphQL\Inputs\IntegracaoInput',
        'AssociacaoInput' => 'App\GraphQL\Inputs\AssociacaoInput',
        'PedidoInput' => 'App\GraphQL\Inputs\PedidoInput',
        'CategoriaInput' => 'App\GraphQL\Inputs\CategoriaInput',
        'UnidadeInput' => 'App\GraphQL\Inputs\UnidadeInput',
        'OrigemInput' => 'App\GraphQL\Inputs\OrigemInput',
        'OperacaoInput' => 'App\GraphQL\Inputs\OperacaoInput',
        'ImpostoInput' => 'App\GraphQL\Inputs\ImpostoInput',
        'TributacaoInput' => 'App\GraphQL\Inputs\TributacaoInput',
        'ProdutoInput' => 'App\GraphQL\Inputs\ProdutoInput',
        'ServicoInput' => 'App\GraphQL\Inputs\ServicoInput',
        'ClassificacaoInput' => 'App\GraphQL\Inputs\ClassificacaoInput',
        'ContaInput' => 'App\GraphQL\Inputs\ContaInput',
        'DespesaInput' => 'App\GraphQL\Inputs\DespesaInput',
        'MovimentacaoInput' => 'App\GraphQL\Inputs\MovimentacaoInput',
        'CreditoInput' => 'App\GraphQL\Inputs\CreditoInput',
        'ChequeInput' => 'App\GraphQL\Inputs\ChequeInput',
        'PagamentoInput' => 'App\GraphQL\Inputs\PagamentoInput',
        'SubPagamentoInput' => 'App\GraphQL\Inputs\SubPagamentoInput',
        'TransferenciaInput' => 'App\GraphQL\Inputs\TransferenciaInput',
        'ItemInput' => 'App\GraphQL\Inputs\ItemInput',
        'SubitemInput' => 'App\GraphQL\Inputs\SubitemInput',
        'ModuloInput' => 'App\GraphQL\Inputs\ModuloInput',
        'ComposicaoInput' => 'App\GraphQL\Inputs\ComposicaoInput',
        'ListaInput' => 'App\GraphQL\Inputs\ListaInput',
        'CompraInput' => 'App\GraphQL\Inputs\CompraInput',
        'RequisitoInput' => 'App\GraphQL\Inputs\RequisitoInput',
        'EstoqueInput' => 'App\GraphQL\Inputs\EstoqueInput',
        'GrupoInput' => 'App\GraphQL\Inputs\GrupoInput',
        'PropriedadeInput' => 'App\GraphQL\Inputs\PropriedadeInput',
        'PacoteInput' => 'App\GraphQL\Inputs\PacoteInput',
        'DispositivoInput' => 'App\GraphQL\Inputs\DispositivoInput',
        'ImpressoraInput' => 'App\GraphQL\Inputs\ImpressoraInput',
        'PromocaoInput' => 'App\GraphQL\Inputs\PromocaoInput',
        'AcessoInput' => 'App\GraphQL\Inputs\AcessoInput',
        'CatalogoInput' => 'App\GraphQL\Inputs\CatalogoInput',
        'SistemaInput' => 'App\GraphQL\Inputs\SistemaInput',
        'ResumoInput' => 'App\GraphQL\Inputs\ResumoInput',
        'FormacaoInput' => 'App\GraphQL\Inputs\FormacaoInput',
        'HorarioInput' => 'App\GraphQL\Inputs\HorarioInput',
        'JuncaoInput' => 'App\GraphQL\Inputs\JuncaoInput',
        'RegimeInput' => 'App\GraphQL\Inputs\RegimeInput',
        'EmitenteInput' => 'App\GraphQL\Inputs\EmitenteInput',
        'NotaInput' => 'App\GraphQL\Inputs\NotaInput',
        'EmpresaInput' => 'App\GraphQL\Inputs\EmpresaInput',
        'TelefoneInput' => 'App\GraphQL\Inputs\TelefoneInput',
        'ObservacaoInput' => 'App\GraphQL\Inputs\ObservacaoInput',
        'CupomInput' => 'App\GraphQL\Inputs\CupomInput',
        'MetricaInput' => 'App\GraphQL\Inputs\MetricaInput',
        'AvaliacaoInput' => 'App\GraphQL\Inputs\AvaliacaoInput',
        'SubAvaliacaoInput' => 'App\GraphQL\Inputs\SubAvaliacaoInput',
        'CozinhaInput' => 'App\GraphQL\Inputs\CozinhaInput',
        'CardapioInput' => 'App\GraphQL\Inputs\CardapioInput',
        'ConferenciaInput' => 'App\GraphQL\Inputs\ConferenciaInput',
        'CupomPedidoInput' => 'App\GraphQL\Inputs\CupomPedidoInput',

        'SetorUpdateInput' => 'App\GraphQL\Inputs\SetorUpdateInput',
        'MesaUpdateInput' => 'App\GraphQL\Inputs\MesaUpdateInput',
        'BancoUpdateInput' => 'App\GraphQL\Inputs\BancoUpdateInput',
        'CarteiraUpdateInput' => 'App\GraphQL\Inputs\CarteiraUpdateInput',
        'CaixaUpdateInput' => 'App\GraphQL\Inputs\CaixaUpdateInput',
        'FormaUpdateInput' => 'App\GraphQL\Inputs\FormaUpdateInput',
        'CartaoUpdateInput' => 'App\GraphQL\Inputs\CartaoUpdateInput',
        'FuncaoUpdateInput' => 'App\GraphQL\Inputs\FuncaoUpdateInput',
        'ClienteUpdateInput' => 'App\GraphQL\Inputs\ClienteUpdateInput',
        'PrestadorUpdateInput' => 'App\GraphQL\Inputs\PrestadorUpdateInput',
        'MoedaUpdateInput' => 'App\GraphQL\Inputs\MoedaUpdateInput',
        'PaisUpdateInput' => 'App\GraphQL\Inputs\PaisUpdateInput',
        'EstadoUpdateInput' => 'App\GraphQL\Inputs\EstadoUpdateInput',
        'CidadeUpdateInput' => 'App\GraphQL\Inputs\CidadeUpdateInput',
        'BairroUpdateInput' => 'App\GraphQL\Inputs\BairroUpdateInput',
        'ZonaUpdateInput' => 'App\GraphQL\Inputs\ZonaUpdateInput',
        'LocalizacaoUpdateInput' => 'App\GraphQL\Inputs\LocalizacaoUpdateInput',
        'ComandaUpdateInput' => 'App\GraphQL\Inputs\ComandaUpdateInput',
        'ViagemUpdateInput' => 'App\GraphQL\Inputs\ViagemUpdateInput',
        'IntegracaoUpdateInput' => 'App\GraphQL\Inputs\IntegracaoUpdateInput',
        'AssociacaoUpdateInput' => 'App\GraphQL\Inputs\AssociacaoUpdateInput',
        'PedidoUpdateInput' => 'App\GraphQL\Inputs\PedidoUpdateInput',
        'CategoriaUpdateInput' => 'App\GraphQL\Inputs\CategoriaUpdateInput',
        'UnidadeUpdateInput' => 'App\GraphQL\Inputs\UnidadeUpdateInput',
        'OrigemUpdateInput' => 'App\GraphQL\Inputs\OrigemUpdateInput',
        'OperacaoUpdateInput' => 'App\GraphQL\Inputs\OperacaoUpdateInput',
        'ImpostoUpdateInput' => 'App\GraphQL\Inputs\ImpostoUpdateInput',
        'TributacaoUpdateInput' => 'App\GraphQL\Inputs\TributacaoUpdateInput',
        'ProdutoUpdateInput' => 'App\GraphQL\Inputs\ProdutoUpdateInput',
        'ServicoUpdateInput' => 'App\GraphQL\Inputs\ServicoUpdateInput',
        'ClassificacaoUpdateInput' => 'App\GraphQL\Inputs\ClassificacaoUpdateInput',
        'ContaUpdateInput' => 'App\GraphQL\Inputs\ContaUpdateInput',
        'MovimentacaoUpdateInput' => 'App\GraphQL\Inputs\MovimentacaoUpdateInput',
        'CreditoUpdateInput' => 'App\GraphQL\Inputs\CreditoUpdateInput',
        'ChequeUpdateInput' => 'App\GraphQL\Inputs\ChequeUpdateInput',
        'PagamentoUpdateInput' => 'App\GraphQL\Inputs\PagamentoUpdateInput',
        'SubPagamentoUpdateInput' => 'App\GraphQL\Inputs\SubPagamentoUpdateInput',
        'ItemUpdateInput' => 'App\GraphQL\Inputs\ItemUpdateInput',
        'SubitemUpdateInput' => 'App\GraphQL\Inputs\SubitemUpdateInput',
        'ModuloUpdateInput' => 'App\GraphQL\Inputs\ModuloUpdateInput',
        'ComposicaoUpdateInput' => 'App\GraphQL\Inputs\ComposicaoUpdateInput',
        'ListaUpdateInput' => 'App\GraphQL\Inputs\ListaUpdateInput',
        'CompraUpdateInput' => 'App\GraphQL\Inputs\CompraUpdateInput',
        'RequisitoUpdateInput' => 'App\GraphQL\Inputs\RequisitoUpdateInput',
        'EstoqueUpdateInput' => 'App\GraphQL\Inputs\EstoqueUpdateInput',
        'GrupoUpdateInput' => 'App\GraphQL\Inputs\GrupoUpdateInput',
        'PropriedadeUpdateInput' => 'App\GraphQL\Inputs\PropriedadeUpdateInput',
        'PacoteUpdateInput' => 'App\GraphQL\Inputs\PacoteUpdateInput',
        'DispositivoUpdateInput' => 'App\GraphQL\Inputs\DispositivoUpdateInput',
        'ImpressoraUpdateInput' => 'App\GraphQL\Inputs\ImpressoraUpdateInput',
        'PromocaoUpdateInput' => 'App\GraphQL\Inputs\PromocaoUpdateInput',
        'AcessoUpdateInput' => 'App\GraphQL\Inputs\AcessoUpdateInput',
        'CatalogoUpdateInput' => 'App\GraphQL\Inputs\CatalogoUpdateInput',
        'SistemaUpdateInput' => 'App\GraphQL\Inputs\SistemaUpdateInput',
        'ResumoUpdateInput' => 'App\GraphQL\Inputs\ResumoUpdateInput',
        'FormacaoUpdateInput' => 'App\GraphQL\Inputs\FormacaoUpdateInput',
        'HorarioUpdateInput' => 'App\GraphQL\Inputs\HorarioUpdateInput',
        'JuncaoUpdateInput' => 'App\GraphQL\Inputs\JuncaoUpdateInput',
        'RegimeUpdateInput' => 'App\GraphQL\Inputs\RegimeUpdateInput',
        'EmitenteUpdateInput' => 'App\GraphQL\Inputs\EmitenteUpdateInput',
        'NotaUpdateInput' => 'App\GraphQL\Inputs\NotaUpdateInput',
        'EmpresaUpdateInput' => 'App\GraphQL\Inputs\EmpresaUpdateInput',
        'TelefoneUpdateInput' => 'App\GraphQL\Inputs\TelefoneUpdateInput',
        'ObservacaoUpdateInput' => 'App\GraphQL\Inputs\ObservacaoUpdateInput',
        'CupomUpdateInput' => 'App\GraphQL\Inputs\CupomUpdateInput',
        'MetricaUpdateInput' => 'App\GraphQL\Inputs\MetricaUpdateInput',
        'AvaliacaoUpdateInput' => 'App\GraphQL\Inputs\AvaliacaoUpdateInput',
        'SubAvaliacaoUpdateInput' => 'App\GraphQL\Inputs\SubAvaliacaoUpdateInput',
        'CozinhaUpdateInput' => 'App\GraphQL\Inputs\CozinhaUpdateInput',
        'CardapioUpdateInput' => 'App\GraphQL\Inputs\CardapioUpdateInput',
        'ConferenciaUpdateInput' => 'App\GraphQL\Inputs\ConferenciaUpdateInput',

        'Date' => 'App\GraphQL\Types\DateType',
        'DateTime' => 'App\GraphQL\Types\DateTimeType',

        'ClienteAuth' => 'App\GraphQL\Types\ClienteAuthType',
        'AuthBase' => 'App\GraphQL\Types\AuthBaseType',

        'Setor' => 'App\GraphQL\Types\SetorType',
        'Mesa' => 'App\GraphQL\Types\MesaType',
        'Sessao' => 'App\GraphQL\Types\SessaoType',
        'Banco' => 'App\GraphQL\Types\BancoType',
        'Carteira' => 'App\GraphQL\Types\CarteiraType',
        'Caixa' => 'App\GraphQL\Types\CaixaType',
        'Forma' => 'App\GraphQL\Types\FormaType',
        'Cartao' => 'App\GraphQL\Types\CartaoType',
        'Funcao' => 'App\GraphQL\Types\FuncaoType',
        'Cliente' => 'App\GraphQL\Types\ClienteType',
        'Prestador' => 'App\GraphQL\Types\PrestadorType',
        'Moeda' => 'App\GraphQL\Types\MoedaType',
        'Pais' => 'App\GraphQL\Types\PaisType',
        'Estado' => 'App\GraphQL\Types\EstadoType',
        'Cidade' => 'App\GraphQL\Types\CidadeType',
        'Bairro' => 'App\GraphQL\Types\BairroType',
        'Zona' => 'App\GraphQL\Types\ZonaType',
        'Localizacao' => 'App\GraphQL\Types\LocalizacaoType',
        'Comanda' => 'App\GraphQL\Types\ComandaType',
        'Viagem' => 'App\GraphQL\Types\ViagemType',
        'Integracao' => 'App\GraphQL\Types\IntegracaoType',
        'Associacao' => 'App\GraphQL\Types\AssociacaoType',
        'Pedido' => 'App\GraphQL\Types\PedidoType',
        'Categoria' => 'App\GraphQL\Types\CategoriaType',
        'Unidade' => 'App\GraphQL\Types\UnidadeType',
        'Origem' => 'App\GraphQL\Types\OrigemType',
        'Operacao' => 'App\GraphQL\Types\OperacaoType',
        'Imposto' => 'App\GraphQL\Types\ImpostoType',
        'Tributacao' => 'App\GraphQL\Types\TributacaoType',
        'Produto' => 'App\GraphQL\Types\ProdutoType',
        'Servico' => 'App\GraphQL\Types\ServicoType',
        'Classificacao' => 'App\GraphQL\Types\ClassificacaoType',
        'Conta' => 'App\GraphQL\Types\ContaType',
        'Movimentacao' => 'App\GraphQL\Types\MovimentacaoType',
        'Credito' => 'App\GraphQL\Types\CreditoType',
        'Cheque' => 'App\GraphQL\Types\ChequeType',
        'Pagamento' => 'App\GraphQL\Types\PagamentoType',
        'Item' => 'App\GraphQL\Types\ItemType',
        'Modulo' => 'App\GraphQL\Types\ModuloType',
        'Funcionalidade' => 'App\GraphQL\Types\FuncionalidadeType',
        'Permissao' => 'App\GraphQL\Types\PermissaoType',
        'Auditoria' => 'App\GraphQL\Types\AuditoriaType',
        'Composicao' => 'App\GraphQL\Types\ComposicaoType',
        'Lista' => 'App\GraphQL\Types\ListaType',
        'Compra' => 'App\GraphQL\Types\CompraType',
        'Requisito' => 'App\GraphQL\Types\RequisitoType',
        'Estoque' => 'App\GraphQL\Types\EstoqueType',
        'Grupo' => 'App\GraphQL\Types\GrupoType',
        'Propriedade' => 'App\GraphQL\Types\PropriedadeType',
        'Pacote' => 'App\GraphQL\Types\PacoteType',
        'Dispositivo' => 'App\GraphQL\Types\DispositivoType',
        'Impressora' => 'App\GraphQL\Types\ImpressoraType',
        'Promocao' => 'App\GraphQL\Types\PromocaoType',
        'Acesso' => 'App\GraphQL\Types\AcessoType',
        'Catalogo' => 'App\GraphQL\Types\CatalogoType',
        'Sistema' => 'App\GraphQL\Types\SistemaType',
        'Resumo' => 'App\GraphQL\Types\ResumoType',
        'Formacao' => 'App\GraphQL\Types\FormacaoType',
        'Horario' => 'App\GraphQL\Types\HorarioType',
        'Juncao' => 'App\GraphQL\Types\JuncaoType',
        'Regime' => 'App\GraphQL\Types\RegimeType',
        'Emitente' => 'App\GraphQL\Types\EmitenteType',
        'Nota' => 'App\GraphQL\Types\NotaType',
        'Evento' => 'App\GraphQL\Types\EventoType',
        'Empresa' => 'App\GraphQL\Types\EmpresaType',
        'Pontuacao' => 'App\GraphQL\Types\PontuacaoType',
        'Telefone' => 'App\GraphQL\Types\TelefoneType',
        'Observacao' => 'App\GraphQL\Types\ObservacaoType',
        'Cupom' => 'App\GraphQL\Types\CupomType',
        'Metrica' => 'App\GraphQL\Types\MetricaType',
        'Avaliacao' => 'App\GraphQL\Types\AvaliacaoType',
        'Cozinha' => 'App\GraphQL\Types\CozinhaType',
        'Cardapio' => 'App\GraphQL\Types\CardapioType',
        'Contagem' => 'App\GraphQL\Types\ContagemType',
        'Notificacao' => 'App\GraphQL\Types\NotificacaoType',
        'Saldo' => 'App\GraphQL\Types\SaldoType',
        'Conferencia' => 'App\GraphQL\Types\ConferenciaType',

        'ClienteVerify' => 'App\GraphQL\Types\ClienteVerifyType',
        'PedidoSummary' => 'App\GraphQL\Types\PedidoSummaryType',
        'Usuario' => 'App\GraphQL\Types\UsuarioType',
        'ItemKitchen' => 'App\GraphQL\Types\ItemKitchenType',
        'DispositivoCreation' => 'App\GraphQL\Types\DispositivoCreationType',

        'CarteiraTipo' => 'App\GraphQL\Enums\CarteiraTipoEnum',
        'CarteiraAmbiente' => 'App\GraphQL\Enums\CarteiraAmbienteEnum',
        'FormaTipo' => 'App\GraphQL\Enums\FormaTipoEnum',
        'ClienteTipo' => 'App\GraphQL\Enums\ClienteTipoEnum',
        'ClienteGenero' => 'App\GraphQL\Enums\ClienteGeneroEnum',
        'ClienteStatus' => 'App\GraphQL\Enums\ClienteStatusEnum',
        'PrestadorVinculo' => 'App\GraphQL\Enums\PrestadorVinculoEnum',
        'LocalizacaoTipo' => 'App\GraphQL\Enums\LocalizacaoTipoEnum',
        'AssociacaoStatus' => 'App\GraphQL\Enums\AssociacaoStatusEnum',
        'PedidoTipo' => 'App\GraphQL\Enums\PedidoTipoEnum',
        'PedidoEstado' => 'App\GraphQL\Enums\PedidoEstadoEnum',
        'ImpostoGrupo' => 'App\GraphQL\Enums\ImpostoGrupoEnum',
        'ProdutoTipo' => 'App\GraphQL\Enums\ProdutoTipoEnum',
        'ServicoTipo' => 'App\GraphQL\Enums\ServicoTipoEnum',
        'ContaTipo' => 'App\GraphQL\Enums\ContaTipoEnum',
        'ContaFonte' => 'App\GraphQL\Enums\ContaFonteEnum',
        'ContaModo' => 'App\GraphQL\Enums\ContaModoEnum',
        'ContaFormula' => 'App\GraphQL\Enums\ContaFormulaEnum',
        'ContaEstado' => 'App\GraphQL\Enums\ContaEstadoEnum',
        'PagamentoEstado' => 'App\GraphQL\Enums\PagamentoEstadoEnum',
        'ItemEstado' => 'App\GraphQL\Enums\ItemEstadoEnum',
        'AuditoriaTipo' => 'App\GraphQL\Enums\AuditoriaTipoEnum',
        'AuditoriaPrioridade' => 'App\GraphQL\Enums\AuditoriaPrioridadeEnum',
        'ComposicaoTipo' => 'App\GraphQL\Enums\ComposicaoTipoEnum',
        'ListaEstado' => 'App\GraphQL\Enums\ListaEstadoEnum',
        'GrupoTipo' => 'App\GraphQL\Enums\GrupoTipoEnum',
        'GrupoFuncao' => 'App\GraphQL\Enums\GrupoFuncaoEnum',
        'DispositivoTipo' => 'App\GraphQL\Enums\DispositivoTipoEnum',
        'ImpressoraModo' => 'App\GraphQL\Enums\ImpressoraModoEnum',
        'PromocaoLocal' => 'App\GraphQL\Enums\PromocaoLocalEnum',
        'PromocaoFuncaoVendas' => 'App\GraphQL\Enums\PromocaoFuncaoVendasEnum',
        'PromocaoFuncaoCliente' => 'App\GraphQL\Enums\PromocaoFuncaoClienteEnum',
        'HorarioModo' => 'App\GraphQL\Enums\HorarioModoEnum',
        'JuncaoEstado' => 'App\GraphQL\Enums\JuncaoEstadoEnum',
        'EmitenteAmbiente' => 'App\GraphQL\Enums\EmitenteAmbienteEnum',
        'NotaTipo' => 'App\GraphQL\Enums\NotaTipoEnum',
        'NotaAmbiente' => 'App\GraphQL\Enums\NotaAmbienteEnum',
        'NotaAcao' => 'App\GraphQL\Enums\NotaAcaoEnum',
        'NotaEstado' => 'App\GraphQL\Enums\NotaEstadoEnum',
        'EventoEstado' => 'App\GraphQL\Enums\EventoEstadoEnum',
        'CupomTipoDesconto' => 'App\GraphQL\Enums\CupomTipoDescontoEnum',
        'CupomFuncaoPedidos' => 'App\GraphQL\Enums\CupomFuncaoPedidosEnum',
        'CupomFuncaoValor' => 'App\GraphQL\Enums\CupomFuncaoValorEnum',
        'MetricaTipo' => 'App\GraphQL\Enums\MetricaTipoEnum',
        'CardapioLocal' => 'App\GraphQL\Enums\CardapioLocalEnum',
        'IntegracaoTipo' => 'App\GraphQL\Enums\IntegracaoTipoEnum',
    ],

    // The types will be loaded on demand. Default is to load all types on each request
    // Can increase performance on schemes with many types
    // Presupposes the config type key to match the type class name property
    'lazyload_types' => true,

    // This callable will be passed the Error object for each errors GraphQL catch.
    // The method should return an array representing the error.
    // Typically:
    // [
    //     'message' => '',
    //     'locations' => []
    // ]
    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

    /*
     * Custom Error Handling
     *
     * Expected handler signature is: function (array $errors, callable $formatter): array
     *
     * The default handler will pass exceptions to laravel Error Handling mechanism
     */
    'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],

    // You can set the key, which will be used to retrieve the dynamic variables
    'params_key'    => 'variables',

    /*
     * Options to limit the query complexity and depth. See the doc
     * @ https://github.com/webonyx/graphql-php#security
     * for details. Disabled by default.
     */
    'security' => [
        'query_max_complexity'  => null,
        'query_max_depth'       => null,
        'disable_introspection' => false,
    ],

    /*
     * You can define your own pagination type.
     * Reference \Rebing\GraphQL\Support\PaginationType::class
     */
    'pagination_type' => \Rebing\GraphQL\Support\PaginationType::class,

    /*
     * Config for GraphiQL (see (https://github.com/graphql/graphiql).
     */
    'graphiql' => [
        'prefix'     => '/graphiql',
        'controller' => \Rebing\GraphQL\GraphQLController::class . '@graphiql',
        'middleware' => [],
        'view'       => 'graphql::graphiql',
        'display'    => env('ENABLE_GRAPHIQL', false),
    ],

    /*
     * Overrides the default field resolver
     * See http://webonyx.github.io/graphql-php/data-fetching/#default-field-resolver
     *
     * Example:
     *
     * ```php
     * 'defaultFieldResolver' => function ($root, $args, $context, $info) {
     * },
     * ```
     * or
     * ```php
     * 'defaultFieldResolver' => [SomeKlass::class, 'someMethod'],
     * ```
     */
    'defaultFieldResolver' => null,

    /*
     * Any headers that will be added to the response returned by the default controller
     */
    'headers' => [],

    /*
     * Any JSON encoding options when returning a response from the default controller
     * See http://php.net/manual/function.json-encode.php for the full list of options
     */
    'json_encoding_options' => 0,
];
