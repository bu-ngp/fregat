<?php

namespace app\controllers\Base;

use Yii;
use app\models\Base\Classmkb;
use app\models\Base\ClassmkbSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * ClassmkbController implements the CRUD actions for Classmkb model.
 */
class ClassmkbController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'selectinputfordiag'],
                        'allow' => true,
                        'roles' => ['GlaukUserPermission'],
                    ],
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

    public function actionIndex() {
        $searchModel = new ClassmkbSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputfordiag($field, $q = null) {
        if (Yii::$app->request->isAjax)
            return Proc::select2request([
                        'model' => new Classmkb,
                        'field' => $field,
                        'q' => $q,
                        'methodquery' => 'selectinput',
            ]);
    }

    /**
     * Finds the Classmkb model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Classmkb the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Classmkb::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
