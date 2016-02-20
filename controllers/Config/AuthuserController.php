<?php

namespace app\controllers\Config;

use app\func\Proc;
use app\models\Config\Authassignment;
use app\models\Config\AuthassignmentSearch;
use app\models\Config\Authuser;
use app\models\Config\AuthuserSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * AuthuserController implements the CRUD actions for Authuser model.
 */
class AuthuserController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'changepassword'],
                        'allow' => true,
                        'roles' => ['UserEdit'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $searchModel = new AuthuserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Authuser();
        $model->scenario = 'Newuser';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->auth_user_id]);
        } else
            return $this->render('create', [
                        'model' => $model,
            ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

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

    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionChangepassword($id) {
        $model = $this->findModel($id);
        $model->auth_user_password = '';
        $model->auth_user_password2 = '';
        $model->scenario = 'Changepassword';

        $result = Proc::GetBreadcrumbsFromSession();
        end($result);
        prev($result);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($result[key($result)]['url']);
        } else {
            return $this->render('changepassword', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Authuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Authuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Authuser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
