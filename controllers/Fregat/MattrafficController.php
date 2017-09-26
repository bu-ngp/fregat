<?php

namespace app\controllers\Fregat;

use app\models\Fregat\Employee;
use app\models\Fregat\Material;
use app\models\Fregat\Spismatmaterials;
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
                        'actions' => ['selectinputformaterial', 'forinstallakt', 'forinstallakt_matparent', 'forinstallakt_mat', 'assign-to-select2', 'assign-to-spismatmaterials-grid', 'forosmotrakt'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'change-build-mol-content'],
                        'allow' => true,
                        'roles' => ['MolEdit'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['MaterialMolDelete'],
                    ],
                    [
                        'actions' => ['selectinputforspisosnovakt', 'selectinputforspisosnovakt-fast', 'forspisosnovakt'],
                        'allow' => true,
                        'roles' => ['SpisosnovaktEdit'],
                    ],
                    [
                        'actions' => ['fornaklad'],
                        'allow' => true,
                        'roles' => ['NakladEdit'],
                    ],
                    [
                        'actions' => ['forspismat'],
                        'allow' => true,
                        'roles' => ['SpismatEdit'],
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

    public function actionCreate($id)
    {
        $material_spisan = false;
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
        $model->mattraffic_username = 'IMPORT';

        if ($Material->material_tip == 2) {
            $model->mattraffic_tip = 1;

            if ($model->mattraffic_number == 0) {
                $material_spisan = true;

                $model->mattraffic_number = 1;
            }
        }

        $model->mattraffic_date = date('Y-m-d');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if ($Material->material_tip == 2) {
                    $Material->material_number = floatval($Material->material_number) + $Mattrafficlast->mattraffic_number;
                    if ($material_spisan) {
                        $Material->material_writeoff = 0;
                    }
                    $Material->save();
                }

                $transaction->commit();

                return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
            } else {
                $transaction->rollBack();

                return $this->render('create', [
                    'model' => $model,
                    'Material' => $Material,
                    'searchModel_mattrafficmols' => $searchModel_mattrafficmols,
                    'dataProvider_mattrafficmols' => $dataProvider_mattrafficmols,
                ]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();

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

    public function actionForspisosnovakt()
    {
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->searchforspisosnovakt(Yii::$app->request->queryParams);

        return $this->render('index_spisosnovakt', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionForspismat()
    {
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->searchforspismat(Yii::$app->request->queryParams);

        return $this->render('index_spismat', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public
    function actionSelectinputformaterial($q = null)
    {
        return Proc::ResultSelect2([
            'model' => new Mattraffic,
            'q' => $q,
            'methodquery' => 'selectinput',
        ]);
    }

    public function actionSelectinputforspisosnovakt($q = null)
    {
        return Proc::ResultSelect2([
            'model' => new Mattraffic,
            'q' => $q,
            'methodquery' => 'selectinputforspisosnovakt',
        ]);
    }

    public function actionSelectinputforspisosnovaktFast($q, $spisosnovakt_id)
    {
        return Proc::ResultSelect2([
            'model' => new Mattraffic,
            'q' => $q,
            'methodquery' => 'selectinputforspisosnovaktFast',
            'MethodParams' => ['spisosnovakt_id' => $spisosnovakt_id],
        ]);
    }

    public
    function actionAssignToSelect2()
    {
        Proc::AssignToModelFromGrid();
    }

    public function actionAssignToSpismatmaterialsGrid()
    {
        Proc::AssignToModelFromGrid(true, new Spismatmaterials, 'id_spismat');
    }

    public function actionFornaklad()
    {
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->searchfornaklad(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public
    function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $status = Mattraffic::CanIsDelete($id);
            if (!empty($status))
                throw new HttpException(500, $status);

            $Mattraffic = Mattraffic::findOne($id);
            $Material = Material::findOne($Mattraffic->id_material);

            $transaction = Yii::$app->db->beginTransaction();
            try {
                echo $this->findModel($id)->delete();
                $diff = floatval($Material->material_number) - floatval($Mattraffic->mattraffic_number);

                if ($diff < 0) {
                    throw new HttpException(500, "Разница в количестве меньше 0 ($diff)");
                }

                Material::updateAll(['material_number' => $diff], ['material_id' => $Mattraffic->id_material]);

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new $e;
            }
        }
    }

    public function actionChangeBuildMolContent($employee_id)
    {
        $model = new Employee;
        $model->setScenario('buildRequire');

        $CurrentMol = Employee::findOne($employee_id);
        $model->attributes = $CurrentMol->attributes;
        $model->id_build = NULL;

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            return $this->renderAjax('_add_build_mol', [
                'model' => $model,
                'employee_id' => $employee_id,
            ]);
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
