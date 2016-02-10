<?php

namespace app\func;

use Yii;
use yii\web\HttpException;
use yii\web\Session;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;

class Proc {

    public static function Breadcrumbs($view, $param = null) {
        if (isset($view)) {
            $param = $param === null ? [] : $param;
            $postfix = isset($param['postfix']) ? $param['postfix'] : '';
            $id = $view->context->module->controller->id . '/' . $view->context->module->requestedRoute . '/' . $postfix;

            $session = new Session;
            $session->open();

            if (!isset($session['breadcrumbs']))
                $session['breadcrumbs'] = [];

            $result = $session['breadcrumbs'];


            $fmodel = isset($param['model']) ? substr($param['model']->className(), strrpos($param['model']->className(), '\\') + 1) : '';

            if (!isset($result[$id])) {
                $params = Yii::$app->getRequest()->getQueryParams();
                unset($params['r']);
                $result[$id] = [
                    'label' => empty($view->title) ? '-' : $view->title,
                    'url' => Url::toRoute(array_merge([$view->context->module->requestedRoute], $params)),
                    'dopparams' => array_merge(isset($param['model']) ? [
                                $fmodel => $param['model']->attributes
                                    ] : [], isset($_GET['foreignmodel']) ? [
                                'foreign' => [
                                    'url' => (string) filter_input(INPUT_GET, 'url'),
                                    'model' => $_GET['foreignmodel'],
                                    'field' => (string) filter_input(INPUT_GET, 'field'),
                                    'id' => (string) filter_input(INPUT_GET, 'id'),
                                ]
                                    ] : []
                    ),
                ];
            } else {
                end($result);

                $value = '';
                $fmodel = $result[key($result)]['dopparams']['foreign']['model'];
                $field = $result[key($result)]['dopparams']['foreign']['field'];

                while (count($result) > 0 && $id !== key($result)) {
                    if (isset($result[key($result)]['dopparams']['foreign']))
                        $fmodel = $result[key($result)]['dopparams']['foreign']['model'];
                    unset($result[key($result)]);
                    end($result);
                }

                if (isset(Yii::$app->request->get()[$fmodel][$field]))
                    $value = Yii::$app->request->get()[$fmodel][$field];
                elseif (isset($result[key($result)]['dopparams'][$fmodel][$field]))
                    $value = $result[key($result)]['dopparams'][$fmodel][$field];
                
                if (isset($result[key($result)]['dopparams'][$fmodel])) {
                    $result[key($result)]['dopparams'][$fmodel][$field] = $value;
                    $param['model']->load($result[key($result)]['dopparams'], $fmodel);
                }
            }

            $session['breadcrumbs'] = $result;

            echo '<pre class="xdebug-var-dump" style="max-height: 350px; font-size: 15px;">';
            $s1 = $_SESSION;
            unset($s1['__flash']);
            print_r($s1);
            echo '</pre>';

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
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning']);
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

    public static function DGselect2t($params) {
        if (isset($params) && is_array($params)) {
            $model = $params['model'];
            $resultmodel = $params['resultmodel'];
            $resultrequest = $params['resultrequest'];
            $keyfield = $params['keyfield'];
            $resultfield = $params['resultfield'];
            $placeholder = $params['placeholder'];
            $fromgridroute = $params['fromgridroute'];
            $thisroute = $params['thisroute'];

            //if (!empty($model) && !empty($resultmodel) && !empty($keyfield) && !empty($resultfield) && !empty($fromgridroute) && !empty($$thisroute)) {

            return [
                'initValueText' => empty($model->$keyfield) ? '' : $resultmodel::findOne($model->$keyfield)->$resultfield,
                'options' => ['placeholder' => $placeholder],
                'theme' => Select2::THEME_BOOTSTRAP,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => Url::to([$resultrequest]),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term, field: "' . $resultfield . '" } }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                ],
                'addon' => [
                    'append' => [
                        'content' => Html::a('<i class="glyphicon glyphicon-plus-sign"></i>', [$fromgridroute,
                            'foreignmodel' => substr($model->className(), strrpos($model->className(), '\\') + 1),
                            'url' => $thisroute,
                            'field' => $keyfield,
                            'id' => $model->primaryKey,
                                ], ['class' => 'btn btn-success']),
                        'asButton' => true
                    ]
                ]
            ];
        }
        //}
    }

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
                    'options' => ['placeholder' => $placeholder],
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

    public static function RemoveLastBreadcrumbsFromSession() {
        $session = new Session;
        $session->open();
        $bc = $session['breadcrumbs'];
        end($bc);
        unset($bc[key($bc)]);
        $session['breadcrumbs'] = $bc;
        $session->close();
    }

}
