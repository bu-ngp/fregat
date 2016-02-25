<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Grupa;
use app\models\Fregat\GrupaSearch;
use app\models\Fregat\Grupavid;
use app\models\Fregat\GrupavidSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\func\Proc;

/**
 * GrupaController implements the CRUD actions for Grupa model.
 */
class GrupaController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['GrupaEdit'],
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
        $searchModel = new GrupaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Grupa();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->grupa_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $Grupavid = new Grupavid;
            $Grupavid->load(Yii::$app->request->get(), 'Grupavid');

            $count = Grupavid::find()
                    ->where(['id_grupa' => $model->primaryKey])
                    ->one();

            $Grupavid->id_grupa = $model->primaryKey;
            $Grupavid->grupavid_main = isset($count) ? 0 : 1;

            if ($Grupavid->validate())
                $Grupavid->save(false);

            $searchModel = new GrupavidSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('update', [
                        'model' => $model,
                        'Grupavid' => $Grupavid,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionDelete($id) {

        Grupavid::deleteAll(['id_grupa' => $id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Grupa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Grupa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Grupa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
