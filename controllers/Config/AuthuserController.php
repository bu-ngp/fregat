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
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * AuthuserController implements the CRUD actions for Authuser model.
 */
class AuthuserController extends Controller
{

    public function behaviors()
    {
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
                        'actions' => ['index', 'create', 'update', 'delete', 'changepassword'],
                        'allow' => true,
                        'roles' => ['UserEdit'],
                    ],
                    [
                        'actions' => ['index', 'update'],
                        'allow' => true,
                        'roles' => ['EmployeeSpecEdit'],
                    ],
                    [
                        'actions' => ['change-self-password'],
                        'allow' => true,
                        'roles' => ['@'],
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

    public function actionIndex()
    {
        $searchModel = new AuthuserSearch();
        $emp = (string)filter_input(INPUT_GET, 'emp');
        $dataProvider = $emp ? $searchModel->searchemployee(Yii::$app->request->queryParams) : $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'emp' => $emp,
        ]);
    }

    public function actionCreate()
    {
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

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $EmployeeSpecEdit = Yii::$app->user->can('EmployeeSpecEdit') && !(Yii::$app->user->can('EmployeeEdit'));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            $emp = (string)filter_input(INPUT_GET, 'emp');

            $searchModel = new AuthassignmentSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $searchModelEmp = new EmployeeSearch();
            $dataProviderEmp = $searchModelEmp->searchforauthuser(Yii::$app->request->queryParams);

            return $this->render('update', [
                'model' => $model,
                'emp' => $emp,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'searchModelEmp' => $searchModelEmp,
                'dataProviderEmp' => $dataProviderEmp,
                'EmployeeSpecEdit' => $EmployeeSpecEdit,
            ]);
        }
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax)
            if ($id == 1)
                throw new HttpException(500, 'Администратора удалить нельзя');
            else
                echo $this->findModel($id)->delete();
    }

    public function actionChangepassword($id)
    {
        $model = $this->findModel($id);
        $model->auth_user_password = '';
        $model->auth_user_password2 = '';
        $model->scenario = 'Changepassword';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            return $this->render('changepassword', [
                'model' => $model,
            ]);
        }
    }

    public function actionChangeSelfPassword()
    {
        $model = $this->findModel(Yii::$app->user->getId());
        $model->auth_user_password = '';
        $model->auth_user_password2 = '';
        $model->scenario = 'Changepassword';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            return $this->render('changepassword', [
                'model' => $model,
            ]);
        }
    }

    public function actionIndexemployee()
    {
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
    protected function findModel($id)
    {
        if (($model = Authuser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
