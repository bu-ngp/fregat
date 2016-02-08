<?php

namespace app\controllers;

use Yii;
use app\models\Build;
use app\models\BuildSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use app\func\Proc;

/**
 * BuildController implements the CRUD actions for Build model.
 */
class BuildController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Build models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new BuildSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', Proc::SetForeignmodel([
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
        ]));
    }

    public function actionSelectinput($field, $q = null) {
        return Proc::select2request(new Build, $field, $q);
    }
    
    public function actionSelectinput2($field, $q = null) {
        $showresultfields = $_GET['showresultfields'];
        
        return Proc::select2request2(new Build, $field, $q, $showresultfields);
    }

    /**
     * Displays a single Build model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Build model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Build();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Build model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Build model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $record = $this->findModel($id);
        if ($record !== null) {
            if ($record->delete()) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['index', 'errordelete' => 'Нельзя удалить']);
            }
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Build model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Build the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Build::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
