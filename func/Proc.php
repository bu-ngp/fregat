<?php

namespace app\func;

use app\func\ReportsTemplate\RecoverysendaktmatReport;
use app\func\ReportsTemplate\RecoverysendaktReport;
use app\models\Config\Authuser;
use app\models\Fregat\Docfiles;
use app\models\Fregat\Fregatsettings;
use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Recoverysendakt;
use app\models\Fregat\RraDocfiles;
use app\models\Fregat\RramatDocfiles;
use Yii;
use yii\base\View;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\web\HttpException;
use yii\web\Session;
use yii\helpers\Url;
use yii\bootstrap\Html;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\db\ActiveRecord;
use yii\base\Model;
use kartik\datecontrol\DateControl;
use kartik\touchspin\TouchSpin;

/**
 * Class Proc Класс функций системы
 * @package app\func
 */
class Proc
{

    /**
     *  Константа для текстового сравнения в доп фильтре ModelSearch, используется в Filter_Compare().
     */
    const Text = 1;
    /**
     * Константа для числового сравнения в доп фильтре ModelSearch, используется в Filter_Compare().
     */
    const Number = 2;
    /**
     * Константа для строгого сравнения в доп фильтре ModelSearch, используется в Filter_Compare().
     */
    const Strict = 3;
    /**
     * Константа для сравнения с использованием массива устолвия Where ActiveQuery в доп фильтре ModelSearch, используется в Filter_Compare().
     */
    const WhereStatement = 4;
    /**
     * Константа для сравнения с использованием CheckBox в доп фильтре ModelSearch, используется в Filter_Compare().
     */
    const Mark = 5;
    /**
     * Константа для сравнения дат в доп фильтре ModelSearch, используется в Filter_Compare().
     */
    const DateRange = 6;
    /**
     * Константа для сравнения с мультивыбором из списка в доп фильтре ModelSearch, используется в Filter_Compare().
     */
    const MultiChoice = 7;
    /**
     * Константа для использования в методе WhereConstruct(), определяет что поиск осуществляется по времени.
     */
    const Time = 20;
    /**
     * Константа для использования в методе WhereConstruct(), определяет что поиск осуществляется по дате.
     */
    const Date = 21;
    /**
     * Константа для использования в методе WhereConstruct(), определяет что поиск осуществляется по дате и времени.
     */
    const DateTime = 22;


