<?php

namespace app\func;

use Yii;
use yii\web\HttpException;
use yii\web\Session;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\db\ActiveRecord;
use yii\base\Model;
use kartik\datecontrol\DateControl;
use kartik\touchspin\TouchSpin;

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
                    if (!isset($result[$id]['dopparams'][$model->formName()])) {
                        $result[$id] = array_replace_recursive($result[$id], [
                            'dopparams' => [$model->formName() => $model->attributes],
                        ]);
                    } else {
                        end($result);

                        $value = '';

                        $field = $result[key($result)]['dopparams']['foreign']['field'];

                        if (count($result) > 0 && $id !== key($result))
                            prev($result);

                        // Массовое присваивание не походит, нужно пройти по всем атрибутам
                        foreach ($model->attributes as $attr => $value)
                            $model->$attr = $result[key($result)]['dopparams'][$model->formName()][$attr];

                        //  $model->load($result[key($result)]['dopparams'], $model->formName());
                    }
                }

                end($result);
                while (count($result) > 0 && $id !== key($result)) {
                    unset($result[key($result)]);
                    end($result);
                }
            } else {
                end($result);
                while (count($result) > 0 && $id !== key($result)) {
                    unset($result[key($result)]);
                    end($result);
                }
            }

            $session['breadcrumbs'] = $result;

            /*     echo '<pre class="xdebug-var-dump" style="max-height: 350px; font-size: 15px;">';
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
                    'options' => ['id' => $Options['options']['id'] . '_gw'],
                    'panel' => [
                        'type' => Yii::$app->params['GridHeadingStyle'],
                        'headingOptions' => ['class' => 'panel-heading panel-' . Yii::$app->params['GridHeadingStyle']],
                    ],
                ],
                    ], $Options);
    }

    // Возвращает массив колонок для DynaGrid
    // $params['columns'] - массив полей базы данных для грида
    // $params['buttons'] - Кнопки для ActionColumn
    // $params['buttons']['update' => [0 => URL для обновления записи, 1 => ID обновляемой записи]]
    // $params['buttons']['delete' => [0 => URL для удаления записи, 1 => ID удаляемой записи]]
    // $params['buttonsfirst'] - Расположить кнопки в первой колонке
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
                    return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'type' => 'button',
                                'title' => 'Удалить',
                                'class' => 'btn btn-xs btn-danger',
                                'onclick' => 'ConfirmDialogToAjax("Вы уверены, что хотите удалить запись?", "' . $customurl . '")'
                    ]);
                };
            }

            // Если есть кнопка удаления записи посредством ajax
            if (isset($params['buttons']['deleteajax']) && is_array($params['buttons']['deleteajax'])) {
                $params['buttons']['deleteajax'] = function ($url, $model) use ($params) {
                    $customurl = Yii::$app->getUrlManager()->createUrl([$params['buttons']['deleteajax'][0], 'id' => isset($params['buttons']['deleteajax'][1]) ? $model[$params['buttons']['deleteajax'][1]] : $model->primarykey]);
                    return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'type' => 'button',
                                'title' => 'Удалить',
                                'class' => 'btn btn-xs btn-danger',
                                'onclick' => 'ConfirmDeleteDialogToAjax("Вы уверены, что хотите удалить запись?", "' . $customurl . '"' . (isset($params['buttons']['deleteajax'][2]) ? ', "' . $params['buttons']['deleteajax'][2] . '"' : '') . ')'
                    ]);
                };
            }

            // Если есть кнопка выбора записи посредством ajax
            if (isset($params['buttons']['chooseajax']) && is_array($params['buttons']['chooseajax'])) {
                $params['buttons']['chooseajax'] = function ($url, $model) use ($params) {
                    return Html::button('<i class="glyphicon glyphicon-ok-sign"></i>', [
                                'type' => 'button',
                                'title' => 'Выбрать',
                                'class' => 'btn btn-xs btn-success',
                                'onclick' => 'AssignValueFromGrid("' . Url::to([$params['buttons']['chooseajax'][0]]) . '","' . $model->primarykey . '")'
                    ]);
                };
            }

            $mascolumns = isset($params['columns']) && is_array($params['columns']) ? $params['columns'] : [];
            $masbuttons = isset($params['buttons']) && is_array($params['buttons']) && count($params['buttons']) > 0 ? [
                [ 'class' => 'kartik\grid\ActionColumn',
                    'header' => Html::encode('Действия'),
                    'contentOptions' => ['style' => 'white-space: nowrap;'],
                    'template' => $tmpl,
                    'buttons' => is_array($params['buttons']) ? $params['buttons'] : [],]
                    ] : [];

            $masitog = (isset($params['buttonsfirst']) && $params['buttonsfirst'] === true) ? array_merge($masbuttons, $mascolumns) : $masitog = array_merge($mascolumns, $masbuttons);

            return array_merge([
                ['class' => 'kartik\grid\SerialColumn',
                    'header' => Html::encode('№'),
                ]
                    ], $masitog);
        }
    }

    // Возвращает параметры для элемента Select2
    // $params[model] - Модель из которой берутся данные
    // $params[resultmodel] - 
    // $params[resultrequest] -
    // $params[placeholder] -
    // $params[fromgridroute] - 
    // $params[thisroute] - 
    // $params[fields] - 
    // $params[dopparams] - 
    // $params[methodquery] - 
    // $params[methodparams] - 
    // $params[ajaxparams] - 
    // $params[minimumInputLength] - 
    // $params[form] - Имя формы, которому пренадлежит select2, по умолчанию не задано
    // $params[options] - Заменяет параметр "options", по умолчанию не задано
    // $params[setsession] - Добавляет класс html "setsession" для сохранения знаечния в сессии, по умолчанию true
    // $params[multiple][idvalue] - поле для определения ИД значений "data" при выборе multiple => true, обязательно при выборе мультивыбора
    // $params[multiple][multipleshowall] - Показывает кнопку "Выбрать все" (при ajax загрузке значений не актуально), по умолчанию true
    public static function DGselect2($params) {
        if (isset($params) && is_array($params)) {
            $model = $params['model'];
            $resultmodel = $params['resultmodel'];
            $resultrequest = $params['resultrequest'];
            $placeholder = $params['placeholder'];
            $fromgridroute = $params['fromgridroute'];
            $thisroute = $params['thisroute'];
            $fields = $params['fields'];
            $dopparams = isset($params['dopparams']) ? $params['dopparams'] : '';
            $methodquery = isset($params['methodquery']) ? $params['methodquery'] : '';
            $methodparams = isset($params['methodparams']) ? $params['methodparams'] : [];
            $minimumInputLength = isset($params['minimuminputlength']) ? $params['minimuminputlength'] : 3;
            $form = isset($params['form']) ? $params['form'] : '';
            $options = isset($params['options']) ? $params['options'] : '';

            $setsession = isset($params['setsession']) ? $params['setsession'] : true;
            $multiple = isset($params['multiple']) && is_array($params['multiple']) ? $params['multiple'] : [];
            $showToggleAll = isset($params['multiple']['multipleshowall']) ? $params['multiple']['multipleshowall'] : true;

            $ajaxparamsString = '';
            foreach ($methodparams as $key => $value)
                $ajaxparamsString.= ',' . $key . ': ' . $value;

            if (!isset($fields['showresultfields']) && !isset($fields['methodquery']))
                $fields['showresultfields'] = [$fields['resultfield']];

            $errorstring = '';
            if (empty($model))
                $errorstring.='empty($model); ';
            if (empty($resultmodel))
                $errorstring.='empty($resultmodel); ';
            if (empty($fields['keyfield']))
                $errorstring.='empty($fields[\'keyfield\']); ';
            if (empty($fields['resultfield']))
                $errorstring.='empty($fields[\'resultfield\']); ';
            if (empty($params['methodquery']))
                $errorstring.='empty($params[\'methodquery\']); ';
            if (empty($thisroute))
                $errorstring.='empty($thisroute); ';
            if (empty($multiple))
                $errorstring.='empty($multiple); ';
            if (isset($multiple['idvalue']))
                $errorstring.='isset($multiple[\'idvalue\']); ';

            if (!empty($model) && !empty($resultmodel) && !empty($fields['keyfield']) && !(empty($fields['resultfield']) && empty($params['methodquery'])) && !empty($thisroute) && (!empty($multiple) && isset($multiple['idvalue']) || empty($multiple))) {

                $valuemodel = is_array($model->$fields['keyfield']) ? $model->$fields['keyfield'] : [$model->$fields['keyfield']];

                if (!empty($methodquery)) {
                    $methodparams['q'] = $model->$fields['keyfield'];
                    $methodparams['init'] = true;

                    $initrecord = isset($methodparams['q']) ? $resultmodel->$methodquery($methodparams) : [];
                    if (!is_array($initrecord))
                        $initrecord = [$initrecord];
                } else {
                    $initrecord = $resultmodel::find()
                            ->select(array_merge(empty($multiple) ? [] : [$multiple['idvalue']], $fields['showresultfields']))
                            ->where(['in', $resultmodel->primarykey()[0], $valuemodel])
                            ->asArray()
                            ->all();

                    $initrecord_tmp = [];
                    foreach ($initrecord as $key => $rows) {
                        if (!empty($multiple))
                            array_shift($rows);
                        $initrecord_tmp[$initrecord[$key][$multiple['idvalue']]] = implode(', ', $rows);
                    }
                    $initrecord = $initrecord_tmp;
                }

                return array_merge([
                    'initValueText' => !empty($multiple) ? '' : implode(', ', $initrecord),
                    'options' => empty($options) ? array_merge(['placeholder' => $placeholder, 'class' => 'form-control' . ($setsession ? ' setsession' : ''), 'disabled' => isset($params['disabled']) && $params['disabled'] === true], empty($form) ? [] : ['form' => $form], empty($multiple) ? [] : ['multiple' => true]) : $options,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'showToggleAll' => $showToggleAll,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => $minimumInputLength,
                        'ajax' => [
                            'url' => Url::to([$resultrequest]),
                            'dataType' => 'json',
                            'data' => empty($fields['methodquery']) ? new JsExpression('function(params) { return {q:params.term, field: "' . $fields['resultfield'] . '", showresultfields: ' . json_encode($fields['showresultfields']) . '' . $ajaxparamsString . ' } }') : new JsExpression('function(params) { return {q:params.term' . $ajaxparamsString . '} }'),
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    ]
                        ], !empty($fromgridroute) ? [
                            'addon' => [
                                'append' => [
                                    'content' => Html::a('<i class="glyphicon glyphicon-plus-sign"></i>', array_merge([$fromgridroute,
                                        'foreignmodel' => $model->formName(),
                                        'url' => $thisroute,
                                        'field' => $fields['keyfield'],
                                        'id' => $model->primaryKey,
                                                    ], !is_array($dopparams) ? [] : $dopparams), ['class' => 'btn btn-success']),
                                    'asButton' => true
                                ]
                            ]] : [], !empty($multiple) ? [
                            'data' => $initrecord
                                ] : []
                );
            } else
                throw new \Exception('Ошибка в Proc::DGselect2(): ' . $errorstring);
        }
    }

    // Выводит массив данных для Select2 элемента
    // $params[model] - Модель из которой берутся данные
    // $params[field] - Поле по которому осуществляется поиск
    // $params[q] - Текстовая строка поиска
    // $params[showresultfields] - Массив полей, которые возвращаются, как результат поиска
    public static function select2request($params) {
        if (isset($params) && is_array($params) && $params['model'] instanceof ActiveRecord && (is_string($params['field']) || isset($params['methodquery']))) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $out = ['results' => ['id' => '', 'text' => '']];
            $model = $params['model'];
            if (!isset($params['showresultfields']))
                $params['showresultfields'] = [$params['field']];

            $params['showresultfields'] = implode(', ', $params['showresultfields']);
            if (isset($params['q'])) {
                if (isset($params['methodquery']) && (!isset($params['methodparams']) || is_array($params['methodparams'])) && is_string($params['methodquery'])) {
                    $params['methodparams']['q'] = $params['q'];

                    $out['results'] = $model->$params['methodquery']($params['methodparams']);
                    if (!is_array($out['results']))
                        exit;
                } else {
                    $out['results'] = $model::find()
                            ->select([$model::primaryKey()[0] . ' AS id', 'CONCAT_WS(", ", ' . $params['showresultfields'] . ') AS text'])
                            ->where(['like', $params['field'], $params['q']])
                            ->limit(20)
                            ->asArray()
                            ->all();
                }
            }
            return $out;
        } else
            throw new \Exception('Ошибка в Proc::select2request()');
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

    // Возвращает предпоследний элемент хлебных крошек из сессии
    public static function GetPreviusBreadcrumbsFromSession() {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        prev($bc);
        $session->close();
        return $bc[key($bc)];
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

    // Возвращает предпоследний URL из хлебных крошек из сессии
    public static function GetPreviousURLBreadcrumbsFromSession() {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        prev($bc);
        $session->close();
        return isset($bc[key($bc)]['url']) ? $bc[key($bc)]['url'] : '';
    }

    // Возвращает последний URL из хлебных крошек из сессии
    public static function GetLastURLBreadcrumbsFromSession() {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        $session->close();
        return isset($bc[key($bc)]['url']) ? $bc[key($bc)]['url'] : '';
    }

    // Сохранить модель в сессии
    public static function SetSessionValuesFromAR($model, $PreviusBC = false) {
        if ($model instanceof ActiveRecord) {
            $BC = self::GetBreadcrumbsFromSession();
            end($BC);
            if ($PreviusBC)
                prev($BC);

            foreach ($model as $attr => $value)
                $BC[key($BC)]['dopparams'][$model->formName()][$attr] = $value;
            $session = new Session;
            $session->open();
            $session['breadcrumbs'] = $BC;
            $session->close();
        } else
            throw new \Exception('Ошибка в Proc::SetSessionValuesFromAR()');
    }

    public static function GetMenuButtons($view) {
        $controller = Yii::$app->controller;
        $default_controller = Yii::$app->defaultRoute;
        $isHome = (($controller->id === $default_controller) && ($controller->action->id === $controller->defaultAction)) ? true : false;

        $urls = [
            'fregat_mainmenu' => 'Fregat/fregat/mainmenu',
            'fregat_conf' => 'Fregat/fregat/config',
            'config_conf' => 'Config/config/index',
            'glauk_index' => 'Base/patient/glaukindex',
            'glauk_conf' => 'Glauk/glaukuchet/config',
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
                            Yii::$app->user->can('FregatUserPermission') ? [['label' => 'Основное меню', 'url' => [$urls['fregat_mainmenu']],
                            'options' => $session['currentmenuurl'] === $urls['fregat_mainmenu'] ? ['class' => 'active'] : []
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
                case 'glauk':
                    $result = array_merge(
                            Yii::$app->user->can('GlaukUserPermission') ? [['label' => 'Пациенты', 'url' => [$urls['glauk_index']],
                            'options' => $session['currentmenuurl'] === $urls['glauk_index'] ? ['class' => 'active'] : [],
                                ]] : [], Yii::$app->user->can('GlaukOperatorPermission') ? [['label' => 'Настройки', 'url' => [$urls['glauk_conf']],
                            'options' => $session['currentmenuurl'] === $urls['glauk_conf'] ? ['class' => 'active'] : [],
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
        preg_match('/(>=|<=|>|<|=)?(.*)/', $modelsearch->getAttribute($field), $matches);
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

    static function Translit($string) {
        $replace = array(
            "'" => "",
            "`" => "",
            "а" => "a", "А" => "a",
            "б" => "b", "Б" => "b",
            "в" => "v", "В" => "v",
            "г" => "g", "Г" => "g",
            "д" => "d", "Д" => "d",
            "е" => "e", "Е" => "e",
            "ж" => "zh", "Ж" => "zh",
            "з" => "z", "З" => "z",
            "и" => "i", "И" => "i",
            "й" => "y", "Й" => "y",
            "к" => "k", "К" => "k",
            "л" => "l", "Л" => "l",
            "м" => "m", "М" => "m",
            "н" => "n", "Н" => "n",
            "о" => "o", "О" => "o",
            "п" => "p", "П" => "p",
            "р" => "r", "Р" => "r",
            "с" => "s", "С" => "s",
            "т" => "t", "Т" => "t",
            "у" => "u", "У" => "u",
            "ф" => "f", "Ф" => "f",
            "х" => "h", "Х" => "h",
            "ц" => "c", "Ц" => "c",
            "ч" => "ch", "Ч" => "ch",
            "ш" => "sh", "Ш" => "sh",
            "щ" => "sch", "Щ" => "sch",
            "ъ" => "", "Ъ" => "",
            "ы" => "y", "Ы" => "y",
            "ь" => "", "Ь" => "",
            "э" => "e", "Э" => "e",
            "ю" => "yu", "Ю" => "yu",
            "я" => "ya", "Я" => "ya",
            "і" => "i", "І" => "i",
            "ї" => "yi", "Ї" => "yi",
            "є" => "e", "Є" => "e"
        );
        return $str = iconv("UTF-8", "UTF-8//IGNORE", strtr($string, $replace));
    }

    public static function CreateLogin($Fullname) {
        preg_match('/(\w+)\s?(\w+)?\s?(\w+)?/ui', $Fullname, $matches);
        $result = '';

        if (!empty($matches[1]))
            $result .= ucfirst(Proc::Translit($matches[1]));
        if (!empty($matches[2]))
            $result .= ucfirst(Proc::Translit(mb_substr($matches[2], 0, 1, 'UTF-8')));
        if (!empty($matches[3]))
            $result .= ucfirst(Proc::Translit(mb_substr($matches[3], 0, 1, 'UTF-8')));

        $count = \app\models\Config\Authuser::find()
                ->where(['like', 'auth_user_login', $result . '%', false])
                ->count();

        return $count > 0 ? $result . $count : $result;
    }

    public static function file_exists_ci($file) {
        if (file_exists($file))
            return $file;
        $lowerfile = strtolower($file);
        foreach (glob(dirname($file) . '/*') as $file)
            if (strtolower($file) == $lowerfile)
                return $file;
        return FALSE;
    }

    public static function GetAllLabelsFromAR($DataProvider, $fields = NULL, $LabelValues = NULL) {
        $cls_ar = class_exists($DataProvider->query->modelClass) ? new $DataProvider->query->modelClass : false;
        if ($cls_ar instanceof ActiveRecord) {
            if (!is_array($fields))
                $fields = $cls_ar->attributes;
            $labels = [];
            array_walk($fields, function($value, $key) use (&$labels, $cls_ar, $LabelValues) {
                $labels[$key] = property_exists($LabelValues, $key) ? $LabelValues->$key : $cls_ar->getAttributeLabel($key);
            });
        }
        return $labels;
    }

    public static function GetAllDataFromAR($Activerecord, $fields = null) {
        if (!is_array($fields))
            $fields = [];

        $data = [];
        $cls_ar = $Activerecord;

        if ($cls_ar instanceof ActiveRecord) {

            if (!is_array($fields))
                $fields = $cls_ar->attributes;

            array_walk($fields, function($value, $key) use (&$data, $cls_ar) {
                $attr_arr = explode('.', $key);
                $attr_arr_tmp = $attr_arr;
                $lastelem = array_pop($attr_arr_tmp);
                $ar = $cls_ar;

                foreach ($attr_arr_tmp as $relat) {
                    $cls_ar = $cls_ar->$relat;
                    if (!isset($cls_ar))
                        break;
                }

                $data[$key] = '';

                if (!empty($cls_ar)) {
                    $result = false;
                    if (is_array($cls_ar))
                        $cls_ar = $cls_ar[0];

                    foreach ($cls_ar->getActiveValidators($lastelem) as $validatorclass)
                        if ($validatorclass instanceof \yii\validators\DateValidator) {
                            $result = $validatorclass->type;
                            break;
                        }

                    switch ($result) {
                        case 'date':
                            $data[$key] = Yii::$app->formatter->asDate($cls_ar->$lastelem);
                            break;
                        case 'time':
                            $data[$key] = Yii::$app->formatter->asTime($cls_ar->$lastelem);
                            break;
                        case 'datetime':
                            $data[$key] = Yii::$app->formatter->asDatetime($cls_ar->$lastelem);
                            break;
                        case false:
                            $data[$key] = $cls_ar->$lastelem;
                            break;
                    }
                }
            });
        }

        return $data;
    }

    public static function Grid2Excel($dataProvider, $modelName, $reportName, $selectvalues = NULL, $ModelFilter = NULL, $LabelValues = NULL) {
        $objPHPExcel = new \PHPExcel;

        /* Границы таблицы */
        $ramka = array(
            'borders' => array(
                'bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'top' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))
        );

        /* Жирный шрифт для шапки таблицы */
        $font = array(
            'font' => array(
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $reportName);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 1)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ],
        ]);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'Дата: ' . date('d.m.Y'));
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 2)->applyFromArray([
            'font' => [
                'italic' => true
            ]
        ]);

        $params = Yii::$app->request->queryParams;
        $inputdata = json_decode($params['inputdata']);
        $fields = Proc::GetArrayValuesByKeyName($modelName, $inputdata);
        $selectvalues = (array) $selectvalues;

        $dataProvider->pagination = false;
        $filter = 'Фильтр:';

        foreach ($fields[$modelName] as $attr => $value) {
            $val_result = $value;
            if (!empty($value)) {
                if (isset($selectvalues[$modelName . '[' . $attr . ']']))
                    $val_result = $selectvalues[$modelName . '[' . $attr . ']'][$fields[$modelName][$attr]];

                $filter .= ' ' . $labels[$attr] . ': "' . $val_result . '";';
            }
        }

        if ($ModelFilter instanceof Model) {
            $dopfilter = self::ConstructFilterOutput($ModelFilter);
            if (!empty($dopfilter))
                $filter .= ' ' . $dopfilter;
        }

        $i = 0;
        $r = 5;
        if (count((array) $dataProvider->getModels()) > 0) {
            foreach ($dataProvider->getModels() as $row => $ar) {
                $r++;
                // Названия полей
                if ($row === 0) {
                    $labels = self::GetAllLabelsFromAR($dataProvider, $fields[$modelName], $LabelValues);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $r - 1, '№');
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $r - 1)->applyFromArray($ramka);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $r - 1)->applyFromArray($font);
                    foreach ($labels as $label) {
                        $i++;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r - 1, $label);
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r - 1)->applyFromArray($ramka);
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r - 1)->applyFromArray($font);
                    }
                }

                $data = self::GetAllDataFromAR($ar, $fields[$modelName]);
                $i = 0;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r, $r - 5);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r)->applyFromArray($ramka);
                foreach (array_keys($data) as $attr) {
                    $i++;
                    if (isset($selectvalues[$modelName . '[' . $attr . ']']))
                        $data[$attr] = $selectvalues[$modelName . '[' . $attr . ']'][$data[$attr]];
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r, isset($data[$attr]) ? $data[$attr] : '');
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r)->applyFromArray($ramka);
                }
            }
        } else {
            $r++;
            $labels = self::GetAllLabelsFromAR($dataProvider, $fields[$modelName], $LabelValues);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $r - 1, '№');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $r - 1)->applyFromArray($ramka);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $r - 1)->applyFromArray($font);
            foreach ($labels as $label) {
                $i++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r - 1, $label);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r - 1)->applyFromArray($ramka);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r - 1)->applyFromArray($font);
            }
        }

        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, 1, $i, 1);

        /* Авторазмер колонок Excel */
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(6);
        for ($i = 1; $i <= count($labels) + 1; $i++)
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);

        if ($filter !== 'Фильтр:') {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, $filter);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, 3, $i, 3);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 3)->applyFromArray([
                'font' => [
                    'italic' => true
                ]
            ]);
        }

        // присваиваем имя файла от имени модели 
        $FileName = $reportName;

        // Устанавливаем имя листа
        $objPHPExcel->getActiveSheet()->setTitle($FileName);

        // Выбираем первый лист
        $objPHPExcel->setActiveSheetIndex(0);
        // Формируем файл Excel
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $FileName = DIRECTORY_SEPARATOR === '/' ? $FileName : mb_convert_encoding($FileName, 'Windows-1251', 'UTF-8');
        // Proc::SaveFileIfExists() - Функция выводит подходящее имя файла, которое еще не существует. mb_convert_encoding() - Изменяем кодировку на кодировку Windows
        $fileroot = self::SaveFileIfExists('files/' . $FileName . '.xlsx');
        // Сохраняем файл в папку "files"
        $objWriter->save('files/' . $fileroot);
        // Возвращаем имя файла Excel
        if (DIRECTORY_SEPARATOR === '/')
            echo $fileroot;
        else
            echo mb_convert_encoding($fileroot, 'UTF-8', 'Windows-1251');
    }

    // Функция преобразует массив
    /*
      AuthitemSearch[description] => string ''
      AuthitemSearch[type] => string ''
      AuthitemSearch[name] => string ''

      'description' => string ''
      'type' => string ''
      'name' => string ''
     */

    public static function GetArrayValuesByKeyName($KeyName, $Obj) {
        $result = [];
        if (is_string($KeyName) && (is_array($Obj) || is_object($Obj)))
            foreach ($Obj as $key => $value) {
                if (strpos($key, $KeyName) === 0) {
                    preg_match('/\[(.+)\]/', $key, $matches);
                    if (!empty($matches[1]))
                        $result[$matches[1]] = $value;
                }
            }
        return [$KeyName => $result];
    }

    // ФИЛЬТР в DYNAGRID-----------------------------------------------------------------------------------------------------------------
    // Устанавливаем фильтр из переденных параметров запроса и сохраняем в сессию, выводим строку фильтра для отображения в гриде
    public static function SetFilter($ModelGridName, $ModelFilter) {
        if (is_string($ModelGridName) && $ModelFilter instanceof Model) {
            $filter = '';
            $dofilterstring = false;
            $session = new Session;
            $session->open();
            if (isset(Yii::$app->request->queryParams[$ModelGridName]['_filter'])) {
                $filterfield = Yii::$app->request->queryParams[$ModelGridName]['_filter'];
                parse_str($filterfield, $filterparams);
                if (is_array($filterparams)) {
                    unset($filterparams['_csrf']);
                    $sestmp[$ModelGridName] = $filterparams;
                    $session['_filter'] = $sestmp;
                    $dofilterstring = true;
                }
            } elseif (isset(Yii::$app->request->queryParams[$ModelGridName]) && !isset(Yii::$app->request->queryParams[$ModelGridName]['_filter'])) {
                unset($session['_filter']);
            } elseif (isset($session['_filter']))
                $dofilterstring = true;

            if ($dofilterstring) {
                $filter = self::ConstructFilterOutput($ModelFilter);
                if (!empty($filter))
                    $filter = '<div class="panel panel-warning"><div class="panel-heading">Доп фильтр:' . $filter . '<button id="' . $ModelFilter->formName() . '_resetfilter" type="button" class="close" aria-hidden="true">&times;</button></div></div>';
            }

            $session->close();
            return $filter;
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function SetFilter');
    }

    // Заполняем форму Фильтра из сессии
    public static function PopulateFilterForm($ModelGridName, &$ModelFilter) {
        if (is_string($ModelGridName) && $ModelFilter instanceof Model) {
            $session = new Session;
            $session->open();
            if (isset($session['_filter'][$ModelGridName][$ModelFilter->formName()]))
                return $ModelFilter->load($session['_filter'][$ModelGridName]);
            else
                return false;
            $session->close();
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function PopulateFilterForm');
    }

    // Получаем значения полей фильтра
    public static function GetFilter($ModelGridName, $ModelFilterName) {
        if (is_string($ModelGridName) && is_string($ModelFilterName)) {
            $result = [];
            $session = new Session;
            $session->open();
            if (isset($session['_filter'][$ModelGridName][$ModelFilterName]) && is_array($session['_filter'][$ModelGridName][$ModelFilterName]))
                $result = $session['_filter'][$ModelGridName][$ModelFilterName];

            $session->close();
            return $result;
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function GetFilter');
    }

    // Функция выводит строку фильтра для отображения в гриде
    private static function ConstructFilterOutput($AR) {
        $session = new Session;
        $session->open();
        $filter = '';

        if (isset($session['_filter'])) {
            foreach ($session['_filter'] as $filtform) {
                foreach ($filtform as $filtformname => $fields) {
                    if ($filtformname === $AR->formName()) {
                        foreach ($fields as $attr => $value)
                            if ((!empty($value) || strpos($attr, '_beg') === strlen($attr) - 4) && strpos($attr, '_znak') !== strlen($attr) - 5 && strpos($attr, '_end') !== strlen($attr) - 4)
                                if (strpos($attr, '_mark') === strlen($attr) - 5) {
                                    if ($value === '1')
                                        $filter .= ' ' . $AR->attributeLabels()[$attr] . ';';
                                } elseif (strpos($attr, '_beg') === strlen($attr) - 4) {
                                    $attrend = substr($attr, 0, strlen($attr) - 4) . '_end';

                                    $result = false;
                                    foreach ($AR->getActiveValidators($attr) as $validatorclass)
                                        if ($validatorclass instanceof \yii\validators\DateValidator) {
                                            $result = $validatorclass->type;
                                            break;
                                        }

                                    switch ($result) {
                                        case 'date':
                                            $fields[$attr] = empty($fields[$attr]) ? '' : Yii::$app->formatter->asDate($fields[$attr]);
                                            $fields[$attrend] = empty($fields[$attrend]) ? '' : Yii::$app->formatter->asDate($fields[$attrend]);
                                            break;
                                        case 'time':
                                            $fields[$attr] = empty($fields[$attr]) ? '' : Yii::$app->formatter->asTime($fields[$attr]);
                                            $fields[$attrend] = empty($fields[$attrend]) ? '' : Yii::$app->formatter->asDate($fields[$attrend]);
                                            break;
                                        case 'datetime':
                                            $fields[$attr] = empty($fields[$attr]) ? '' : Yii::$app->formatter->asDatetime($fields[$attr]);
                                            $fields[$attrend] = empty($fields[$attrend]) ? '' : Yii::$app->formatter->asDate($fields[$attrend]);
                                            break;
                                    }

                                    if (!empty($fields[$attr]) && !empty($fields[$attrend]))
                                        $filter .= ' ' . $AR->attributeLabels()[$attr] . ' С ' . $fields[$attr] . ' ПО ' . $fields[$attrend] . ';';
                                    elseif (!empty($fields[$attr]) || !empty($fields[$attrend])) {
                                        $znak = (!empty($fields[$attr])) ? '>=' : '<=';
                                        $value = (!empty($fields[$attr])) ? $fields[$attr] : $fields[$attrend];
                                        $filter .= ' ' . $AR->attributeLabels()[$attr] . ' ' . $znak . ' "' . $value . '";';
                                    }
                                } elseif (!isset($fields[$attr . '_znak']) || isset($fields[$attr . '_znak']) && in_array($fields[$attr . '_znak'], ['>=', '<=', '='])) {
                                    $znak = (isset($fields[$attr . '_znak'])) ? $fields[$attr . '_znak'] : '=';

                                    $result = false;
                                    foreach ($AR->getActiveValidators($attr) as $validatorclass)
                                        if ($validatorclass instanceof \yii\validators\DateValidator) {
                                            $result = $validatorclass->type;
                                            break;
                                        }

                                    switch ($result) {
                                        case 'date':
                                            $value = Yii::$app->formatter->asDate($value);
                                            break;
                                        case 'time':
                                            $value = Yii::$app->formatter->asTime($value);
                                            break;
                                        case 'datetime':
                                            $value = Yii::$app->formatter->asDatetime($value);
                                            break;
                                    }

                                    if (!is_array($value))
                                        $value = [$value];

                                    if (method_exists($AR, 'VariablesValues'))
                                        foreach ($value as $key => $item) {
                                            $var = $AR->VariablesValues($attr, $item);
                                            $value[$key] = isset($var[$item]) ? $var[$item] : $item;
                                        }

                                    $value = implode(', ', $value);
                                    $filter .= ' ' . $AR->attributeLabels()[$attr] . ' ' . $znak . ' "' . $value . '";';
                                }
                    }
                }
            }
        }

        $session->close();
        return $filter;
    }

    // Берет значение сначала из справочника (посредством перехода на страницу выбора), если нет, то вытаскивает из сессии (для простого обновления страницы)
    // выводит $kl - для последующей передачи в функцию Proc::SetSessionValuesFromAR, т.е. установить в последнюю сессию или предыдущую
    public static function GetValueForFillARs(&$attrvar, $modelname, $attrname) {
        $kl = false;
        $lastses = self::GetLastBreadcrumbsFromSession();
        if (isset($lastses['dopparams']['foreign']) && $lastses['dopparams']['foreign']['model'] === $modelname && $lastses['dopparams']['foreign']['field'] === $attrname) {
            $lastses = self::GetPreviusBreadcrumbsFromSession();
            $kl = true;
        }

        if (empty($attrvar) && isset($lastses['dopparams'][$modelname][$attrname]))
            $attrvar = $lastses['dopparams'][$modelname][$attrname];

        return $kl;
    }

    public static function FilterFieldDate($Form, $ActiveRecord, $FieldName) {
        return $Form->field($ActiveRecord, $FieldName)->widget(DateControl::classname(), [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                    ],
                    'saveOptions' => ['class' => 'form-control'],
        ]);
    }

    public static function FilterFieldIntCondition($Form, $ActiveRecord, $FieldName, $Options = NULL) {
        if (!is_array($Options))
            $Options = [];

        echo '<div class="form-group"><label class="control-label" for="patientfilter-patient_vozrast">';
        echo $ActiveRecord->getAttributeLabel('patient_vozrast');
        echo '</label><div class="row"><div class="col-xs-5">';
        echo $Form->field($ActiveRecord, $FieldName . '_znak')->widget(Select2::classname(), [
            'hideSearch' => true,
            'data' => ['>=' => 'Больше или равно', '<=' => 'Меньше или равно', '=' => 'Равно'],
            'options' => ['placeholder' => 'Выберете знак равенства', 'class' => 'form-control', 'style' => 'width; 215px;'],
            'theme' => Select2::THEME_BOOTSTRAP,
        ])->label(false);
        echo '</div><div class="col-xs-7">';
        echo $Form->field($ActiveRecord, $FieldName)->widget(TouchSpin::classname(), [
            'options' => ['class' => 'form-control'],
            'pluginOptions' => array_merge([
                'verticalbuttons' => true,
                'forcestepdivisibility' => 'none',
                    ], $Options),
        ])->label(false);
        echo '</div></div></div>';
    }

    public static function FilterFieldSelectSingle($Form, $ActiveRecord, $FieldName, $PlaceHolder) {
        if (method_exists($ActiveRecord, 'VariablesValues'))
            return $Form->field($ActiveRecord, $FieldName)->widget(Select2::classname(), [
                        'hideSearch' => true,
                        'data' => $ActiveRecord::VariablesValues($FieldName),
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'options' => ['placeholder' => $PlaceHolder, 'class' => 'form-control'],
                        'theme' => Select2::THEME_BOOTSTRAP,
            ]);
    }

    public static function FilterFieldSelectMultiple($Form, $ActiveRecord, $FieldName, $PlaceHolder) {
        if (method_exists($ActiveRecord, 'VariablesValues'))
            return $Form->field($ActiveRecord, $FieldName)->widget(Select2::classname(), [
                        'hideSearch' => true,
                        'data' => $ActiveRecord::VariablesValues($FieldName),
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'options' => ['placeholder' => $PlaceHolder, 'class' => 'form-control', 'multiple' => true],
                        'theme' => Select2::THEME_BOOTSTRAP,
            ]);
    }

    public static function FilterFieldDateRange($Form, $ActiveRecord, $FieldName) {
        echo '<div class="form-group"><label class="control-label" for="' . strtolower($ActiveRecord->formName()) . '-' . $FieldName . '_beg">';
        echo $ActiveRecord->getAttributeLabel($FieldName . '_beg');
        echo '</label><div class="row"><div class="col-xs-6">';
        echo $Form->field($ActiveRecord, $FieldName . '_beg', [
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ОТ</span>{input}</div>'
        ])->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE,
            'options' => [
                'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
            ],
            'saveOptions' => ['class' => 'form-control'],
        ])->label(false);
        echo '</div><div class="col-xs-6">';
        echo $Form->field($ActiveRecord, $FieldName . '_end', [
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ДО</span>{input}</div>'
        ])->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE,
            'options' => [
                'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
            ],
            'saveOptions' => ['class' => 'form-control'],
        ])->label(false);
        echo '</div></div></div>';
    }

    // Присваеиват сортировку реляционным атрибутам по массиву списку атрибутов
    public static function AssignRelatedAttributes(&$DataProvider, $AttributesNames) {
        if ($DataProvider instanceof \yii\data\ActiveDataProvider && is_array($AttributesNames))
            foreach ($AttributesNames as $attr) {
                preg_match('/(\w+\.?\w+)$/', $attr, $matches);
                $attrsql = $matches[1];

                $DataProvider->sort->attributes[$attr] = [
                    'asc' => [$attrsql => SORT_ASC],
                    'desc' => [$attrsql => SORT_DESC],
                ];
            }
    }

    // Присваивает выбранное значение из справочника модели, в сессии
    public static function AssignToModelFromGrid($ActiveRecord = NULL, $AttributeForeignID = NULL) {
        if (Yii::$app->request->isAjax) {
            $LastBC = Proc::GetLastBreadcrumbsFromSession();
            $assigndata = filter_input(INPUT_POST, 'assigndata');
            $foreign = isset($LastBC['dopparams']['foreign']) ? $LastBC['dopparams']['foreign'] : '';

            if (!empty($foreign) && !empty($assigndata)) {
                $BC = Proc::GetBreadcrumbsFromSession();
                end($BC);
                prev($BC);
                $BC[key($BC)]['dopparams'][$foreign['model']][$foreign['field']] = $assigndata;
                $session = new Session;
                $session->open();
                $session['breadcrumbs'] = $BC;
                $session->close();

                if ($ActiveRecord instanceof ActiveRecord && is_string($AttributeForeignID)) {
                    $field = $LastBC['dopparams']['foreign']['field'];
                    if ($ActiveRecord->formName() === $LastBC['dopparams']['foreign']['model']) {
                        $ActiveRecord->$field = $assigndata;
                        $ActiveRecord->$AttributeForeignID = $foreign['id'];
                        if ($ActiveRecord->validate())
                            $ActiveRecord->save(false);
                    }
                }
            } else
                return 'error foreign or assigndata empty AssignToModelFromGrid()';
        }
    }

    // Меняет раскладку клавиатуры
    public static function switcher($text, $arrow = 0) {
        $str[0] = array('й' => 'q', 'ц' => 'w', 'у' => 'e', 'к' => 'r', 'е' => 't', 'н' => 'y', 'г' => 'u', 'ш' => 'i', 'щ' => 'o', 'з' => 'p', 'х' => '[', 'ъ' => ']', 'ф' => 'a', 'ы' => 's', 'в' => 'd', 'а' => 'f', 'п' => 'g', 'р' => 'h', 'о' => 'j', 'л' => 'k', 'д' => 'l', 'ж' => ';', 'э' => '\'', 'я' => 'z', 'ч' => 'x', 'с' => 'c', 'м' => 'v', 'и' => 'b', 'т' => 'n', 'ь' => 'm', 'б' => ',', 'ю' => '.', 'Й' => 'Q', 'Ц' => 'W', 'У' => 'E', 'К' => 'R', 'Е' => 'T', 'Н' => 'Y', 'Г' => 'U', 'Ш' => 'I', 'Щ' => 'O', 'З' => 'P', 'Х' => '[', 'Ъ' => ']', 'Ф' => 'A', 'Ы' => 'S', 'В' => 'D', 'А' => 'F', 'П' => 'G', 'Р' => 'H', 'О' => 'J', 'Л' => 'K', 'Д' => 'L', 'Ж' => ';', 'Э' => '\'', '?' => 'Z', 'ч' => 'X', 'С' => 'C', 'М' => 'V', 'И' => 'B', 'Т' => 'N', 'Ь' => 'M', 'Б' => ',', 'Ю' => '.',);
        $str[1] = array('q' => 'й', 'w' => 'ц', 'e' => 'у', 'r' => 'к', 't' => 'е', 'y' => 'н', 'u' => 'г', 'i' => 'ш', 'o' => 'щ', 'p' => 'з', '[' => 'х', ']' => 'ъ', 'a' => 'ф', 's' => 'ы', 'd' => 'в', 'f' => 'а', 'g' => 'п', 'h' => 'р', 'j' => 'о', 'k' => 'л', 'l' => 'д', ';' => 'ж', '\'' => 'э', 'z' => 'я', 'x' => 'ч', 'c' => 'с', 'v' => 'м', 'b' => 'и', 'n' => 'т', 'm' => 'ь', ',' => 'б', '.' => 'ю', 'Q' => 'Й', 'W' => 'Ц', 'E' => 'У', 'R' => 'К', 'T' => 'Е', 'Y' => 'Н', 'U' => 'Г', 'I' => 'Ш', 'O' => 'Щ', 'P' => 'З', '[' => 'Х', ']' => 'Ъ', 'A' => 'Ф', 'S' => 'Ы', 'D' => 'В', 'F' => 'А', 'G' => 'П', 'H' => 'Р', 'J' => 'О', 'K' => 'Л', 'L' => 'Д', ';' => 'Ж', '\'' => 'Э', 'Z' => '?', 'X' => 'ч', 'C' => 'С', 'V' => 'М', 'B' => 'И', 'N' => 'Т', 'M' => 'Ь', ',' => 'Б', '.' => 'Ю',);
        return strtr($text, isset($str[$arrow]) ? $str[$arrow] : array_merge($str[0], $str[1]));
    }

    // Используется для полей формы со связью, чтобы укоротить код (isset($model->idTrosnov->idMattraffic->idMaterial) ? $model->idTrosnov->idMattraffic->idMaterial : new Material)
    public static function RelatModelValue($ActiverecordRelat, $Relationstring, $ActiverecordNew) {
        if ($ActiverecordRelat instanceof ActiveRecord && is_string($Relationstring) && !empty($Relationstring) && $ActiverecordNew instanceof ActiveRecord) {
            $RelatArr = explode('.', $Relationstring);
            $fail = false;
            foreach ($RelatArr as $relat)
                if (isset($ActiverecordRelat->$relat))
                    $ActiverecordRelat = $ActiverecordRelat->$relat;
                else {
                    $fail = true;
                    break;
                }

            return $fail ? $ActiverecordNew : $ActiverecordRelat;
        } else
            throw new \Exception('Ошибка в Proc::RelatModelValue()');
    }

}
