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
            $postfix = isset($param['postfix']) ? $param['postfix'] : '';
            $id = $view->context->module->controller->id . '/' . $view->context->module->requestedRoute . '/' . $postfix;

            $session = new Session;
            $session->open();

            if (!isset($session['breadcrumbs']))
                $session['breadcrumbs'] = [];

            $result = $session['breadcrumbs'];
            $IDs = $session['breadcrumbs'];

            array_walk($IDs, function(&$value) {
                $value = $value['dopparams']['id'];
            });


            $key = array_search($id, $IDs);
            VAR_DUMP($id);
            VAR_DUMP($IDs);
            var_dump($key);
            if ($key === false) {
                $params = Yii::$app->getRequest()->getQueryParams();
                unset($params['r']);
                array_push($result, [
                    'label' => empty($view->title) ? '-' : $view->title,
                    'url' => Url::toRoute(array_merge([$view->context->module->requestedRoute], $params)),
                    //   'url' => $view->context->module->requestedRoute,
                    'dopparams' => [
                        'id' => $id,
                    ],
                ]);
            } else {
                end($result);
                if ($key !== key($result)) {
                    array_splice($result, $key + 1, count($result) - $key);
                }
            }

            $session['breadcrumbs'] = $result;

            echo '<pre style="max-height: 350px; font-size: 15px;">';
            $s1 = $_SESSION;
            unset($s1['__flash']);
            print_r($s1);
            echo '</pre>';

            //  unset($session['breadcrumbs']);
            $session->close();

            end($result);
            /*  var_Dump(Url::toRoute($view->context->module->requestedRoute));
              var_Dump($view->context->module->requestedRoute);
              var_dump($result); */
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

    public static function DGselect2($params) {
        if (isset($params) && is_array($params)) {
            $model = $params['model'];
            $resultmodel = $params['resultmodel'];
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
                            'url' => Url::to(['site/selectinput']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term, model: "' . str_replace('\\', '\\\\', $resultmodel->className()) /* substr($resultmodel->className(), strrpos($resultmodel->className(), '\\') + 1) */ . '", field: "' . $resultfield . '" } }')
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

}