    /**
     * Функция создает массив ссылок для хлебных крошек используя сессию.
     * @param View $View Текущее представление.
     * @param array $Param Массив параметров.
     * <br>     $Param = [
     * <br>         'ClearBefore' => False (boolean) Очистить историю переходов в хлебных крошках.
     * <br>          'AddFirst' => [] (array) Массив параметров первой ссылки в хлебных крошках.
     * <br>             'Label' => (string) Имя хлебной крошки.
     * <br>             'Url' => (string) Ссылка хлебной крошки.
     * <br>         'PostFix' => '' (string) Суффикс к ID хлебной крошки.
     * <br>         'Model' => (ActiveRecord|array[ActiveRecord]) Модель ActiveRecord или массив моделей, для создания списка атрибутов в сессии, для хранения значений введеных пользователем.
     * <br>     ]
     * @return array Массив ссылок хлебных крошек.
     * @throws HttpException
     */
    public static function Breadcrumbs($View, $Param = null)
    {
        if (isset($View)) {
            $Param = $Param === null ? [] : self::array_change_key_case_recursive($Param);

            $clearbefore = isset($Param['clearbefore']) && is_bool($Param['clearbefore']) ? $Param['clearbefore'] : false;
            $addfirst = isset($Param['addfirst']) && is_array($Param['addfirst']) ? $Param['addfirst'] : [];

            $postfix = isset($Param['postfix']) ? $Param['postfix'] : '';
            $id = $View->context->module->controller->id . '/' . $View->context->module->requestedRoute . '/' . $postfix;

            $session = new Session;
            $session->open();

            // Костыль Ошибка html.sortable.min.js.map (Kartik расширение)
            if (preg_match('/html\.sortable\.min\.js\.map/', $View->context->module->requestedRoute))
                return $session['breadcrumbs'];

            if (!isset($session['breadcrumbs']))
                $session['breadcrumbs'] = [];

            $result = $session['breadcrumbs'];

            if ($clearbefore)
                $result = [];

            if (count($addfirst) > 0) {
                $addfirst['label'] = '<span class="bc_lighter"></span>' . $addfirst['label'];
                $addfirst['encode'] = false;
                $result = array_replace_recursive([
                    'addfirst' => $addfirst,
                ], $result);
            }

            if (!isset($result[$id]))
                $result[$id] = [];

            $params = Yii::$app->getRequest()->getQueryParams();
            unset($params['r']);
            $result[$id] = array_replace_recursive($result[$id], [
                'label' => '<span class="bc_lighter"></span>' . (empty($View->title) ? '-' : $View->title),
                'encode' => false,
                'url' => Url::toRoute(array_merge([$View->context->module->requestedRoute], $params)),
                'dopparams' => isset($_GET['foreignmodel']) ? [
                    'foreign' => [
                        'url' => (string)filter_input(INPUT_GET, 'url'),
                        'model' => (string)filter_input(INPUT_GET, 'foreignmodel'),
                        'field' => (string)filter_input(INPUT_GET, 'field'),
                        'id' => (string)filter_input(INPUT_GET, 'id'),
                    ]
                ] : []
            ]);

            if (isset($Param['model']) && !is_array($Param['model']))
                $Param['model'] = [$Param['model']];

            if (isset($Param['model']) && is_array($Param['model'])) {
                foreach ($Param['model'] as $model) {
                    if (!isset($result[$id]['dopparams'][$model->formName()])) {
                        $result[$id] = array_replace_recursive($result[$id], [
                            'dopparams' => [$model->formName() => $model->attributes],
                        ]);
                    } else {
                        // Массовое присваивание не походит, нужно пройти по всем атрибутам
                        foreach ($model->attributes as $attr => $value)
                            $model->$attr = $result[$id]['dopparams'][$model->formName()][$attr];
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

            /* echo '<pre class="xdebug-var-dump" style="max-height: 350px; font-size: 15px;">';
             $s1 = $_SESSION;
             unset($s1['__flash']);
             print_r($s1);
             echo '</pre>';*/

            $session->close();

            end($result);

            unset($result[key($result)]['url']);

            return $result;
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function Breadcrumbs');
    }

    /**
     * Настройки по умолчанию для DynaGrid.
     * @param array $Options массив настроек DynaGrid.
     * @return array массив настроек DynaGrid.
     */
    public static function DGopts($Options)
    {
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

    /**
     * Меняет регистр ключей массива в нижний, рекурсивно.
     * @param array $arr
     * @return array
     */
    public static function array_change_key_case_recursive($arr)
    {
        return array_map(function ($item) {
            if (is_array($item))
                $item = self::array_change_key_case_recursive($item);
            return $item;
        }, array_change_key_case($arr));
    }

    /**
     * Возвращает массив колонок для DynaGrid.
     * @param array $Params Массив параметров.
     * <br>     $Params = [
     * <br>         'ButtonsFirst' => True (boolean) Разместить кнопки действий в первой колонке.
     * <br>         'Columns' => [] (array) Настроек колонок DynaGrid.
     * <br>         'Buttons' => [] (array) Массив кнопок действий.
     * <br>             'Update' => NULL (array) Массив кнопки обновления записи.
     * <br>                 0 => (string) Url действия обновления записи.
     * <br>                 1 => Значение первичного ключа Модели (integer) Значение параметра $_GET['id'] для действия.
     * <br>             'Delete' => NULL (array) Массив кнопки удаления записи.
     * <br>                 0 => (string) Url действия удаления записи.
     * <br>                 1 => Значение первичного ключа Модели (integer) Значение параметра $_GET['id'] для действия.
     * <br>             'DeleteAjax' => NULL (array) Массив кнопки удаления записи, посредством Ajax запроса.
     * <br>                 0 => (string) Url действия удаления записи, посредством Ajax запроса.
     * <br>                 1 => Значение первичного ключа Модели (integer) Значение параметра $_GET['id'] для действия.
     * <br>             'ChooseAjax' => NULL (array) Массив кнопки выбора записи из справочника, посредством Ajax запроса.
     * <br>                 0 => (string) Url действия выбора записи из справочника, посредством Ajax запроса.
     * <br>             'ВownloadКeport' => NULL (array) Массив кнопки для скачивания отчета по ИД записи, посредством Ajax запроса.
     * <br>                 0 => (string) Url действия для скачивания отчета по ИД записи, посредством Ajax запроса.
     * <br>     ]
     * @return array Массив колонок DynaGrid.
     */
    public static function DGcols($Params)
    {
        if (isset($Params) && is_array($Params)) {
            $Params = array_change_key_case($Params);

            // Делаем строку 'template' на основе массива кнопок
            $tmpl = isset($Params['buttons']) && is_array($Params['buttons']) ? '{' . implode("} {", array_keys($Params['buttons'])) . '}' : '';

            // Если есть кнопка обновления записи
            if (isset($Params['buttons']['update']) && is_array($Params['buttons']['update'])) {
                $Params['buttons']['update'] = function ($url, $model) use ($Params) {
                    $id = isset($Params['buttons']['update'][1]) ? $model[$Params['buttons']['update'][1]] : $model->primaryKey;
                    $customurl = Yii::$app->getUrlManager()->createUrl([$Params['buttons']['update'][0], 'id' => $id]);
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning', 'data-pjax' => '0']);
                };
            }

            // Если есть кнопка удаления записи
            if (isset($Params['buttons']['delete']) && is_array($Params['buttons']['delete'])) {
                $Params['buttons']['delete'] = function ($url, $model) use ($Params) {
                    $id = isset($Params['buttons']['delete'][1]) ? $model[$Params['buttons']['delete'][1]] : $model->primaryKey;
                    $customurl = Yii::$app->getUrlManager()->createUrl([$Params['buttons']['delete'][0], 'id' => $id]);
                    return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                        'type' => 'button',
                        'title' => 'Удалить',
                        'class' => 'btn btn-xs btn-danger',
                        'onclick' => 'ConfirmDialogToAjax("Вы уверены, что хотите удалить запись?", "' . $customurl . '")'
                    ]);
                };
            }

            // Если есть кнопка удаления записи посредством ajax
            if (isset($Params['buttons']['deleteajax']) && is_array($Params['buttons']['deleteajax'])) {
                $Params['buttons']['deleteajax'] = function ($url, $model) use ($Params) {
                    $id = isset($Params['buttons']['deleteajax'][1]) ? $model[$Params['buttons']['deleteajax'][1]] : $model->primaryKey;
                    $customurl = Yii::$app->getUrlManager()->createUrl([$Params['buttons']['deleteajax'][0], 'id' => $id]);
                    return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                        'type' => 'button',
                        'title' => 'Удалить',
                        'class' => 'btn btn-xs btn-danger',
                        'onclick' => 'ConfirmDeleteDialogToAjax("Вы уверены, что хотите удалить запись?", "' . $customurl . '"' . (isset($Params['buttons']['deleteajax'][2]) ? ', "' . $Params['buttons']['deleteajax'][2] . '"' : '') . ')'
                    ]);
                };
            }

            // Если есть кнопка выбора записи посредством ajax
            if (isset($Params['buttons']['chooseajax']) && is_array($Params['buttons']['chooseajax'])) {
                $Params['buttons']['chooseajax'] = function ($url, $model) use ($Params) {
                    return Html::button('<i class="glyphicon glyphicon-ok-sign"></i>', [
                        'type' => 'button',
                        'title' => 'Выбрать',
                        'class' => 'btn btn-xs btn-success',
                        'onclick' => 'AssignValueFromGrid("' . Url::to([$Params['buttons']['chooseajax'][0]]) . '","' . $model->primarykey . '")'
                    ]);
                };
            }

            // Если есть кнопка скачивания отчета по ИД
            if (isset($Params['buttons']['downloadreport']) && is_array($Params['buttons']['downloadreport'])) {
                $Params['buttons']['downloadreport'] = function ($url, $model) use ($Params) {
                    return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                        'type' => 'button',
                        'title' => 'Скачать отчет',
                        'class' => 'btn btn-xs btn-info',
                        'onclick' => 'DownloadReport("' . Url::to([$Params['buttons']['downloadreport'][0]]) . '", null, {id: ' . $model->primaryKey . '} )'
                    ]);
                };
            }

            $mascolumns = isset($Params['columns']) && is_array($Params['columns']) ? $Params['columns'] : [];
            $masbuttons = isset($Params['buttons']) && is_array($Params['buttons']) && count($Params['buttons']) > 0 ? [
                ['class' => 'kartik\grid\ActionColumn',
                    'header' => Html::encode('Действия'),
                    'contentOptions' => ['style' => 'white-space: nowrap;'],
                    'template' => $tmpl,
                    'buttons' => is_array($Params['buttons']) ? $Params['buttons'] : [],]
            ] : [];

            $masitog = (!isset($Params['buttonsfirst']) || $Params['buttonsfirst'] === true) ? array_merge($masbuttons, $mascolumns) : $masitog = array_merge($mascolumns, $masbuttons);

            return array_merge([
                ['class' => 'kartik\grid\SerialColumn',
                    'header' => Html::encode('№'),
                ]
            ], $masitog);
        }
    }

    /**
     * Возвращает параметры для элемента Select2 (Выбор из списка с кнопкой выбора из справочника).
     * @param array $Params Набор параметров.
     * <br>     $Params = [
     * <br>         'Model' => (ActiveRecord|Model) Модель поля Select2.
     * <br>         'ResultModel' => (ActiveRecord|Model) Модель, которая возвращает значения для списка Select2.
     * <br>         'ResultRequest' => NULL (string) Url действия, который возвращает JSON строку с результатом выборки списка Select2, посредством Ajax запроса.
     * <br>         'PlaceHolder' => 'Введите значение' (string) Подсказка PlaceHolder элемента Select2.
     * <br>         'FromGridRoute' => NULL (string) Url действия с выбором из таблицы справочника. Если пусто, то кнопка "Выбор из справочника" не доступна.
     * <br>         'ThisRoute' => (string) Маршрут действия в формате Controller/Action.
     * <br>         'Fields' => (array) Параметры столбцов для запроса результатов Select2.
     * <br>             'KeyField' => (string) Ключевое поле Модели $Params['Model'] по которому ищем значения из справочника.
     * <br>             'ResultField' => NULL, если указан $Params['MethodQuery'] (string) Имя Поля-наименования по которому ищем значение.
     * <br>             'ShowResultFields' => $Params['Fields']['ResultField'] (array) Массив имен полей, которые отображаются как результат поиска Select2 через запятую.
     * <br>         'DopParams' => [] (array) Дополнительные параметры Url выбора из справочника $Params['FromGridRoute'].
     * <br>         'MethodQuery' => (string) Метод модели $Params['ResultModel'] формирующий результат ActiveQuery для значений списка Select2.
     * <br>         'MethodParams' => [] (array) Массив параметров для Ajax запроса для вывода результата поиска Select2 в JSON формате.
     * <br>         'MinimumInputLength' => 3 (integer) Минимальное количество символов введеных пользователем в Select2, чтобы вызвать Ajax запрос поиска.
     * <br>         'Form' => '' (string) Опция имени формы Select2.
     * <br>         'Options' => [] (array) Массив опций Select2.
     * <br>         'onlyAjax' => true (bool) Использовать только Ajax для загрузки данных.
     * <br>         'preloadDataAjaxCondition' => function() {} (Closure) Функция с условиями для Ajax для загрузки данных.
     * <br>         'SetSession' => True (boolean) Устанавливает класс setsession для элемента html со значением ключа select2. Позволяет сохранять значение элемента в сессии.
     * <br>         'Multiple' => [] (array) Массив параметров для мультивыбора значений из списка Select2.
     * <br>             'MultipleShowAll' => True (boolean) Показывает кнопку "Выбрать все" (При Ajax загрузке значений не актуально).
     * <br>             'IdValue' => (string) Имя поля модели $Params['ResultModel'] с ключами для мультивыбора.
     * <br>     ]
     * @return array Массив параметров для элемента Select2.
     * @throws \Exception
     */
    public static function DGselect2($Params)
    {
        if (isset($Params) && is_array($Params)) {
            $Params = self::array_change_key_case_recursive($Params);

            $model = $Params['model'];
            /** @var ActiveRecord $resultmodel */
            $resultmodel = $Params['resultmodel'];
            $resultrequest = $Params['resultrequest'];
            $placeholder = isset($Params['placeholder']) ? $Params['placeholder'] : 'Введите значение';
            $fromgridroute = $Params['fromgridroute'];
            $thisroute = $Params['thisroute'];
            $fields = $Params['fields'];
            $dopparams = isset($Params['dopparams']) ? $Params['dopparams'] : '';
            $methodquery = isset($Params['methodquery']) ? $Params['methodquery'] : '';
            $methodparams = isset($Params['methodparams']) ? $Params['methodparams'] : [];
            $minimumInputLength = isset($Params['minimuminputlength']) ? $Params['minimuminputlength'] : 3;
            $form = isset($Params['form']) ? $Params['form'] : '';
            $optionsselect2 = isset($Params['options']) ? $Params['options'] : [];
            $onlyAjax = isset($Params['onlyajax']) ? $Params['onlyajax'] : true;
            $preloadDataAjaxCondition = isset($Params['preloaddataajaxcondition']) ? $Params['preloaddataajaxcondition'] : function ($query) {
                return $query;
            };

            $setsession = isset($Params['setsession']) ? $Params['setsession'] : true;
            $multiple = isset($Params['multiple']) && is_array($Params['multiple']) ? $Params['multiple'] : [];
            $showToggleAll = isset($Params['multiple']['multipleshowall']) ? $Params['multiple']['multipleshowall'] : true;

            $ajaxparamsString = '';
            foreach ($methodparams as $key => $value)
                $ajaxparamsString .= ',' . $key . ': ' . $value;

            if (empty($fields['showresultfields'])/* && empty($methodquery)*/)
                $fields['showresultfields'] = [$fields['resultfield']];

            $errorstring = '';
            if (empty($model))
                $errorstring .= 'empty($model); ';
            if (empty($resultmodel))
                $errorstring .= 'empty($resultmodel); ';
            if (empty($fields['keyfield']))
                $errorstring .= 'empty($fields[\'keyfield\']); ';
            if (empty($fields['resultfield']))
                $errorstring .= 'empty($fields[\'resultfield\']); ';
            if (empty($methodquery))
                $errorstring .= 'empty($Params[\'methodquery\']); ';
            if (empty($thisroute))
                $errorstring .= 'empty($thisroute); ';
            if (empty($multiple))
                $errorstring .= 'empty($multiple); ';
            if (isset($multiple['idvalue']))
                $errorstring .= 'isset($multiple[\'idvalue\']); ';

            if (!empty($model) && !empty($resultmodel) && !empty($fields['keyfield']) && !(empty($fields['resultfield']) && empty($methodquery)) && !empty($thisroute) && (!empty($multiple) && isset($multiple['idvalue']) || empty($multiple))) {

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
                        if (empty($multiple))
                            $initrecord_tmp[] = implode(', ', $rows);
                        else
                            $initrecord_tmp[$initrecord[$key][$multiple['idvalue']]] = implode(', ', $rows);
                    }
                    $initrecord = empty($multiple) ? ['text' => $initrecord_tmp[0]] : $initrecord_tmp;
                }
                $query = $resultmodel::find();
                $query = $preloadDataAjaxCondition($query);
                $countRecords = $onlyAjax ? 0 : $query->count();

                $needAjax = $countRecords > 100;

                $query = $resultmodel::find();
                $query = $preloadDataAjaxCondition($query);

                $data = $onlyAjax || $needAjax ? [] : \yii\helpers\ArrayHelper::map($query
                    ->select(array_merge([$resultmodel->primaryKey()[0]], ['CONCAT_WS(", ", ' . implode(',', $fields['showresultfields']) . ') AS text']))
                    ->asArray()
                    ->all(),
                    $resultmodel->primaryKey()[0], 'text');

                $a = '';

                return array_merge(
                    $onlyAjax || $needAjax ? [] : [
                        'data' => $data,
                    ], [
                    'initValueText' => !empty($multiple) ? '' : implode(', ', ['text' => $initrecord['text']]),
                    'options' => empty($optionsselect2) ? array_merge(['placeholder' => $placeholder, 'class' => 'form-control' . ($setsession ? ' setsession' : ''), 'disabled' => isset($Params['disabled']) && $Params['disabled'] === true], empty($form) ? [] : ['form' => $form], empty($multiple) ? [] : ['multiple' => true]) : $optionsselect2,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'showToggleAll' => $showToggleAll,
                    'pluginOptions' => array_merge(
                        $onlyAjax || $needAjax ? [
                            'minimumInputLength' => $minimumInputLength,
                            'ajax' => [
                                'url' => Url::to([$resultrequest]),
                                'dataType' => 'json',
                                'data' => empty($methodquery) ? new JsExpression('function(params) { return {q:params.term, field: "' . $fields['resultfield'] . '", showresultfields: ' . json_encode($fields['showresultfields']) . '' . $ajaxparamsString . ' } }') : new JsExpression('function(params) { return {q:params.term' . $ajaxparamsString . '} }'),
                            ],
                        ] : [],
                        [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        ]
                    ),
                ], !empty($fromgridroute) && (empty($Params['disabled']) || $Params['disabled'] === false) ? [
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
                    ]] : [], !empty($multiple) && ($onlyAjax || $needAjax) ? [
                    'data' => $initrecord
                ] : []
                );
            } else
                throw new \Exception('Ошибка в Proc::DGselect2(): ' . $errorstring);
        }
    }

    /**
     * Выводит массив наденных данных для Select2 элемента
     * @param array $Params Массив параметров
     * <br>     $Params => [
     * <br>         'Model' => (ActiveRecord) Модель, в которой ищем данные.
     * <br>         'Field' => NULL (string) Имя атрибута, по которому осуществляем поиск. Не обязательно, если указан параметр $Params['MethodQuery'].
     * <br>         'q' => NULL (string) Строка поиска, отправленная пользователем.
     * <br>         'ShowResultFields' => $Params['Field'] (array) Набор полей, для вывода в результирующий набор Select2 через запятую. Актуально, если указан параметр $Params['Field'].
     * <br>         'Order' => NULL (string|array) Строка или массив сортировки ActiveQuery->Order(). Актуально, если указан параметр $Params['Field'].
     * <br>         'MethodQuery' => NULL (string) Метод модели $Params['Model'], выводящий массив найденных значений. Не обязательно, если указан параметр $Params['Field'].
     * <br>         'MethodParams' => ['q' => $Params['q']] (array) Массив параметров метода модели $Params['Model']->$Params['MethodQuery']($Params['MethodParams']). Актуально, если указан параметр $Params['MethodQuery'].
     * <br>     ]
     * @return array ['results' => ['id' => ID найденного значения, 'text' => Текст найденного значения]] Вывод массива найденных значений.
     * @throws \Exception
     */
    public static function ResultSelect2($Params)
    {
        if (isset($Params) && is_array($Params) && $Params['model'] instanceof ActiveRecord && (is_string($Params['field']) || isset($Params['methodquery']))) {
            $Params = self::array_change_key_case_recursive($Params);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $Out = ['results' => ['id' => '', 'text' => '']];
            $Model = $Params['model'];
            if (!isset($Params['showresultfields']))
                $Params['showresultfields'] = [$Params['field']];

            $Params['showresultfields'] = implode(', ', $Params['showresultfields']);
            if (isset($Params['q'])) {
                if (is_string($Params['methodquery']) && (!isset($Params['methodparams']) || is_array($Params['methodparams']))) {
                    $Params['methodparams']['q'] = $Params['q'];

                    $Out['results'] = $Model->$Params['methodquery']($Params['methodparams']);
                    if (!is_array($Out['results']))
                        exit;
                } else {
                    $Out['results'] = $Model::find()
                        ->select([$Model::primaryKey()[0] . ' AS id', 'CONCAT_WS(", ", ' . $Params['showresultfields'] . ') AS text'])
                        ->where(['like', $Params['field'], $Params['q']])
                        ->orderBy(is_string($Params['order']) || is_array($Params['order']) ? $Params['order'] : [])
                        ->limit(20)
                        ->asArray()
                        ->all();
                }
            }
            return $Out;
        } else
            throw new \Exception('Ошибка в Proc::ResultSelect2()');
    }

    /**
     * Удаляет последний элемент массива хлебных крошек из сессии.
     */
    public static function RemoveLastBreadcrumbsFromSession()
    {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        unset($bc[key($bc)]);
        $session['breadcrumbs'] = $bc;
        $session->close();
    }

    /**
     * Возвращает массив хлебных крошек из сессии.
     * @return array
     */
    public static function GetBreadcrumbsFromSession()
    {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        $session->close();
        return $bc;
    }

    /**
     * Возвращает предпоследний элемент хлебных крошек из сессии.
     * @return array
     */
    public static function GetPreviusBreadcrumbsFromSession()
    {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        prev($bc);
        $session->close();
        return $bc[key($bc)];
    }

    /**
     * Возвращает последний элемент хлебных крошек из сессии.
     * @return array
     */
    public static function GetLastBreadcrumbsFromSession()
    {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        $session->close();
        return $bc[key($bc)];
    }

    /**
     * Возвращает предпоследний URL из хлебных крошек из сессии.
     * @return string
     */
    public static function GetPreviousURLBreadcrumbsFromSession()
    {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        unset($bc['addfirst']);
        if (count($bc) > 1) {
            end($bc);
            prev($bc);
            $session->close();
            return isset($bc[key($bc)]['url']) ? $bc[key($bc)]['url'] : '';
        } else
            return Yii::$app->homeUrl;
    }

    /**
     * Возвращает последний URL из хлебных крошек из сессии.
     * @return string
     */
    public static function GetLastURLBreadcrumbsFromSession()
    {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        $session->close();
        return isset($bc[key($bc)]['url']) ? $bc[key($bc)]['url'] : '';
    }

    /**
     * Сохранить значения атрибутов модели в сессии в хлебной крошке.
     * @param ActiveRecord|Model $Model Модель, атрибуты которой будут сохранены в сессии в последней хлебной крошки.
     * @param bool $PreviusBC Если True, то сохранить в сессии предыдущей хлебной крошки.
     * @throws \Exception
     */
    public static function SetSessionValuesFromAR($Model, $PreviusBC = false)
    {
        if ($Model instanceof ActiveRecord) {
            $BC = self::GetBreadcrumbsFromSession();
            end($BC);
            if ($PreviusBC)
                prev($BC);

            foreach ($Model as $attr => $value)
                $BC[key($BC)]['dopparams'][$Model->formName()][$attr] = $value;
            $session = new Session;
            $session->open();
            $session['breadcrumbs'] = $BC;
            $session->close();
        } else
            throw new \Exception('Ошибка в Proc::SetSessionValuesFromAR()');
    }

    /**
     * Формирует массив настроек для Nav::Widget(['items' => Proc::GetMenuButtons($this)]).
     * @param View $View Текущее представление.
     * @return array Массив конфигурации меню навигации.
     */
    public static function GetMenuButtons($View)
    {
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
            if ($url === $View->context->module->requestedRoute) {
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

    /**
     * Устанавливает начальную группу кнопок для определенной системы портала, применяется в контроллерах.
     * @param string $ButtonsGroup Определяет начальную группу кнопок по имени системы.
     * 1) 'fregat' - Система "Фрегат".
     * 2) 'config' - Настройки портала.
     * 3) 'glauk' - Регистр глаукомных пациентов.
     */
    public static function SetMenuButtons($ButtonsGroup)
    {
        $session = new Session;
        $session->open();
        $session['menubuttons'] = $ButtonsGroup;
        $session->close();
    }

    /**
     * Функция preg_match_all с использованием заданной кодироки.
     * @param $ps_pattern
     * @param $ps_subject
     * @param $pa_matches
     * @param $pn_flags
     * @param int $pn_offset
     * @param null $ps_encoding
     * @return int
     */
    static function mb_preg_match_all($ps_pattern, $ps_subject, &$pa_matches, $pn_flags = PREG_PATTERN_ORDER, $pn_offset = 0, $ps_encoding = NULL)
    {
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
     * @param string $FileRoot Путь к файлу
     * @return string Новое имя файла.
     */
    static function SaveFileIfExists($FileRoot)
    {
        $counter = 1;
        $filename = substr($FileRoot, strpos($FileRoot, '/') + 1);

        while (file_exists($FileRoot)) {
            preg_match('/(.+\/)(.+?)((\(.+)?\.)(.+)/i', $FileRoot, $file_arr);
            // $file_arr[1] - Директория, $file_arr[2] - Имя файла, end($file_arr) - Расширение файла
            $FileRoot = $file_arr[1] . $file_arr[2] . '(' . $counter . ')' . '.' . end($file_arr);
            $filename = $file_arr[2] . '(' . $counter . ')' . '.' . end($file_arr);
            $counter++;
        }

        return $filename;
    }

    /**
     * Формирует массив конструкции Where() ActiveQuery.
     * @param ActiveRecord $ModelSearch Модель, для который создаем конструкцию Where().
     * @param string $Field Атрибут модели, по которому осуществляем поиск, фильтрацию.
     * @param integer $Type Тип значения в атрибуте модели (Proc::Time, Proc::Date, Proc::DateTime).
     * @param string $SQLField SQL выражение вместо имени поля, для вычисляемых полей. По умолчанию пусто.
     * @return array Массив конструкции Where().
     */
    static function WhereConstruct($ModelSearch, $Field, $Type = 0, $SQLField = '')
    {
        $AttributeModelValue = $ModelSearch->getAttribute($Field);
        $AttributeValue = empty($AttributeModelValue) ? $ModelSearch->$Field : $ModelSearch->getAttribute($Field);

        preg_match('/(>=|<=|>|<|=)?(.*)/', $AttributeValue, $Matches);
        $Operator = $Matches[1];
        $Value = $Matches[2];

        if (!empty($Value))
            switch ($Type) {
                case self::Time:
                    $Value = date("H:i:s", strtotime($Value));
                    break;
                case self::Date:
                    $Value = date("Y-m-d", strtotime($Value));
                    break;
                case self::DateTime:
                    $Value = date("Y-m-d H:i:s", strtotime($Value));
                    break;
            }

        preg_match('/(.+\.)?((.+)\.(.+))$|(.+)/', $Field, $Matches);

        $Field = empty($Matches[2]) ? $Field : $Matches[2];

        $Field = $SQLField ?: $Field;

        return [empty($Operator) ? '=' : $Operator, $Field, $Value];
    }

    /**
     * Метод транслитирирует русские символы на латинский.
     * @param string $string Строка, которую необходимо транслитирировать.
     * @return string Строка результат транслитерации.
     */
    static function Translit($string)
    {
        $replace = array(
            "'" => "",
            "`" => "",
            "а" => "a", "А" => "a",
            "б" => "b", "Б" => "b",
            "в" => "v", "В" => "v",
            "г" => "g", "Г" => "g",
            "д" => "d", "Д" => "d",
            "е" => "e", "Е" => "e",
            "ё" => "e", "Ё" => "e",
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

    /**
     * Фукния создает Логин пользователя на основе полного ФИО.
     * Например, $Fullname = 'Иванов Петр Сергеевич' выведет 'IvanovPS'.
     * Если логин 'IvanovPS' существует, то метод добавит количество совпадающих логинов в конце результата, т.е. IvanovPS1, IvanovPS2, и т.д.
     * @param $Fullname ФИО пользователя полностью.
     * @return string Преобразованный логин.
     */
    public static function CreateLogin($Fullname)
    {
        preg_match('/(\w+)\s?(\w+)?\s?(\w+)?/ui', $Fullname, $matches);
        $result = '';

        if (!empty($matches[1]))
            $result .= ucfirst(self::Translit($matches[1]));
        if (!empty($matches[2]))
            $result .= ucfirst(self::Translit(mb_substr($matches[2], 0, 1, 'UTF-8')));
        if (!empty($matches[3]))
            $result .= ucfirst(self::Translit(mb_substr($matches[3], 0, 1, 'UTF-8')));

        $count = Authuser::find()
            ->where(['like', 'auth_user_login', $result . '%', false])
            ->count();

        return $count > 0 ? $result . $count : $result;
    }

    /**
     * @param $DataProvider
     * @param null $fields
     * @param null $LabelValues
     * @return array
     */
    public static function GetAllLabelsFromAR($DataProvider, $fields = NULL, $LabelValues = NULL)
    {
        $labels = [];
        $cls_ar = class_exists($DataProvider->query->modelClass) ? new $DataProvider->query->modelClass : false;
        if ($cls_ar instanceof ActiveRecord) {
            if (!is_array($fields))
                $fields = $cls_ar->attributes;

            array_walk($fields, function ($value, $key) use (&$labels, $cls_ar, $LabelValues) {
                $labels[$key] = !empty($LabelValues) && property_exists($LabelValues, $key) ? $LabelValues->$key : $cls_ar->getAttributeLabel($key);
            });
        }
        return $labels;
    }

    /**
     * @param $Activerecord
     * @param null $fields
     * @return array
     */
    public static function GetAllDataFromAR($Activerecord, $fields = null)
    {
        if (!is_array($fields))
            $fields = [];

        $data = [];
        $cls_ar = $Activerecord;

        if ($cls_ar instanceof ActiveRecord) {

            if (!is_array($fields))
                $fields = $cls_ar->attributes;

            array_walk($fields, function ($value, $key) use (&$data, $cls_ar) {
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

    /**
     * @param $num
     * @return string
     */
    public static function getNameFromNumber($num)
    {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }

    /**
     * @param $dataProvider
     * @param $modelName
     * @param $reportName
     * @param null $selectvalues
     * @param null $ModelFilter
     * @param null $LabelValues
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public static function Grid2Excel($dataProvider, $modelName, $reportName, $selectvalues = NULL, $ModelFilter = NULL, $LabelValues = NULL)
    {
        $Importconfig = Importconfig::findOne(1);

        ini_set('max_execution_time', $Importconfig['max_execution_time']);  // 1000 seconds
        ini_set('memory_limit', $Importconfig['memory_limit']); // 1Gbyte Max Memory

        $objPHPExcel = new \PHPExcel;

        /* Границы таблицы */
        $ramka = array(
            'borders' => [
                'allborders' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                ],
            ],
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
        $selectvalues = (array)$selectvalues;
        $labels = self::GetAllLabelsFromAR($dataProvider, $fields[$modelName], $LabelValues);

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
        if (count((array)$dataProvider->getModels()) > 0) {
            foreach ($dataProvider->getModels() as $row => $ar) {
                $r++;
                // Названия полей
                if ($row === 0) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $r - 1, '№');
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $r - 1)->applyFromArray($font);
                    foreach ($labels as $label) {
                        $i++;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r - 1, $label);
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r - 1)->applyFromArray($font);
                    }
                }

                $data = self::GetAllDataFromAR($ar, $fields[$modelName]);
                $i = 0;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r, $r - 5);
                foreach (array_keys($data) as $attr) {
                    $i++;
                    $ValidatorsAttr = $ar->getActiveValidators($attr);
                    array_walk($ValidatorsAttr, function (&$val) {
                        $val = (new \ReflectionClass($val::className()))->getShortName();
                    });

                    if (isset($selectvalues[$modelName . '[' . $attr . ']']))
                        $data[$attr] = $selectvalues[$modelName . '[' . $attr . ']'][$data[$attr]];
                    if (in_array('StringValidator', $ValidatorsAttr))
                        $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($i, $r, isset($data[$attr]) ? $data[$attr] : '', \PHPExcel_Cell_DataType::TYPE_STRING);
                    else
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r, isset($data[$attr]) ? $data[$attr] : '');
                }
                $objPHPExcel->getActiveSheet()->getStyle('A5:' . self::getNameFromNumber($i) . $r)->applyFromArray($ramka);
            }
        } else {
            $r++;
            $labels = self::GetAllLabelsFromAR($dataProvider, $fields[$modelName], $LabelValues);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $r - 1, '№');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $r - 1)->applyFromArray($font);
            foreach ($labels as $label) {
                $i++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $r - 1, $label);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i, $r - 1)->applyFromArray($font);
            }
            $objPHPExcel->getActiveSheet()->getStyle('A5:' . self::getNameFromNumber($i) . ($r - 1))->applyFromArray($ramka);
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

    /**
     * @param $KeyName
     * @param $Obj
     * @return array
     */
    public static function GetArrayValuesByKeyName($KeyName, $Obj)
    {
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
    /**
     * @param $ModelGridName
     * @param $ModelFilter
     * @return string
     * @throws HttpException
     */
    public static function SetFilter($ModelGridName, $ModelFilter)
    {
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
    /**
     * @param $ModelGridName
     * @param $ModelFilter
     * @return bool
     * @throws HttpException
     */
    public static function PopulateFilterForm($ModelGridName, &$ModelFilter)
    {
        if (is_string($ModelGridName) && $ModelFilter instanceof Model) {
            $result = false;
            $session = new Session;
            $session->open();
            if (isset($session['_filter'][$ModelGridName][$ModelFilter->formName()])) {
                $Filed = $ModelFilter->load($session['_filter'][$ModelGridName]);
                foreach ($ModelFilter->attributes as $attr => $val) {
                    if (strrpos($attr, '_not') === strlen($attr) - 4 && $val == '1') {
                        $attr2 = substr($attr, 0, strlen($attr) - 4);
                        if ($ModelFilter->hasProperty($attr2) && empty($ModelFilter->$attr2))
                            $ModelFilter->$attr = null;
                    }
                }
                $result = $Filed;
            }

            $session->close();
            return $result;
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function PopulateFilterForm');
    }

    // Получаем значения полей фильтра
    /**
     * @param $ModelGridName
     * @param $ModelFilterName
     * @return array
     * @throws HttpException
     */
    public static function GetFilter($ModelGridName, $ModelFilterName)
    {
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
    /**
     * @param $AR
     * @return string
     */
    private static function ConstructFilterOutput($AR)
    {
        $session = new Session;
        $session->open();
        $filter = '';

        if (isset($session['_filter'])) {
            foreach ($session['_filter'] as $filtform) {
                foreach ($filtform as $filtformname => $fields) {
                    if ($filtformname === $AR->formName()) {
                        foreach ($fields as $attr => $value)
                            if ((!empty($value) || strpos($attr, '_beg') === strlen($attr) - 4) && strpos($attr, '_znak') !== strlen($attr) - 5 && strpos($attr, '_end') !== strlen($attr) - 4 && strpos($attr, '_not') !== strlen($attr) - 4)
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

                                    $not = !empty($fields[$attr . '_not']) ? 'НЕ ' : '';

                                    $value = implode(', ', $value);
                                    $filter .= ' ' . $AR->attributeLabels()[$attr] . ' ' . $znak . ' "' . $not . $value . '";';
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
    /**
     * @param $attrvar
     * @param $modelname
     * @param $attrname
     * @return bool
     */
    public static function GetValueForFillARs(&$attrvar, $modelname, $attrname)
    {
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

    /**
     * @param $Form
     * @param $ActiveRecord
     * @param $FieldName
     * @return mixed
     */
    public static function FilterFieldDate($Form, $ActiveRecord, $FieldName)
    {
        return $Form->field($ActiveRecord, $FieldName)->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE,
            'options' => [
                'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
            ],
            'saveOptions' => ['class' => 'form-control'],
        ]);
    }

    /**
     * @param $Form
     * @param $ActiveRecord
     * @param $FieldName
     * @param null $Options
     */
    public static function FilterFieldIntCondition($Form, $ActiveRecord, $FieldName, $Options = NULL)
    {
        if (!is_array($Options))
            $Options = [];

        echo '<div class="form-group"><label class="control-label" for="' . $ActiveRecord->formName() . '-' . $FieldName . '">';
        echo $ActiveRecord->getAttributeLabel($FieldName);
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

    /**
     * @param $Form
     * @param $ActiveRecord
     * @param $FieldName
     * @return mixed
     */
    public static function SetTemplateForActiveFieldWithNOT($Form, $ActiveRecord, $FieldName)
    {
        $field = $Form->field($ActiveRecord, $FieldName . '_not')->checkbox()->label('НЕ', ['class' => 'labelbold']);
        $field->template = "<div class=\"checkbox\">{beginLabel}{input}{labelTitle}{endLabel}{hint}</div>";
        return $field;
    }

    /**
     * @param $Form
     * @param $ActiveRecord
     * @param $FieldName
     * @param $PlaceHolder
     * @return mixed
     */
    public static function FilterFieldSelectSingle($Form, $ActiveRecord, $FieldName, $PlaceHolder)
    {
        if (method_exists($ActiveRecord, 'VariablesValues'))
            return $Form->field($ActiveRecord, $FieldName)->widget(Select2::classname(), array_merge([
                'hideSearch' => true,
                'data' => $ActiveRecord::VariablesValues($FieldName),
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['placeholder' => $PlaceHolder, 'class' => 'form-control'],
                'theme' => Select2::THEME_BOOTSTRAP,
            ], $ActiveRecord->hasProperty($FieldName . '_not') ? [
                'addon' => [
                    'prepend' => [
                        'content' => self::SetTemplateForActiveFieldWithNOT($Form, $ActiveRecord, $FieldName),
                    ],
                    'groupOptions' => [
                        'class' => 'notforselect2',
                    ],
                ],
            ] : []));
    }

    /**
     * @param $Form
     * @param $ActiveRecord
     * @param $FieldName
     * @param $PlaceHolder
     * @return mixed
     */
    public static function FilterFieldSelectMultiple($Form, $ActiveRecord, $FieldName, $PlaceHolder)
    {
        if (method_exists($ActiveRecord, 'VariablesValues'))
            return $Form->field($ActiveRecord, $FieldName)->widget(Select2::classname(), array_merge([
                'hideSearch' => true,
                'data' => $ActiveRecord::VariablesValues($FieldName),
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['placeholder' => $PlaceHolder, 'class' => 'form-control', 'multiple' => true],
                'theme' => Select2::THEME_BOOTSTRAP,
            ], $ActiveRecord->hasProperty($FieldName . '_not') ? [
                'addon' => [
                    'prepend' => [
                        'content' => self::SetTemplateForActiveFieldWithNOT($Form, $ActiveRecord, $FieldName),
                    ],
                    'groupOptions' => [
                        'class' => 'notforselect2',
                    ],
                ],
            ] : []));
    }

    /**
     * @param $Form
     * @param $ActiveRecord
     * @param $FieldName
     */
    public static function FilterFieldDateRange($Form, $ActiveRecord, $FieldName)
    {
        echo '<div class="form-group"><label class="control-label" for="' . strtolower($ActiveRecord->formName()) . '-' . $FieldName . '_beg">';
        echo $ActiveRecord->getAttributeLabel($FieldName . '_beg');
        echo '</label><div class="row"><div class="col-xs-6">';
        echo $Form->field($ActiveRecord, $FieldName . '_beg', [
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ОТ</span>{input}</div>'
        ])->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE,
            'options' => [
                'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
            ],
            'saveOptions' => ['class' => 'form-control'],
        ])->label(false);
        echo '</div><div class="col-xs-6">';
        echo $Form->field($ActiveRecord, $FieldName . '_end', [
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ДО</span>{input}</div>'
        ])->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE,
            'options' => [
                'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
            ],
            'saveOptions' => ['class' => 'form-control'],
        ])->label(false);
        echo '</div></div></div>';
    }

    // Присваеиват сортировку реляционным атрибутам по массиву списку атрибутов
    /**
     * @param $DataProvider
     * @param $AttributesNames
     */
    public static function AssignRelatedAttributes(&$DataProvider, $AttributesNames)
    {
        if ($DataProvider instanceof ActiveDataProvider && is_array($AttributesNames))
            foreach ($AttributesNames as $key => $val) {
                if (is_string($key)) {
                    preg_match('/(\.?\w+)$/', $key, $matches);
                    $attrsql = $val . $matches[1];
                } else {
                    preg_match('/(\w+\.?\w+)$/', $val, $matches);
                    $attrsql = $matches[1];
                }

                $DataProvider->sort->attributes[is_string($key) ? $key : $val] = [
                    'asc' => [$attrsql => SORT_ASC],
                    'desc' => [$attrsql => SORT_DESC],
                ];
            }
    }

    /**
     * Присваивает выбранное значение из справочника модели, в сессии.
     * При выборе значения из справочника, значение присваивается в сессию предыдущей хлебной крошки, для формы, с которой был открыт справочник.
     * @param bool $RedirectPreviousUrl
     * @param ActiveRecord $ActiveRecord Модель к которой присваивается знаечния из справочника.
     * @param string $AttributeForeignID Имя атрибута
     * @return string
     */
    public static function AssignToModelFromGrid($RedirectPreviousUrl = True, $ActiveRecord = NULL, $AttributeForeignID = NULL)
    {
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

                if ($RedirectPreviousUrl)
                    Yii::$app->response->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
            } else
                return 'error foreign or assigndata empty AssignToModelFromGrid()';
        }
    }

    // Меняет раскладку клавиатуры
    /**
     * @param $text
     * @param int $arrow
     * @return string
     */
    public static function switcher($text, $arrow = 0)
    {
        $str[0] = array('й' => 'q', 'ц' => 'w', 'у' => 'e', 'к' => 'r', 'е' => 't', 'н' => 'y', 'г' => 'u', 'ш' => 'i', 'щ' => 'o', 'з' => 'p', 'х' => '[', 'ъ' => ']', 'ф' => 'a', 'ы' => 's', 'в' => 'd', 'а' => 'f', 'п' => 'g', 'р' => 'h', 'о' => 'j', 'л' => 'k', 'д' => 'l', 'ж' => ';', 'э' => '\'', 'я' => 'z', 'ч' => 'x', 'с' => 'c', 'м' => 'v', 'и' => 'b', 'т' => 'n', 'ь' => 'm', 'б' => ',', 'ю' => '.', 'Й' => 'Q', 'Ц' => 'W', 'У' => 'E', 'К' => 'R', 'Е' => 'T', 'Н' => 'Y', 'Г' => 'U', 'Ш' => 'I', 'Щ' => 'O', 'З' => 'P', 'Х' => '[', 'Ъ' => ']', 'Ф' => 'A', 'Ы' => 'S', 'В' => 'D', 'А' => 'F', 'П' => 'G', 'Р' => 'H', 'О' => 'J', 'Л' => 'K', 'Д' => 'L', 'Ж' => ';', 'Э' => '\'', '?' => 'Z', 'ч' => 'X', 'С' => 'C', 'М' => 'V', 'И' => 'B', 'Т' => 'N', 'Ь' => 'M', 'Б' => ',', 'Ю' => '.',);
        $str[1] = array('q' => 'й', 'w' => 'ц', 'e' => 'у', 'r' => 'к', 't' => 'е', 'y' => 'н', 'u' => 'г', 'i' => 'ш', 'o' => 'щ', 'p' => 'з', '[' => 'х', ']' => 'ъ', 'a' => 'ф', 's' => 'ы', 'd' => 'в', 'f' => 'а', 'g' => 'п', 'h' => 'р', 'j' => 'о', 'k' => 'л', 'l' => 'д', ';' => 'ж', '\'' => 'э', 'z' => 'я', 'x' => 'ч', 'c' => 'с', 'v' => 'м', 'b' => 'и', 'n' => 'т', 'm' => 'ь', ',' => 'б', '.' => 'ю', 'Q' => 'Й', 'W' => 'Ц', 'E' => 'У', 'R' => 'К', 'T' => 'Е', 'Y' => 'Н', 'U' => 'Г', 'I' => 'Ш', 'O' => 'Щ', 'P' => 'З', '[' => 'Х', ']' => 'Ъ', 'A' => 'Ф', 'S' => 'Ы', 'D' => 'В', 'F' => 'А', 'G' => 'П', 'H' => 'Р', 'J' => 'О', 'K' => 'Л', 'L' => 'Д', ';' => 'Ж', '\'' => 'Э', 'Z' => '?', 'X' => 'ч', 'C' => 'С', 'V' => 'М', 'B' => 'И', 'N' => 'Т', 'M' => 'Ь', ',' => 'Б', '.' => 'Ю',);
        return strtr($text, isset($str[$arrow]) ? $str[$arrow] : array_merge($str[0], $str[1]));
    }

    // Используется для полей формы со связью, чтобы укоротить код (isset($model->idTrosnov->idMattraffic->idMaterial) ? $model->idTrosnov->idMattraffic->idMaterial : new Material)
    /**
     * @param $ActiverecordRelat
     * @param $Relationstring
     * @param $ActiverecordNew
     * @return mixed|Model|ActiveRecord
     * @throws \Exception
     */
    public static function RelatModelValue($ActiverecordRelat, $Relationstring, $ActiverecordNew)
    {
        if (($ActiverecordRelat instanceof ActiveRecord || $ActiverecordRelat instanceof Model) && is_string($Relationstring) && !empty($Relationstring) && ($ActiverecordNew instanceof ActiveRecord || $ActiverecordNew instanceof Model)) {
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

    /**
     * @param $Typereport
     */
    public static function SendReportAkt($Typereport)
    {
        $dopparams = json_decode(Yii::$app->request->post()['dopparams']);
        if (!empty($dopparams)) {
            $email = Recoverysendakt::findOne($dopparams->id)->idOrgan->organ_email;
            if (!empty($email)) {
                $Report = ($Typereport === 1) ? new RecoverysendaktReport() : new RecoverysendaktmatReport();
                $Report->setDirectoryFiles('tmpfiles');
                $filename = $Report->Execute();
                $fnutf8 = $filename;
                $fregatsettings = Fregatsettings::findOne(1);

                $fl = (DIRECTORY_SEPARATOR === '/') ? ('tmpfiles/' . $filename) : mb_convert_encoding('tmpfiles/' . $filename, 'Windows-1251', 'UTF-8');

                Yii::$app->mailer->compose('//Fregat/recoverysendakt/_send', [
                    'filename' => $filename,
                ])
                    ->setFrom($fregatsettings->fregatsettings_recoverysend_emailfrom)
                    ->setTo([
                        YII_DEBUG ? 'karpovvv@mugp-nv.ru' : Recoverysendakt::findOne($dopparams->id)->idOrgan->organ_email,
                    ])
                    ->setSubject($fregatsettings->fregatsettings_recoverysend_emailtheme)
                    ->attach($fl, ['fileName' => $fnutf8])
                    ->send();
                echo $fnutf8;
            } else
                throw new \Exception('У организации ' . Recoverysendakt::findOne($dopparams->id)->idOrgan->organ_name . ' отсутствует E-mail');
        } else
            throw new \Exception('Не передан параметр POST dopparams');
    }

    /**
     * @param $Type integer
     * @param $ActiveQuery ActiveQuery Запрос к которму применяется фильтр
     * @param $FilterValues array Массив атрибутов модели со значениями фильтров
     * @param $Params array
     */
    public static function Filter_Compare($Type, &$ActiveQuery, $FilterValues, $Params = [])
    {
        if (is_integer($Type) && $ActiveQuery instanceof ActiveQuery && is_array($FilterValues) && is_array($Params) && isset($Params['Attribute']) && is_string($Params['Attribute'])) {
            $Attribute = $Params['Attribute'];
            $SQLAttribute = !empty($Params['SQLAttribute']) && is_string($Params['SQLAttribute']) ? $Params['SQLAttribute'] : $Attribute;
            $Value = $FilterValues[$Attribute];
            $ExistsSubQuery = isset($Params['ExistsSubQuery']) ? $Params['ExistsSubQuery'] : NULL;

            if (!empty($Value) || $Type === Proc::DateRange)
                switch ($Type) {
                    case Proc::Text:
                        $LikeManual = isset($Params['LikeManual']) ? $Params['LikeManual'] : true;
                        if (empty($ExistsSubQuery))
                            $ActiveQuery->andFilterWhere(['LIKE', $Attribute, $FilterValues[$Attribute], !$LikeManual]);
                        else {
                            $ExistsSubQuery->andFilterWhere(['LIKE', $SQLAttribute, $FilterValues[$Attribute]], !$LikeManual);
                            $ActiveQuery->andWhere(['exists', $ExistsSubQuery]);
                        }
                        break;
                    case Proc::Number:
                        $znak = $Attribute . '_znak';

                        if (!empty($FilterValues[$znak]))
                            if (empty($ExistsSubQuery))
                                $ActiveQuery->andWhere($SQLAttribute . ' ' . $FilterValues[$znak] . ' ' . $FilterValues[$Attribute]);
                            else {
                                $ExistsSubQuery->andWhere($SQLAttribute . ' ' . $FilterValues[$znak] . ' ' . $FilterValues[$Attribute]);
                                $ActiveQuery->andWhere(['exists', $ExistsSubQuery]);
                            }
                        break;
                    case Proc::Strict:
                        $FilterWhere = empty($FilterValues[$Attribute . '_not']) ? [$SQLAttribute => $FilterValues[$Attribute]] : ['not', [$SQLAttribute => $FilterValues[$Attribute]]];

                        if (empty($ExistsSubQuery))
                            $ActiveQuery->andFilterWhere($FilterWhere);
                        else {
                            $ExistsSubQuery->andFilterWhere($FilterWhere);
                            $ActiveQuery->andWhere(['exists', $ExistsSubQuery]);
                        }
                        break;
                    case Proc::WhereStatement:
                        $WhereStatement = isset($Params['WhereStatement']) ? $Params['WhereStatement'] : NULL;
                        if (!empty($WhereStatement))
                            if (empty($ExistsSubQuery))
                                $ActiveQuery->andWhere($WhereStatement);
                            else {
                                $ExistsSubQuery->andWhere($WhereStatement);
                                $ActiveQuery->andWhere(['exists', $ExistsSubQuery]);
                                $a = '';
                            }
                        break;
                    case Proc::Mark:
                        $WhereStatement = isset($Params['WhereStatement']) ? $Params['WhereStatement'] : NULL;
                        if (!empty($WhereStatement) && $FilterValues[$Attribute] === '1')
                            if (empty($ExistsSubQuery))
                                $ActiveQuery->andWhere($WhereStatement);
                            else {
                                $ExistsSubQuery->andWhere($WhereStatement);
                                $ActiveQuery->andWhere(['exists', $ExistsSubQuery]);
                            }
                        break;
                    case Proc::DateRange:

                        if (!empty($FilterValues[$Attribute . '_beg']) && !empty($FilterValues[$Attribute . '_end'])) {
                            if (empty($ExistsSubQuery))
                                $ActiveQuery->andFilterWhere(['between', new Expression('CAST(' . $SQLAttribute . ' AS DATE)'), $FilterValues[$Attribute . '_beg'], $FilterValues[$Attribute . '_end']]);
                            else {
                                $ExistsSubQuery->andFilterWhere(['between', new Expression('CAST(' . $SQLAttribute . ' AS DATE)'), $FilterValues[$Attribute . '_beg'], $FilterValues[$Attribute . '_end']]);
                                $ActiveQuery->andWhere(['exists', $ExistsSubQuery]);
                            }
                        } elseif (!empty($FilterValues[$Attribute . '_beg']) || !empty($FilterValues[$Attribute . '_end'])) {
                            $znak = !empty($FilterValues[$Attribute . '_beg']) ? '>=' : '<=';
                            $value = !empty($FilterValues[$Attribute . '_beg']) ? $FilterValues[$Attribute . '_beg'] : $FilterValues[$Attribute . '_end'];

                            if (empty($ExistsSubQuery))
                                $ActiveQuery->andFilterWhere([$znak, $SQLAttribute, $value]);
                            else {
                                $ExistsSubQuery->andFilterWhere([$znak, $SQLAttribute, $value]);
                                $ActiveQuery->andWhere(['exists', $ExistsSubQuery]);
                            }
                        }
                        break;
                    case Proc::MultiChoice:
                        if (empty($ExistsSubQuery))
                            $ActiveQuery->andFilterWhere([empty($FilterValues[$Attribute . '_not']) ? 'IN' : 'NOT IN', $SQLAttribute, $FilterValues[$Attribute]]);
                        else {
                            $ExistsSubQuery->andFilterWhere([empty($FilterValues[$Attribute . '_not']) ? 'IN' : 'NOT IN', $SQLAttribute, $FilterValues[$Attribute]]);
                            $ActiveQuery->andWhere(['exists', $ExistsSubQuery]);
                        }
                        break;
                }
        }

    }

    public static function DeleteDocFile($docfile_id)
    {
        if (!empty($docfile_id)) {
            $existdb1 = RraDocfiles::find()->andWhere(['id_docfiles' => $docfile_id])->count();
            $existdb2 = RramatDocfiles::find()->andWhere(['id_docfiles' => $docfile_id])->count();
            if (empty($existdb1) && empty($existdb2)) {
                $Docfiles = Docfiles::findOne($docfile_id);
                if (!empty($Docfiles)) {
                    $hash = Yii::$app->basePath . '/docs/' . $Docfiles->docfiles_hash;
                    $fileroot = (DIRECTORY_SEPARATOR === '/') ? $hash : mb_convert_encoding($hash, 'Windows-1251', 'UTF-8');

                    if ($Docfiles->delete() && file_exists($fileroot))
                        return unlink($fileroot);
                }
            }
        }
        return false;
    }

    public static function ActiveRecordErrorsToString($ActiveRecord)
    {
        if ($ActiveRecord instanceof ActiveRecord) {
            $strerr = '';
            foreach ($ActiveRecord->getErrors() as $attr)
                foreach ($attr as $errmsg)
                    $strerr .= $errmsg . ', ';
            if (!empty($strerr))
                $strerr = mb_substr($strerr, 0, mb_strlen($strerr, 'UTF-8') - 2, 'UTF-8');

            return $strerr;
        } elseif (is_array($ActiveRecord)) {
            $strerr = '';
            foreach ($ActiveRecord as $attr)
                foreach ($attr as $errmsg)
                    $strerr .= $errmsg . ', ';
            if (!empty($strerr))
                $strerr = mb_substr($strerr, 0, mb_strlen($strerr, 'UTF-8') - 2, 'UTF-8');

            return $strerr;
        }

        return false;
    }

    public static function file_exists_utf8($FileNameUTF8)
    {
        $FileRoot = (DIRECTORY_SEPARATOR === '/') ? $FileNameUTF8 : mb_convert_encoding($FileNameUTF8, 'Windows-1251', 'UTF-8');
        return file_exists($FileRoot);
    }

    /** Применяет параметр к url с датой изменения файла в виде Timestamp
     * @param $filePath string Путь к файлу
     * @return string
     */
    public static function appendTimestampUrlParam($filePath)
    {
        return '?v=' . @filemtime($filePath);
    }
}
