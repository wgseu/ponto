<?php
/**
 * Copyright (c) 2016 MZ Desenvolvimento de Sistemas LTDA - All Rights Reserved
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Service;

use $[Table.package]\$[Table.norm];

/**
 * Allow application to serve system resources
 */
class $[Table.norm]Service extends \MZ\Core\Service
{
    /**
     * Show $[Table.name] page
     * @param  Request $request request object
     */
    public function main($request)
    {
        $this->getApplication()->needLogin();
        $response = $this->getResponse();
        if ($response instanceof \MZ\Response\JsonResponse) {
            $response->success([]);
        } else {
            $response->setTitle(_t('$[table.unix].title'));
            $response->output('$[table.unix.plural]_index');
        }
    }

    /**
     * Show all $[Table.name.plural]
     * @param  Request $request request object
     */
    public function view($request)
    {
        $this->getApplication()->needManager();
        $limite = isset($_GET['limite']) && is_numeric($_GET['limite'])?intval($_GET['limite']):10;
        if ($limite < 1 || $limite > 100) {
            $limite = 10;
        }
        $$[table.unix] = new $[Table.norm]($_GET);
        $condition = \MZ\Util\Filter::query($_GET);
        $order = isset($_GET['ordem'])?$_GET['ordem']:'';
        $count = $[Table.norm]::count($condition);
        list($pagesize, $offset, $pagestring, $pagination, $pagecount) = pagestring($count, $limite);
        $$[table.unix.plural] = $[Table.norm]::findAll($condition, $order, $pagesize, $offset);
        $response = $this->getResponse();
        if ($response instanceof \MZ\Response\JsonResponse) {
            $_$[table.unix.plural] = [];
            foreach ($$[table.unix.plural] as $_$[table.unix]) {
                $_$[table.unix.plural][] = $_$[table.unix]->publish();
            }
            $response->success(['itens' => $_$[table.unix.plural], 'paginas' => $pagecount]);
        } else {
$[field.each(all)]
$[field.if(enum)]
            $response->getEngine()->$[field.unix]_options = $[Table.norm]::get$[Field.norm]Options();
$[field.else.if(reference)]
$[field.if(searchable)]
            $response->getEngine()->$[field.unix]_obj = $$[table.unix]->find$[Field.norm]();
$[field.end]
$[field.end]
$[field.end]
            $response->getEngine()->pagestring = $pagestring;
            $response->getEngine()->$[table.unix] = $$[table.unix];
            $response->getEngine()->$[table.unix.plural] = $$[table.unix.plural];
            $response->setTitle(_t('$[table.unix].title'));
            $response->output('gerenciar_$[table.unix.plural]_index');
        }
    }

    /**
     * Update a existing $[Table.name] or create a new one
     * @param  Request $request request object
     */
    public function save($request)
    {
        $this->getApplication()->needManager();
        $response = $this->getResponse();
        if ($request->action == 'editar') {
            $$[table.unix] = self::findBy$[Primary.norm]($request);
        } else {
            $$[table.unix] = $[Table.norm]::findBy$[Primary.norm]($request->param('$[table.pk.unix]'));
$[field.each(all)]
$[field.if(blob|image|primary)]
            $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]null);
$[field.else.if(date)]
            $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]\MZ\Database\Helper::date());
$[field.else.if(datetime)]
            $$[table.unix]->set$[Field.norm]($[field.if(array)]$[field.array.number], $[field.end]\MZ\Database\Helper::now());
$[field.end]
$[field.end]
        }
        $errors = [];
        $focusctrl = '$[table.desc]';
        $old_$[table.unix] = $$[table.unix];
        if ($request->method('POST')) {
            $$[table.unix] = new $[Table.norm]($_POST);
            try {
                $$[table.unix]->filter($old_$[table.unix]);
                $$[table.unix]->save();
                $old_$[table.unix]->clean($$[table.unix]);
                if ($request->action == 'editar') {
                    $message = _t('$[table.unix].updated', $$[table.unix]->get$[Table.desc.norm]());
                } else {
                    $message = _t('$[table.unix].registered', $$[table.unix]->get$[Table.desc.norm]());
                }
                if ($response instanceof \MZ\Response\JsonResponse) {
                    $response->success(['item' => $$[table.unix]->publish()], $message);
                } else {
                    \Thunder::success($message, true);
                    $response->redirect('/gerenciar/$[table.unix.plural]/');
                }
                return;
            } catch (\Exception $e) {
                $$[table.unix]->clean($old_$[table.unix]);
                if ($e instanceof \MZ\Exception\ValidationException) {
                    $errors = $e->getErrors();
                }
                if ($response instanceof \MZ\Response\JsonResponse) {
                    $response->error($e->getMessage(), $e->getCode(), $errors);
                    return;
                } else {
                    \Thunder::error($e->getMessage());
                    foreach ($errors as $key => $value) {
                        $focusctrl = $key;
                        break;
                    }
                }
                $response->getProcessor()->code(400);
            }
        } elseif ($response instanceof \MZ\Response\JsonResponse) {
            $response->error(_t('none.data.sent'), 400);
            return;
        }
