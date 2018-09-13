<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
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
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
namespace MZ\System;

class PaginaTest extends \MZ\Framework\TestCase
{
    public function testFromArray()
    {
        $old_pagina = new Pagina([
            'id' => 123,
            'nome' => Pagina::NOME_SOBRE,
            'linguagemid' => 123,
            'conteudo' => 'Página',
        ]);
        $pagina = new Pagina();
        $pagina->fromArray($old_pagina);
        $this->assertEquals($pagina, $old_pagina);
        $pagina->fromArray(null);
        $this->assertEquals($pagina, new Pagina());
    }

    public function testFilter()
    {
        $old_pagina = new Pagina([
            'id' => 1234,
            'nome' => Pagina::NOME_SOBRE,
            'linguagemid' => 1234,
            'conteudo' => ' Página <script>filter</script> ',
        ]);
        $pagina = new Pagina([
            'id' => 321,
            'nome' => ' <script>'.Pagina::NOME_SOBRE.'</script> ',
            'linguagemid' => '1.234',
            'conteudo' => ' Página <script>filter</script> ',
        ]);
        $pagina->filter($old_pagina);
        $this->assertEquals($old_pagina, $pagina);
    }

    public function testPublish()
    {
        $pagina = new Pagina();
        $values = $pagina->publish();
        $allowed = [
            'id',
            'nome',
            'linguagemid',
            'conteudo',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $pagina = new Pagina();
        try {
            $pagina->insert();
            $this->fail('Não deveria ter cadastrado a página');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'nome',
                    'linguagemid',
                ],
                array_keys($e->getErrors())
            );
        }
        $pagina->setNome(Pagina::NOME_TERMOS);
        $pagina->setLinguagemID(1046);
        $pagina->insert();
    }

    public function testUpdate()
    {
        $pagina = new Pagina();
        $pagina->setNome(Pagina::NOME_PRIVACIDADE);
        $pagina->setLinguagemID(1033);
        $pagina->insert();
        $pagina->setNome(Pagina::NOME_TERMOS);
        $pagina->setLinguagemID(1034);
        $pagina->setConteudo('Página updated');
        $pagina->update();
        $found_pagina = Pagina::findByID($pagina->getID());
        $this->assertEquals($pagina, $found_pagina);
        $pagina->setID('');
        $this->expectException('\Exception');
        $pagina->update();
    }

    public function testDelete()
    {
        $pagina = new Pagina();
        $pagina->setNome(Pagina::NOME_PRIVACIDADE);
        $pagina->setLinguagemID(1046);
        $pagina->insert();
        $pagina->delete();
        $pagina->clean(new Pagina());
        $found_pagina = Pagina::findByID($pagina->getID());
        $this->assertEquals(new Pagina(), $found_pagina);
        $pagina->setID('');
        $this->expectException('\Exception');
        $pagina->delete();
    }

    public function testFind()
    {
        $pagina = new Pagina();
        $pagina->setNome(Pagina::NOME_PRIVACIDADE);
        $pagina->setLinguagemID(1046);
        $pagina->insert();
        $found_pagina = Pagina::find(['id' => $pagina->getID()]);
        $this->assertEquals($pagina, $found_pagina);
        $found_pagina = Pagina::findByID($pagina->getID());
        $this->assertEquals($pagina, $found_pagina);
        $found_pagina->loadByID($pagina->getID());
        $this->assertEquals($pagina, $found_pagina);
        $found_pagina = Pagina::findByNomeLinguagemID($pagina->getNome(), $pagina->getLinguagemID());
        $this->assertEquals($pagina, $found_pagina);
        $found_pagina->loadByNomeLinguagemID($pagina->getNome(), $pagina->getLinguagemID());
        $this->assertEquals($pagina, $found_pagina);

        $pagina_sec = new Pagina();
        $pagina_sec->setNome(Pagina::NOME_SOBRE);
        $pagina_sec->setLinguagemID(1033);
        $pagina_sec->insert();

        $paginas = Pagina::findAll(['search' => 'r%e'], [], 2, 0);
        $this->assertEquals([$pagina, $pagina_sec], $paginas);

        $count = Pagina::count(['search' => 'r%e']);
        $this->assertEquals(2, $count);
    }
}
