<?php

namespace app\func;

use Yii;
use yii\web\HttpException;
use yii\web\Session;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\web\JsExpression;

class Proc {

    public static function Breadcrumbs($view, $param = null) {
        if (isset($view)) {
            $param = $param === null ? [] : $param;

            $clearbefore = isset($param['clearbefore']) && is_bool($param['clearbefore']) ? $param['clearbefore'] : false;
            $addfirst = isset($param['addfirst']) && is_array($param['addfirst']) ? $param['addfirst'] : [];

            $postfix = isset($param['postfix']) ? $param['postfix'] : '';
            $id = $view->context->module->controller->id . '/' . $view->context->module->requestedRoute . '/' . $postfix;

            $session = new Session;
            $session->open();

            if (!isset($session['breadcrumbs']))
                $session['breadcrumbs'] = [];

            $result = $session['breadcrumbs'];

            if ($clearbefore)
                $result = [];

            if (count($addfirst) > 0) {
                $result = array_replace_recursive([
                    'addfirst' => $addfirst,
                        ], $result);
            }

            if (!isset($result[$id]))
                $result[$id] = [];

            $params = Yii::$app->getRequest()->getQueryParams();
            unset($params['r']);
            $result[$id] = array_replace_recursive($result[$id], [
                'label' => empty($view->title) ? '-' : $view->title,
                'url' => Url::toRoute(array_merge([$view->context->module->requestedRoute], $params)),
                'dopparams' => isset($_GET['foreignmodel']) ? [
                    'foreign' => [
                        'url' => (string) filter_input(INPUT_GET, 'url'),
                        'model' => (string) filter_input(INPUT_GET, 'foreignmodel'),
                        'field' => (string) filter_input(INPUT_GET, 'field'),
                        'id' => (string) filter_input(INPUT_GET, 'id'),
                    ]
                        ] : []
            ]);

            if (isset($param['model']) && !is_array($param['model']))
                $param['model'] = [$param['model']];

            if (isset($param['model']) && is_array($param['model'])) {
                foreach ($param['model'] as $model) {

                    $fmodel = substr($model->className(), strrpos($model->className(), '\\') + 1);

                    if (!isset($result[$id]['dopparams'][$fmodel])) {
                        $result[$id] = array_replace_recursive($result[$id], [
                            'dopparams' => [$fmodel => $model->attributes],
                        ]);
                    } else {
                        end($result);

                        $value = '';

                        $field = $result[key($result)]['dopparams']['foreign']['field'];

                        while (count($result) > 0 && $id !== key($result)) {
                            unset($result[key($result)]);
                            end($result);
                        }

                        $model->load($result[key($result)]['dopparams'], $fmodel);

                        if (isset(Yii::$app->request->get()[$fmodel][$field])) {
                            $value = Yii::$app->request->get()[$fmodel][$field];
                            $result[key($result)]['dopparams'][$fmodel][$field] = $value;
                            $model->$field = $value;
                        }
                    }
                }
            } else {
                end($result);
                while (count($result) > 0 && $id !== key($result)) {
                    unset($result[key($result)]);
                    end($result);
                }
            }

            $session['breadcrumbs'] = $result;

            /*    echo '<pre class="xdebug-var-dump" style="max-height: 350px; font-size: 15px;">';
              $s1 = $_SESSION;
              unset($s1['__flash']);
              print_r($s1);
              echo '</pre>'; */

            $session->close();

            end($result);

            unset($result[key($result)]['url']);

            return $result;
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function Breadcrumbs');
    }

    // Настройки по умолчанию для DynaGrid
    public static function DGopts($Options) {
        if (isset($Options) && is_array($Options))
            return array_replace_recursive([
                'options' => ['id' => 'dynagrid-1'],
                'showPersonalize' => true,
                'storage' => 'cookie',
                //'allowPageSetting' => false, 
                'allowThemeSetting' => false,
                'allowFilterSetting' => false,
                'allowSortSetting' => false,
                'gridOptions' => [
                    'panel' => [
                        'type' => Yii::$app->params['GridHeadingStyle'],
                        'headingOptions' => ['class' => 'panel-heading panel-' . Yii::$app->params['GridHeadingStyle']],
                    ]],
                    ], $Options);
    }

    // Возвращает массив колонок для DynaGrid
    // $params['columns'] - массив полей базы данных для грида
    // $params['buttons'] - Кнопки для ActionColumn
    // $params['buttons']['update' => [0 => URL для обновления записи, 1 => ID обновляемой записи]]
    // $params['buttons']['delete' => [0 => URL для удаления записи, 1 => ID удаляемой записи]]
    public static function DGcols($params) {
        if (isset($params) && is_array($params)) {
            // Делаем строку 'template' на основе массива кнопок
            $tmpl = isset($params['buttons']) && is_array($params['buttons']) ? '{' . implode("} {", array_keys($params['buttons'])) . '}' : '';

            // Если есть кнопка обновления записи
            if (isset($params['buttons']['update']) && is_array($params['buttons']['update'])) {
                $params['buttons']['update'] = function ($url, $model) use ($params) {
                    $customurl = Yii::$app->getUrlManager()->createUrl([$params['buttons']['update'][0], 'id' => $model[$params['buttons']['update'][1]]]);
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning', 'data-pjax' => '0']);
                };
            }

            // Если есть кнопка удаления записи
            if (isset($params['buttons']['delete']) && is_array($params['buttons']['delete'])) {
                $params['buttons']['delete'] = function ($url, $model) use ($params) {
                    $customurl = Yii::$app->getUrlManager()->createUrl([$params['buttons']['delete'][0], 'id' => $model[$params['buttons']['delete'][1]]]);
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', $customurl, ['title' => 'Удалить', 'class' => 'btn btn-xs btn-danger', 'data' => [
                                    'confirm' => "Вы уверены, что хотите удалить запись?",
                                    'method' => 'post',
                    ]]);
                };
            }

