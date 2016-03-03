<?php

namespace app\controllers\Config;

use app\func\Proc;
use app\models\Config\Authassignment;
use app\models\Config\AuthassignmentSearch;
use app\models\Config\Authuser;
use app\models\Config\AuthuserSearch;
use app\models\Fregat\Employee;
use app\models\Fregat\EmployeeSearch;
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
        $dataProvider = isset($_GET['emp']) && $_GET['emp'] ? $searchModel->searchemployee(Yii::$app->request->queryParams) : $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'emp' => isset($_GET['emp']) && $_GET['emp']
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $result = Proc::GetBreadcrumbsFromSession();
            end($result);
            prev($result);

            return $this->redirect($result[key($result)]['url']);
        } else {
            $Authassignment = new Authassignment;
            $Employee = new Employee;
            $Authassignment->load(Yii::$app->request->get(), 'Authassignment');
            $Employee->load(Yii::$app->request->get(), 'Employee');
            $Authassignment->user_id = $model->primaryKey;
            $Employee->id_person = $model->primaryKey;
            if ($Authassignment->validate())
                $Authassignment->save(false);
            if ($Employee->validate())
                $Employee->save(false);

            $searchModel = new AuthassignmentSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $searchModelEmp = new EmployeeSearch();
            $dataProviderEmp = $searchModelEmp->searchforauthuser(Yii::$app->request->queryParams);

            return $this->render('update', [
                        'model' => $model,
                        'emp' => isset($_GET['emp']) && $_GET['emp'],
                        'Authassignment' => $Authassignment,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'searchModelEmp' => $searchModelEmp,
                        'dataProviderEmp' => $dataProviderEmp,
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

    public function actionIndexemployee() {
        $searchModel = new AuthuserSearch();
        $dataProvider = $searchModel->searchemployee(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
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
