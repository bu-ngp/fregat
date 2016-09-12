<?php

namespace app\controllers\Fregat;

use app\models\Fregat\Employee;
use app\models\Fregat\Material;
use Yii;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\MattrafficSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * MattrafficController implements the CRUD actions for Mattraffic model.
 */
class MattrafficController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'selectinputformaterial', 'forinstallakt', 'forinstallakt_matparent', 'forinstallakt_mat', 'assign-to-matparent', 'forosmotrakt'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['MolEdit'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['MaterialMolDelete'],
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
        Proc::SetMenuButtons('fregat');
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($id)
    {
        $model = new Mattraffic();

        $Mattrafficlast = Mattraffic::find()
            ->leftJoin('mattraffic mt', 'mattraffic.id_material = mt.id_material and  mattraffic.mattraffic_date < mt.mattraffic_date and mt.mattraffic_tip in (1,2)')
            ->andWhere([
                'mt.mattraffic_date' => NULL,
                'mattraffic.id_material' => $id,
                'mattraffic.mattraffic_tip' => [1, 2],
            ])
            ->one();

        $Material = Material::findOne($id);

        $searchModel_mattrafficmols = new MattrafficSearch();
        $dataProvider_mattrafficmols = $searchModel_mattrafficmols->searchformolsmattraffic(Yii::$app->request->queryParams);

        $model->attributes = $Mattrafficlast->attributes;
        $model->id_material = $Material->primaryKey;
        $model->id_mol = $model->primaryKey;
        $model->mattraffic_date = date('Y-m-d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            return $this->render('create', [
                'model' => $model,
                'Material' => $Material,
                'searchModel_mattrafficmols' => $searchModel_mattrafficmols,
                'dataProvider_mattrafficmols' => $dataProvider_mattrafficmols,
            ]);
        }
    }

    public
    function actionForinstallakt()
    {
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->searchforinstallakt(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'foreigndo' => (string)filter_input(INPUT_GET, 'foreigndo'),
        ]);
    }

    public function actionForinstallakt_matparent()
    {
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->searchforinstallakt_matparent(Yii::$app->request->queryParams);

        return $this->render('indexmatparent', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'foreigndo' => (string)filter_input(INPUT_GET, 'foreigndo'),
        ]);
    }

    public
    function actionForosmotrakt()
    {
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->searchforosmotrakt(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'foreigndo' => (string)filter_input(INPUT_GET, 'foreigndo'),
        ]);
    }

    public
    function actionForinstallakt_mat()
    {
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->searchforinstallakt_mat(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'foreigndo' => (string)filter_input(INPUT_GET, 'foreigndo'),
        ]);
    }

    public
    function actionSelectinputformaterial($field, $q = null)
    {
        return Proc::select2request([
            'model' => new Mattraffic,
            'field' => $field,
            'q' => $q,
            'methodquery' => 'selectinput',
        ]);
    }

    public
    function actionAssignToMatparent()
    {
        Proc::AssignToModelFromGrid();
        $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
    }

    public
    function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $status = Mattraffic::CanIsDelete($id);
            if (!empty($status))
                throw new HttpException(500, $status);

            echo $this->findModel($id)->delete();
        }
    }

    /**
     * Finds the Mattraffic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Mattraffic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Mattraffic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
