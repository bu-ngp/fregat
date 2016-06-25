<?php

namespace app\controllers\Glauk;

use Yii;
use app\models\Glauk\Glaukuchet;
use app\models\Glauk\GlaukuchetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Fregat\Employee;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * GlaukuchetController implements the CRUD actions for Glaukuchet model.
 */
class GlaukuchetController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['selectinputforvrach', 'config'],
                        'allow' => true,
                        'roles' => ['GlaukUserPermission'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    // Меню настройки
    public function actionConfig() {
        return $this->render('//Glauk/glaukuchet/config');
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputforvrach($field, $q = null) {
        if (Yii::$app->request->isAjax)
            return Proc::select2request([
                        'model' => new Employee,
                        'field' => $field,
                        'q' => $q,
                        'methodquery' => 'selectinput',
            ]);
    }

    /**
     * Finds the Glaukuchet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Glaukuchet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Glaukuchet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
