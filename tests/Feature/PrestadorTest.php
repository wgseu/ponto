<?php

/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */

namespace Tests\Feature;

use App\Models\Acesso;
use Tests\TestCase;
use App\Models\Funcao;
use App\Models\Cliente;
use App\Models\Permissao;
use App\Models\Prestador;

class PrestadorTest extends TestCase
{
    /**
     * Obtém os headers de autenticação do prestador de serviço
     *
     * @param Prestador $prestador opcional
     * @param string[] $permissoes lista de permissões
     * @return array
     */
    public static function auth($prestador = null, $permissoes = [])
    {
        $prestador = $prestador ?: factory(Prestador::class)->create();
        $funcao = $prestador->funcao;
        foreach ($permissoes as $nome) {
            $permissao = Permissao::where('nome', $nome)->firstOrFail();
            (new Acesso([
                'permissao_id' => $permissao->id,
                'funcao_id' => $funcao->id
            ]))->save();
        }
        $user = $prestador->cliente()->first();
        return ClienteTest::auth($user);
    }

    /**
     * Obtém os headers de autenticação do proprietárop
     *
     * @param Cliente $cliente opcional
     * @return array
     */
    public static function authOwner($cliente = null)
    {
        $prestador = EmpresaTest::createOwner($cliente)->prestador;
        return self::auth($prestador);
    }

    public function testCreatePrestador()
    {
        $headers = PrestadorTest::authOwner();
        $funcao_id = factory(Funcao::class)->create();
        $cliente_id = factory(Cliente::class)->create();
        $response = $this->graphfl('create_prestador', [
            'input' => [
                'codigo' => 'Teste',
                'funcao_id' => $funcao_id->id,
                'cliente_id' => $cliente_id->id,
            ]
        ], $headers);

        $found_prestador = Prestador::findOrFail($response->json('data.CreatePrestador.id'));
        $this->assertEquals('Teste', $found_prestador->codigo);
        $this->assertEquals($response->json('data.CreatePrestador.funcao_id'), $found_prestador->funcao_id);
        $this->assertEquals($response->json('data.CreatePrestador.cliente_id'), $found_prestador->cliente_id);
    }

    public function testUpdatePrestador()
    {
        $headers = PrestadorTest::authOwner();
        $prestador = factory(Prestador::class)->create();
        $this->graphfl('update_prestador', [
            'id' => $prestador->id,
            'input' => [
                'codigo' => 'Atualizou',
            ]
        ], $headers);
        $prestador->refresh();
        $this->assertEquals('Atualizou', $prestador->codigo);
    }

    public function testDeletePrestador()
    {
        $headers = PrestadorTest::authOwner();
        $prestador_to_delete = factory(Prestador::class)->create();
        $this->graphfl('delete_prestador', ['id' => $prestador_to_delete->id], $headers);
        $prestador = Prestador::find($prestador_to_delete->id);
        $this->assertNull($prestador);
    }

    public function testQueryPrestador()
    {
        $headers = PrestadorTest::authOwner();
        $prestador = factory(Prestador::class)->create();
        $response = $this->graphfl('query_prestador', ['id' => $prestador->id], $headers);
        $this->assertEquals($prestador->id, $response->json('data.prestadores.data.0.id'));
    }

    public function testNotUserLogin()
    {
        $funcao_id = factory(Funcao::class)->create();
        $cliente_id = factory(Cliente::class)->create([
            'login' => ''
        ]);
        $cliente_id->save();
        $headers = PrestadorTest::authOwner();
        $this->expectException('Exception');
        $this->graphfl('create_prestador', [
            'input' => [
                'codigo' => 'Teste',
                'funcao_id' => $funcao_id->id,
                'cliente_id' => $cliente_id->id,
            ]
        ], $headers);
    }

    public function testUserNotTypeFisical()
    {
        $funcao_id = factory(Funcao::class)->create();
        $cliente_id = factory(Cliente::class)->create();
        $cliente_id->tipo = Cliente::TIPO_JURIDICA;
        $cliente_id->cpf = '54557802000126';
        $cliente_id->save();
        $headers = PrestadorTest::authOwner();
        $this->expectException('Exception');
        $this->graphfl('create_prestador', [
            'input' => [
                'codigo' => 'Teste',
                'funcao_id' => $funcao_id->id,
                'cliente_id' => $cliente_id->id,
            ]
        ], $headers);
    }

    public function testScoreNegative()
    {
        $prestador = factory(Prestador::class);
        $prestador->pontuacao = -2;
        $this->expectException('Exception');
        $prestador->save();
    }

    public function testCommissionNegative()
    {
        $prestador = factory(Prestador::class);
        $prestador->porcentagem = -2;
        $this->expectException('Exception');
        $prestador->save();
    }

    public function testRemunerationNegative()
    {
        $prestador = factory(Prestador::class);
        $prestador->remuneracao = -2;
        $this->expectException('Exception');
        $prestador->save();
    }
}
