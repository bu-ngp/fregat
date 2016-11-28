<?php

namespace app\controllers\Config;

use app\models\Fregat\Fregatsettings;
use Yii;
use yii\web\Controller;
use app\func\Proc;
use yii\filters\AccessControl;

class ConfigController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['RoleEdit', 'UserEdit'],
                    ],
                    [
                        'actions' => ['configuration'],
                        'allow' => true,
                        'roles' => ['FregatConfig'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        Proc::SetMenuButtons('config');
        return $this->render('//Config/index');
    }

    public function actionConfiguration()
    {
        $model = Fregatsettings::findOne(1);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            return $this->render('//Config/configuration/update', [
                'model' => $model,
            ]);
        }
    }
}
