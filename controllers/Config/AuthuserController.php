<?php

namespace app\controllers\Config;

use app\func\Proc;
use app\models\Config\Authassignment;
use app\models\Config\AuthassignmentSearch;
use app\models\Config\Authuser;
use app\models\Config\AuthuserSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AuthuserController implements the CRUD actions for Authuser model.
 */
class AuthuserController extends Controller
{
    public function behaviors()
    {
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
     * Lists all Authuser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthuserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Authuser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Authuser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Authuser();
        $model->scenario = 'Newuser';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['view', 'id' => $model->auth_user_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Authuser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {        
        $model = $this->findModel($id);
        $model->auth_user_password = '';
        $model->auth_user_password2 = '';
        $model->scenario = 'Newuser';
        
        $Authassignment = new Authassignment;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $Authassignment->load(Yii::$app->request->get(), 'Authassignment');
            $Authassignment->user_id = $model->primaryKey;
            if ($Authassignment->validate())
                $Authassignment->save(false);

            $searchModel = new AuthassignmentSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('update', [
                        'model' => $model,
                        'Authassignment' => $Authassignment,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Authuser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Authuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Authuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Authuser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
