<?php

namespace Tests\Feature;

use App\Models\Classificacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassificacaoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateClassificacao()
    {
        $headers = PrestadorTest::auth();
        factory(Classificacao::class)->create();
        $credito = $this->graphfl('create_classificacao', [
            "ClassificacaoInput" => [
                'descricao' => "Pagamento de fornecedores",
            ]
        ], $headers);
        $this->assertEquals("Pagamento de fornecedores", $credito->json("data.CreateClassificacao.descricao"));
    }

    public function testFindClassificacao()
    {
        $headers = PrestadorTest::auth();
        $classificacao = factory(Classificacao::class)->create();
        $response = $this->graphfl('find_classificacao_id',[
            "ID" => $classificacao->id,
        ], $headers);

        $this->assertEquals(
            $classificacao->descricao,
            $response->json('data.classificacoes.data.0.descricao')
        );
    }

    public function testUpdateClassificacao()
    {
        $headers = PrestadorTest::auth();
        $classificacao = factory(Classificacao::class)->create();
        $response = $this->graphfl('update_classificacao', [
            "ID" => $classificacao->id,
            "ClassificacaoUpdateInput" => [
                "descricao" => "Diárias",
              ]
        ], $headers);
        $classificacao->refresh();
        $this->assertEquals(
            $classificacao->descricao,
            $response->json('data.UpdateClassificacao.descricao')
        );
    }
    
    public function testDeleteCredito()
    {
        $headers = PrestadorTest::auth();
        $classificacao = factory(Classificacao::class)->create();
        $response = $this->graphfl('delete_classificacao', [
            "ID" => $classificacao->id
        ], $headers);
        $this->assertEquals(
            $classificacao->id,
            $response->json("data.DeleteClassificacao.id")
        );
    }
}
