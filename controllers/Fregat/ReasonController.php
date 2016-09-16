<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Reason;
use app\models\Fregat\ReasonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * ReasonController implements the CRUD actions for Reason model.
 */
class ReasonController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'selectinput', 'assign-to-osmotrakt'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['ReasonEdit'],
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
        $searchModel = new ReasonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSelectinput($field, $q = null) {
        return Proc::ResultSelect2([
                    'model' => new Reason,
                    'field' => $field,
                    'q' => $q,
                    'order' => 'reason_text',
        ]);
    }

    public function actionCreate() {
        $model = new Reason();

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else
            return $this->render('create', [
                        'model' => $model,
            ]);
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

    public function actionAssignToOsmotrakt() {
        Proc::AssignToModelFromGrid();
        $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
    }

    /**
     * Finds the Reason model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reason the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Reason::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
