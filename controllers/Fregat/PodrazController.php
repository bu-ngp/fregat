<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Podraz;
use app\models\Fregat\PodrazSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * PodrazController implements the CRUD actions for Podraz model.
 */
class PodrazController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'selectinput', 'assign-to-employee'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission', 'GlaukUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['PodrazEdit'],
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
        $searchModel = new PodrazSearch();
        $Request = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($Request);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'iduser' => $Request['iduser'],
        ]);
    }

    public function actionSelectinput($field, $q = null) {
        return Proc::ResultSelect2([
                    'model' => new Podraz,
                    'field' => $field,
                    'q' => $q,
                    'order' => 'podraz_name',
        ]);
    }

    public function actionCreate() {
        $model = new Podraz();

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDelete($id) {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    public function actionAssignToEmployee() {
        Proc::AssignToModelFromGrid();
        $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
    }

    /**
     * Finds the Podraz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Podraz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Podraz::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
