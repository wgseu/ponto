<?php
/*
    Copyright 2016 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
    Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
    O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
    DISPOSIÇÕES GERAIS
    O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
    ou outros avisos ou restrições de propriedade do GrandChef.

    O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
    ou descompilação do GrandChef.

    PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

    GrandChef é a especialidade do desenvolvedor e seus
    licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
    de leis de propriedade.

    O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
    direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
require_once(dirname(__DIR__) . '/app.php');

use $[Table.package]\$[Table.norm];

need_permission(\PermissaoNome::$[TABLE.style], is_output('json'));
$$[primary.unix] = isset($_GET['$[primary.unix]'])?$_GET['$[primary.unix]']:null;
$$[table.unix] = $[Table.norm]::findBy$[Primary.norm]($$[primary.unix]);
if (!$$[table.unix]->exists()) {
    $msg = 'Não existe $[Table.name] com $[primary.gender] $[Primary.name] informado!';
    if (is_output('json')) {
        json($msg);
    }
    \Thunder::warning($msg);
    redirect('/gerenciar/$[table.unix]/');
}
$focusctrl = '$[descriptor]';
$errors = [];
$old_$[table.unix] = $$[table.unix];
if (is_post()) {
    $$[table.unix] = new $[Table.norm]($_POST);
    try {
        $$[table.unix]->filter($old_$[table.unix]);
        $$[table.unix]->save();
        $old_$[table.unix]->clean($$[table.unix]);
        $msg = sprintf(
            '$[Table.name] "%s" atualizad$[table.gender] com sucesso!',
            $$[table.unix]->get$[Descriptor.norm]()
        );
        if (is_output('json')) {
            json(null, ['item' => $$[table.unix]->publish(), 'msg' => $msg]);
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/$[table.unix]/');
    } catch (\Exception $e) {
        $$[table.unix]->clean($old_$[table.unix]);
        if ($e instanceof \MZ\Exception\ValidationException) {
            $errors = $e->getErrors();
        }
        if (is_output('json')) {
            json($e->getMessage(), null, ['errors' => $errors]);
        }
        \Thunder::error($e->getMessage());
        foreach ($errors as $key => $value) {
            $focusctrl = $key;
            break;
        }
    }
} elseif (is_output('json')) {
    json('Nenhum dado foi enviado');
}
$[field.each(reference)]
$[field.if(searchable)]
$$[field.unix]_obj = $$[table.unix]->find$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]);
$[field.else]
$$[reference.unix.plural] = \$[Reference.package]\$[Reference.norm]::findAll();
$[field.end]
$[field.end]
include template('gerenciar_$[table.unix]_editar');
