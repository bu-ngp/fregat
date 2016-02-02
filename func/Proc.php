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
            $id = $view->context->module->controller->id . '/' . $view->context->module->requestedRoute.'/'.$postfix;                        

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
            if ($key === false) {
                array_push($result, [
                    'label' => empty($view->title) ? '-' : $view->title,
                    'url' => Url::toRoute($view->context->module->requestedRoute),
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

         //   var_dump($result);

            $session['breadcrumbs'] = $result;

          //   unset($session['breadcrumbs']);
            $session->close();

            end($result);
            unset($result[key($result)]['url']);
            return $result;
        } else
            throw new HttpException(500, 'Ошибка при передачи параметров в function Breadcrumbs');
    }

}