            return array_merge([
                ['class' => 'kartik\grid\SerialColumn',
                    'header' => Html::encode('№'),
                ]
                    ], isset($params['columns']) && is_array($params['columns']) ? $params['columns'] : [], [
                [ 'class' => 'kartik\grid\ActionColumn',
                    'header' => Html::encode('Действия'),
                    'contentOptions' => ['style' => 'white-space: nowrap;'],
                    'template' => $tmpl,
                    'buttons' => isset($params['buttons']) && is_array($params['buttons']) ? $params['buttons'] : [],]
            ]);
        }
    }

    // Возвращает параметры для элемента Select2
    public static function DGselect2($params) {
        if (isset($params) && is_array($params)) {
            $model = $params['model'];
            $resultmodel = $params['resultmodel'];
            $resultrequest = $params['resultrequest'];
            $placeholder = $params['placeholder'];
            $fromgridroute = $params['fromgridroute'];
            $thisroute = $params['thisroute'];
            $fields = $params['fields'];

            if (!isset($fields['showresultfields']))
                $fields['showresultfields'] = [$fields['resultfield']];

            if (!empty($model) && !empty($resultmodel) && !empty($fields['keyfield']) && !empty($fields['resultfield']) && !empty($fromgridroute) && !empty($thisroute)) {

                $initrecord = empty($model->$fields['keyfield']) ? '' : $resultmodel::find()
                                ->select($fields['showresultfields'])
                                ->where([$resultmodel->primarykey()[0] => $model->$fields['keyfield']])
                                ->asArray()
                                ->one();

                return [
                    'initValueText' => empty($model->$fields['keyfield']) ? '' : implode(', ', $initrecord),
                    'options' => ['placeholder' => $placeholder, 'class' => 'form-control setsession'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => Url::to([$resultrequest]),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term, field: "' . $fields['resultfield'] . '", showresultfields: ' . json_encode($fields['showresultfields']) . ' } }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    ],
                    'addon' => [
                        'append' => [
                            'content' => Html::a('<i class="glyphicon glyphicon-plus-sign"></i>', [$fromgridroute,
                                'foreignmodel' => substr($model->className(), strrpos($model->className(), '\\') + 1),
                                'url' => $thisroute,
                                'field' => $fields['keyfield'],
                                'id' => $model->primaryKey,
                                    ], ['class' => 'btn btn-success']),
                            'asButton' => true
                        ]
                    ]
                ];
            } else
                throw new \Exception('Ошибка в Proc::DGselect2()');
        }
    }

    // Выводит массив данных для Select2 элемента
    // $model - Модель из которой берутся данные
    // $field - Поле по которому осуществляется поиск
    // $q - Текстовая строка поиска
    // $showresultfields - Массив полей, которые возвращаются, как результат поиска
    public static function select2request($model, $field, $q = null, $showresultfields = null) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!isset($showresultfields))
            $showresultfields = [$field];

        $showresultfields = implode(', ', $showresultfields);
        if (!is_null($q)) {
            $m = new $model;
            $out['results'] = $model::find()
                    ->select([$m->primaryKey()[0] . ' AS id', 'CONCAT_WS(", ", ' . $showresultfields . ') AS text'])
                    ->where(['like', $field, $q])
                    ->limit(20)
                    ->asArray()
                    ->all();
        }
        return $out;
    }

    // Удаляет последний элемент массива хлебных крошек из сессии
    public static function RemoveLastBreadcrumbsFromSession() {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        unset($bc[key($bc)]);
        $session['breadcrumbs'] = $bc;
        $session->close();
    }

    // Возвращает массив хлебных крошек из сессии
    public static function GetBreadcrumbsFromSession() {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        $session->close();
        return $bc;
    }

    // Возвращает последний элемент хлебных крошек из сессии
    public static function GetLastBreadcrumbsFromSession() {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        $session->close();
        return $bc[key($bc)];
    }

    public static function GetMenuButtons($view) {
        $controller = Yii::$app->controller;
        $default_controller = Yii::$app->defaultRoute;
        $isHome = (($controller->id === $default_controller) && ($controller->action->id === $controller->defaultAction)) ? true : false;

        $urls = [
            'fregat_matcen' => 'Fregat/fregat/index',
            'fregat_conf' => 'Fregat/fregat/config',
            'config_conf' => 'Config/config/index',
        ];

        $session = new Session;
        $session->open();

        foreach ($urls as $url) // Записываем в сессию url меню
            if ($url === $view->context->module->requestedRoute) {
                $session['currentmenuurl'] = $url;
                break;
            }

        $result = [];
        if (!$isHome) {
            $menubuttons = isset($session['menubuttons']) ? $session['menubuttons'] : null;

            switch ($menubuttons) {
                case 'fregat':
                    $result = array_merge(
                            Yii::$app->user->can('FregatUserPermission') ? [['label' => 'Материальные ценности', 'url' => [$urls['fregat_matcen']],
                            'options' => $session['currentmenuurl'] === $urls['fregat_matcen'] ? ['class' => 'active'] : []
                                ]] : [], Yii::$app->user->can('FregatUserPermission') ? [['label' => 'Настройки', 'url' => [$urls['fregat_conf']],
                            'options' => $session['currentmenuurl'] === $urls['fregat_conf'] ? ['class' => 'active'] : []
                                ]] : []
                    );
                    break;
                case 'config':
                    $result = array_merge(
                            Yii::$app->user->can('UserEdit') || Yii::$app->user->can('RoleEdit') ? [['label' => 'Настройки портала', 'url' => [$urls['config_conf']],
                            'options' => $session['currentmenuurl'] === $urls['config_conf'] ? ['class' => 'active'] : [],
                                ]] : []
                    );
                    break;
            }
        } else {
            $session->remove('menubuttons');
            $session->remove('currentmenuurl');
        }
        $session->close();
        return $result;
    }

    public static function SetMenuButtons($ButtonsGroup) {
        $session = new Session;
        $session->open();
        $session['menubuttons'] = $ButtonsGroup;
        $session->close();
    }

    static function mb_preg_match_all($ps_pattern, $ps_subject, &$pa_matches, $pn_flags = PREG_PATTERN_ORDER, $pn_offset = 0, $ps_encoding = NULL) {
        // WARNING! - All this function does is to correct offsets, nothing else:

        if (is_null($ps_encoding))
            $ps_encoding = mb_internal_encoding();

        $pn_offset = strlen(mb_substr($ps_subject, 0, $pn_offset, $ps_encoding));
        $ret = preg_match_all($ps_pattern, $ps_subject, $pa_matches, $pn_flags, $pn_offset);

        if ($ret && ($pn_flags & PREG_OFFSET_CAPTURE))
            foreach ($pa_matches as &$ha_match)
                foreach ($ha_match as &$ha_match)
                    $ha_match[1] = mb_strlen(substr($ps_subject, 0, $ha_match[1]), $ps_encoding);
        //
        // (code is independent of PREG_PATTER_ORDER / PREG_SET_ORDER)

        return $ret;
    }

    /**
     * Функция проверяет имя файла, если оно существует, в название добавляется порядковый номер, например Список.xls переходит в Список(1).xls
     * 
     * $fileroot - путь к файлу
     * возвращает новое имя файла
     */
    static function SaveFileIfExists($fileroot) {
        $counter = 1;
        $filename = substr($fileroot, strpos($fileroot, '/') + 1);

        while (file_exists($fileroot)) {
            preg_match('/(.+\/)(.+?)((\(.+)?\.)(.+)/i', $fileroot, $file_arr);
            // $file_arr[1] - Директория, $file_arr[2] - Имя файла, end($file_arr) - Расширение файла
            $fileroot = $file_arr[1] . $file_arr[2] . '(' . $counter . ')' . '.' . end($file_arr);
            $filename = $file_arr[2] . '(' . $counter . ')' . '.' . end($file_arr);
            $counter++;
        }

        return $filename;
    }

    static function WhereCunstruct($modelsearch, $field, $type = '') {
        preg_match('/(>=|<=|>|<|=)?(.*)/', $modelsearch->$field, $matches);
        $operator = $matches[1];
        $value = $matches[2];

        if ($type === 'date')
            $value = !empty($value) ? date("Y-m-d", strtotime($value)) : $value;
        elseif ($type === 'datetime')
            $value = !empty($value) ? date("Y-m-d H:i:s", strtotime($value)) : $value;
        elseif ($type === 'time')
            $value = !empty($value) ? date("H:i:s", strtotime($value)) : $value;

        return [empty($operator) ? '=' : $operator, $field, $value];
    }

}