$[field.each(reference)]
$[field.if(searchable)]
        $response->getEngine()->$[field.unix]_obj = $$[table.unix]->find$[Field.norm]($[field.if(array)]$[field.array.number]$[field.end]);
$[field.else]
        $_$[reference.unix.plural] = \$[Reference.package]\$[Reference.norm]::findAll();
        $response->getEngine()->_$[reference.unix.plural] = $_$[reference.unix.plural];
$[field.end]
$[field.end]
        $response->getEngine()->errors = $errors;
        $response->getEngine()->focusctrl = $focusctrl;
        $response->getEngine()->old_$[table.unix] = $old_$[table.unix];
        $response->getEngine()->$[table.unix] = $$[table.unix];
        if ($request->action == 'editar') {
            $response->setTitle(_t('$[table.unix].edit.title'));
            $response->output('gerenciar_$[table.unix.plural]_editar');
        } else {
            $response->setTitle(_t('$[table.unix].register.title'));
            $response->output('gerenciar_$[table.unix.plural]_cadastrar');
        }
    }

    /**
     * Delete existing $[Table.name]
     * @param  Request $request request object
     */
    public function delete($request)
    {
        $this->getApplication()->needManager();
        $response = $this->getResponse();
        $$[table.unix] = self::findBy$[Primary.norm]($request);
        try {
            $$[table.unix]->delete();
            $$[table.unix]->clean(new $[Table.norm]());
            $message = _t('$[table.unix].deleted', $$[table.unix]->get$[Table.desc.norm]());
            if ($response instanceof \MZ\Response\JsonResponse) {
                $response->success([], $message);
            } else {
                \Thunder::success($message, true);
                $response->redirect('/gerenciar/$[table.unix.plural]/');
            }
        } catch (\Exception $e) {
            throw new \MZ\Exception\RedirectException(
                _t('$[table.unix].cannot.delete', $$[table.unix]->get$[Table.desc.norm](), $e->getMessage()),
                $e->getCode(),
                '/gerenciar/$[table.unix.plural]/'
            );
        }
    }
$[table.each(unique)]

    /**
     * Find existing $[Table.name] by $[unique.each(all)]$[field.if(first)]$[field.else], $[field.end]$[Field.norm]$[unique.end]

     * @param  Request $request request object
     * @param  string $redirect redirect URL when $[Table.norm] don't exists
     * @param  array $params override default param name
     */
    public static function findBy$[unique.each(all)]$[Field.norm]$[unique.end]($request, $redirect = null, $params = [])
    {
$[unique.each(all)]
        $$[field.unix] = $request->param(isset($params['$[field.unix]'])?$params['$[field.unix]']:'$[field.unix]');
$[unique.end]
        $$[table.unix] = $[Table.norm]::findBy$[unique.each(all)]$[Field.norm]$[unique.end]($[unique.each(all)]$[field.if(first)]$[field.else], $[field.end]$$[field.unix]$[unique.end]);
        if (!$$[table.unix]->exists()) {
            throw new \MZ\Exception\RedirectException(
                _t('$[table.unix].$[unique.each(all)]$[field.unix].$[unique.end]not.found'$[unique.each(all)], $$[field.unix]$[unique.end]),
                404,
                is_null($redirect)?'/gerenciar/$[table.unix.plural]/':$redirect
            );
        }
        return $$[table.unix];
    }
$[table.end]

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'url' => '/$[table.unix.plural]/',
                'method' => 'GET',
                'callback' => self::class.'@main',
            ],
            [
                'url' => '/gerenciar/$[table.unix.plural]/',
                'method' => 'GET',
                'callback' => self::class.'@view',
            ],
            [
                'url' => '/gerenciar/$[table.unix.plural]/[cadastrar|editar:action]/[i:id]?',
                'method' => ['GET', 'POST'],
                'callback' => self::class.'@save',
            ],
            [
                'url' => '/gerenciar/$[table.unix.plural]/excluir/[i:id]',
                'method' => 'GET',
                'callback' => self::class.'@delete',
            ],
        ];
    }
}
