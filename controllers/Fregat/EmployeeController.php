<?php

namespace app\controllers\Fregat;

use HttpException;
use Yii;
use app\models\Fregat\Employee;
use app\models\Fregat\EmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\Impemployee;
use yii\web\Response;
use yii\web\Session;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'assign-to-grid', 'foractiveemployee'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission', 'GlaukUserPermission'],
                    ],
                    [
                        'actions' => ['selectinputemloyee', 'forimportemployee', 'selectinputwithmaterials'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete', 'add-employee'],
                        'allow' => true,
                        'roles' => ['EmployeeEdit'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['EmployeeBuildEdit', 'EmployeeSpecEdit'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['EmployeeSpecEdit'],
                    ],
                    [
                        'actions' => ['fornaklad'],
                        'allow' => true,
                        'roles' => ['NakladEdit'],
                    ]
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
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionForimportemployee()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->searchforimportemployee(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSelectinputemloyee($q = null)
    {
        return Proc::ResultSelect2([
            'model' => new Employee,
            'q' => $q,
            'methodquery' => 'selectinputactive',
        ]);
    }

    public function actionSelectinputwithmaterials($q = null)
    {
        return Proc::ResultSelect2([
            'model' => new Employee,
            'q' => $q,
            'methodquery' => 'selectinputwithmaterials',
        ]);
    }

    public function actionCreate($iduser)
    {
        $model = new Employee();
        $model->id_person = $iduser;
        $model->employee_importdo = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else
            return $this->render('create', [
                'model' => $model,
                'iduser' => $iduser,
            ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $OnlyBuildEdit = Yii::$app->user->can('EmployeeBuildEdit') && !(Yii::$app->user->can('EmployeeEdit') || Yii::$app->user->can('EmployeeSpecEdit'));

        if (Yii::$app->user->can('EmployeeEdit') || Yii::$app->user->can('EmployeeSpecEdit'))
            $Values = Yii::$app->request->post();
        elseif (Yii::$app->user->can('EmployeeBuildEdit'))
            $Values = isset($_POST['Employee']['id_build']) ? [
                'Employee' => [
                    'id_build' => $_POST['Employee']['id_build'] ? $_POST['Employee']['id_build'] : NULL
                ]] : NULL;

        if ($model->load($Values) && $model->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else
            return $this->render('update', [
                'model' => $model,
                'OnlyBuildEdit' => $OnlyBuildEdit,
            ]);
    }

    public function actionFornaklad()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->searchfornaklad(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    public function actionAssignToGrid()
    {
        Proc::AssignToModelFromGrid(True, new Impemployee, 'id_importemployee');
    }

    public function actionAddEmployee()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $build_id = Yii::$app->request->post('build_id');
            $employee_id = Yii::$app->request->post('employee_id');
            if (empty($build_id))
                throw new HttpException(500, 'Не заполнено здание.');

            if (empty($employee_id))
                throw new HttpException(500, 'Ошибка при отправке запроса.');

            $EmployeeCurrent = Employee::findOne($employee_id);
            $Employee = new Employee;
            $Employee->attributes = $EmployeeCurrent->attributes;
            $Employee->id_build = $build_id;
            if (!$Employee->save())
                throw new HttpException(500, 'Ошибка при сохранении нового здания.');

            $BC = Proc::GetBreadcrumbsFromSession();
            end($BC);
            $BC[key($BC)]['dopparams']['Mattraffic']['id_mol'] = $Employee->primaryKey;

            $session = new Session;
            $session->open();
            $session['breadcrumbs'] = $BC;
            $session->close();

            $text = $Employee->selectinput(['init' => true, 'q' => $Employee->primaryKey]);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['id' => $Employee->primaryKey, 'text' => $text['text']];
        }
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
