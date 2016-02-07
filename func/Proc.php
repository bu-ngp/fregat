<?php

namespace app\func;

use Yii;
use yii\web\HttpException;
use yii\web\Session;
use yii\helpers\Url;

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

}
