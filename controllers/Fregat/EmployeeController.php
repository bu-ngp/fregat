<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Employee;
use app\models\Fregat\EmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'selectinput', 'forimportemployee'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['EmployeeEdit'],
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
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionForimportemployee() {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->searchforimportemployee(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($iduser) {
        $model = new Employee();
        $model->id_person = $iduser;

        $result = Proc::GetBreadcrumbsFromSession();
        end($result);
        prev($result);        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($result[key($result)]['url']);
        } else {

            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        $result = Proc::GetBreadcrumbsFromSession();
        end($result);
        prev($result);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($result[key($result)]['url']);
        } else {

            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();

        $result = Proc::GetBreadcrumbsFromSession();
        end($result);

        return $this->redirect($result[key($result)]['url']);
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
