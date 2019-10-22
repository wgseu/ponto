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

namespace MZ\Exception;

class UploadExceptionTest extends \MZ\Framework\TestCase
{
    public function testCreate()
    {
        $unknow = 'Unknown upload error';
        $e = new UploadException(UPLOAD_ERR_INI_SIZE);
        $this->assertNotEquals($unknow, $e->getMessage());
        $e = new UploadException(UPLOAD_ERR_FORM_SIZE);
        $this->assertNotEquals($unknow, $e->getMessage());
        $e = new UploadException(UPLOAD_ERR_PARTIAL);
        $this->assertNotEquals($unknow, $e->getMessage());
        $e = new UploadException(UPLOAD_ERR_NO_FILE);
        $this->assertNotEquals($unknow, $e->getMessage());
        $e = new UploadException(UPLOAD_ERR_NO_TMP_DIR);
        $this->assertNotEquals($unknow, $e->getMessage());
        $e = new UploadException(UPLOAD_ERR_CANT_WRITE);
        $this->assertNotEquals($unknow, $e->getMessage());
        $e = new UploadException(UPLOAD_ERR_EXTENSION);
        $this->assertNotEquals($unknow, $e->getMessage());
        $e = new UploadException(200);
        $this->assertEquals($unknow, $e->getMessage());
    }
}
