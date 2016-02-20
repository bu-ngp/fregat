<?php

namespace app\controllers\Config;

use Yii;
use yii\web\Controller;
use app\func\Proc;
use yii\filters\AccessControl;

class ConfigController extends Controller {
    
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['RoleEdit','UserEdit'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        Proc::SetMenuButtons('config');
        return $this->render('//Config/index');
    }

}
