<?php

namespace app\controllers\Base;

use Yii;
use app\models\Base\Fias;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\func\Proc;

/**
 * FiasController implements the CRUD actions for Fias model.
 */
class FiasController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['selectinputforcity', 'selectinputforstreet', 'checkstreets'],
                        'allow' => true,
                        'roles' => ['GlaukUserPermission'],
                    ],
                ],
            ],
                /*   'verbs' => [
                  'class' => VerbFilter::className(),
                  'actions' => [
                  'delete' => ['POST'],
                  ],
                  ], */
        ];
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputforcity($field, $q = null) {
        if (Yii::$app->request->isAjax)
            return Proc::select2request([
                        'model' => new Fias,
                        'field' => $field,
                        'q' => $q,
                        'methodquery' => 'selectinputforcity',
            ]);
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputforstreet($field, $q = null, $fias_city = null) {
        if (Yii::$app->request->isAjax)
            return Proc::select2request([
                        'model' => new Fias,
                        'field' => $field,
                        'q' => $q,
                        'methodquery' => 'selectinputforstreet',
                        'methodparams' => ['fias_city' => $fias_city],
            ]);
    }

    public function actionCheckstreets() {
        if (Yii::$app->request->isAjax) {
            $city_AOGUID = Yii::$app->request->post('city_AOGUID');
            if (!empty($city_AOGUID))
                echo Fias::Checkstreets($city_AOGUID);
        }
    }

    protected function findModel($id) {
        if (($model = Fias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
