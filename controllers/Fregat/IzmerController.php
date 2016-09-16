<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Izmer;
use app\models\Fregat\IzmerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * IzmerController implements the CRUD actions for Izmer model.
 */
class IzmerController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'selectinput', 'assign-to-material'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['IzmerEdit'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $searchModel = new IzmerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSelectinput($field, $q = null) {
        return Proc::ResultSelect2([
                    'model' => new Izmer,
                    'field' => $field,
                    'q' => $q,
        ]);
    }

    public function actionCreate() {
        $model = new Izmer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else
            return $this->render('update', [
                        'model' => $model,
            ]);
    }

    public function actionDelete($id) {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    public function actionAssignToMaterial() {
        Proc::AssignToModelFromGrid();
        $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
    }

    /**
     * Finds the Izmer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Izmer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Izmer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
