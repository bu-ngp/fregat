<?php

namespace app\controllers;

use Yii;
use app\models\Podraz;
use app\models\PodrazSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;

/**
 * PodrazController implements the CRUD actions for Podraz model.
 */
class PodrazController extends Controller {

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
     * Lists all Podraz models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PodrazSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $foreignmodel = (string) filter_input(INPUT_GET, 'foreignmodel');

        if (!empty($foreignmodel)) {
            $session = new Session;
            $session->open();
            $session[$foreignmodel] = array_replace_recursive(isset($session[$foreignmodel]) ? $session[$foreignmodel] : [], ['foreign' => [
                    'url' => (string) filter_input(INPUT_GET, 'url'),
                    'field' => (string) filter_input(INPUT_GET, 'field'),
                    'id' => (string) filter_input(INPUT_GET, 'id'),
            ]]);

            $session->close();
        }

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'foreignmodel' => $foreignmodel,
        ]);
    }

    public function actionSelectinput($q = null) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $out['results'] = Podraz::find()
                    ->select(['podraz_id AS id', 'podraz_name AS text'])
                    ->where(['like', 'podraz_name', $q])
                    ->limit(20)
                    ->asArray()
                    ->all();
        }
        return $out;
    }

    /**
     * Displays a single Podraz model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Podraz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Podraz();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Podraz model.
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
     * Deletes an existing Podraz model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
