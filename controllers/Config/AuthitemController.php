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
use app\models\Config\AuthitemFilter;
use yii\filters\VerbFilter;
use app\models\Config\Authassignment;

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
                        'actions' => ['index', 'create', 'update', 'delete', 'forauthitemchild', 'toexcel', 'filter'],
                        'allow' => true,
                        'roles' => ['RoleEdit'],
                    ],
                    [
                        'actions' => ['forauthassignment', 'assign-to-authassignment'],
                        'allow' => true,
                        'roles' => ['UserEdit'],
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
        $searchModel = new AuthitemSearch();
        $queryParams = Yii::$app->request->queryParams;
        $filter = Proc::SetFilter($searchModel->formName(), new AuthitemFilter);
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'filter' => $filter,
        ]);
    }

    public function actionCreate() {
        $model = new Authitem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return ($model->type == 1) ? $this->redirect(['update', 'id' => $model->name]) : $this->redirect(Proc::GetLastURLBreadcrumbsFromSession());
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
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
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
        if (Yii::$app->request->isAjax)
            $record = $this->findModel($id)->delete();
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

    public function actionToexcel() {
        $searchModel = new AuthitemSearch();
        $params = Yii::$app->request->queryParams;
        $inputdata = json_decode($params['inputdata']);
        $modelname = $searchModel->formName();
        $dataProvider = $searchModel->search(Proc::GetArrayValuesByKeyName($modelname, $inputdata));
        $selectvalues = json_decode($params['selectvalues']);

        Proc::Grid2Excel($dataProvider, $modelname, 'Авторизованные единицы', $selectvalues, new AuthitemFilter);
    }

    public function actionFilter() {
        $model = new AuthitemFilter();

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            Proc::PopulateFilterForm('AuthitemSearch', $model);

            return $this->renderAjax('_filter', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAssignToAuthassignment() {
        Proc::AssignToModelFromGrid(new Authassignment, 'user_id');
        $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
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
