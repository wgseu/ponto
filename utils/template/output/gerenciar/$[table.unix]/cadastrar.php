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
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(PermissaoNome::$[TABLE.style], isset($_GET['saida']) && $_GET['saida'] == 'json');
$focusctrl = '$[descriptor]';
$errors = array();
if ($_POST) {
    $$[table.unix] = new \Z$[tAble.norm]($_POST);
    try {
        $$[table.unix]->set$[pRimary.norm](null);
$[field.each]
$[field.if(date)]
        $$[table.unix]->set$[fIeld.norm](\MZ\Util\Filter::date($$[table.unix]->get$[fIeld.norm]()));
$[field.else.if(time)]
        $$[table.unix]->set$[fIeld.norm](\MZ\Util\Filter::time($$[table.unix]->get$[fIeld.norm]()));
$[field.else.if(datetime)]
        $$[table.unix]->set$[fIeld.norm](\MZ\Util\Filter::datetime($$[table.unix]->get$[fIeld.norm]()));
$[field.else.if(currency)]
        $$[table.unix]->set$[fIeld.norm](\MZ\Util\Filter::money($$[table.unix]->get$[fIeld.norm]()));
$[field.else.if(float)]
        $$[table.unix]->set$[fIeld.norm](\MZ\Util\Filter::money($$[table.unix]->get$[fIeld.norm]()));
$[field.else.if(integer)]
        $$[table.unix]->set$[fIeld.norm](\MZ\Util\Filter::number($$[table.unix]->get$[fIeld.norm]()));
$[field.else.if(masked)]
        $$[table.unix]->set$[fIeld.norm](\MZ\Util\Filter::unmask($$[table.unix]->get$[fIeld.norm](), '$[field.mask]'));
$[field.else.if(image)]
        $$[field.unix] = upload_image('raw_$[field]', '$[field.image.folder]');
        $$[table.unix]->set$[fIeld.norm]($$[field.unix]);
$[field.else.if(blob)]
        $$[field.unix] = upload_image('raw_$[field]', '$[field.image.folder]');
        if (!is_null($$[field.unix])) {
            $$[table.unix]->set$[fIeld.norm](file_get_contents(WWW_ROOT . get_image_url($$[field.unix], '$[field.image.folder]')));
            unlink(WWW_ROOT . get_image_url($$[field.unix], '$[field.image.folder]'));
        } else {
            $$[table.unix]->set$[fIeld.norm](null);
        }
$[field.end]
$[field.end]
        $$[table.unix] = \Z$[tAble.norm]::cadastrar($$[table.unix]);
        $msg = '$[tAble.name] "'.$$[table.unix]->get$[dEscriptor.norm]().'" cadastrad$[table.gender] com sucesso!';
        if (isset($_GET['saida']) && $_GET['saida'] == 'json') {
            json(null, array('item' => $$[table.unix]->toArray(), 'msg' => $msg));
        }
        \Thunder::success($msg, true);
        redirect('/gerenciar/$[table.unix]/');
    } catch (\ValidationException $e) {
        $errors = $e->getErrors();
    } catch (\Exception $e) {
        $errors['unknow'] = $e->getMessage();
    }
$[field.each]
$[field.if(image)]
    // remove $[field.gender] $[field.name] enviad$[field.gender]
    if (!is_null($$[table.unix]->get$[fIeld.norm]())) {
        unlink(WWW_ROOT . get_image_url($$[table.unix]->get$[fIeld.norm](), '$[field.image.folder]'));
    }
    $$[table.unix]->set$[fIeld.norm](null);
$[field.else.if(blob)]
    // remove $[field.gender] $[field.name] enviad$[field.gender]
    $$[table.unix]->set$[fIeld.norm](null);
$[field.end]
$[field.end]
    foreach ($errors as $key => $value) {
        $focusctrl = $key;
        if (isset($_GET['saida']) && $_GET['saida'] == 'json') {
            json($value, null, array('field' => $focusctrl));
        }
        \Thunder::error($value);
        break;
    }
} else {
    $$[table.unix] = new \Z$[tAble.norm]();
$[field.each]
$[field.if(date)]
    $$[table.unix]->set$[fIeld.norm](date('Y-m-d', time()));
$[field.else.if(time)]
    $$[table.unix]->set$[fIeld.norm](date('H:i:s', time()));
$[field.else.if(datetime)]
    $$[table.unix]->set$[fIeld.norm](date('Y-m-d H:i:s', time()));
$[field.end]
$[field.end]
}
if (isset($_GET['saida']) && $_GET['saida'] == 'json') {
    json('Nenhum dado foi enviado');
}

$[field.each(reference)]
$[field.if(searchable)]
$$[field.unix]_obj = new \Z$[rEference.norm]();
$[field.else]
$_$[reference.unix.plural] = \Z$[rEference.norm]::getTod$[rEference.gender]s();
$[field.end]
$[field.end]
include template('gerenciar_$[table.unix]_cadastrar');
