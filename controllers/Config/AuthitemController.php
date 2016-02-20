<?php

namespace app\controllers\Config;

use Yii;
use app\models\Config\Authitem;
use app\models\Config\AuthitemSearch;
use app\models\Config\Authitemchild;
use app\models\Config\AuthitemchildSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * AuthitemController implements the CRUD actions for Authitem model.
 */
class AuthitemController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'forauthitemchild'],
                        'allow' => true,
                        'roles' => ['RoleEdit'],
                    ],
                    [
                        'actions' => ['forauthassignment'],
                        'allow' => true,
                        'roles' => ['UserEdit'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $searchModel = new AuthitemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Authitem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)

            if ($model->type == 1)
                return $this->redirect(['update', 'id' => $model->name]);
            else
                return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $Authitemchild = new Authitemchild;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $Authitemchild->load(Yii::$app->request->get(), 'Authitemchild');
            $Authitemchild->parent = $model->primaryKey;
            if ($Authitemchild->validate())
                $Authitemchild->save(false);

            $searchModel = new AuthitemchildSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('update', [
                        'model' => $model,
                        'Authitemchild' => $Authitemchild,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionForauthitemchild() {
        $searchModel = new AuthitemSearch();
        $dataProvider = $searchModel->searchforauthitemchild(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionForauthassignment() {
        $searchModel = new AuthitemSearch();
        $dataProvider = $searchModel->searchforauthassignment(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Authitem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Authitem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Authitem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
