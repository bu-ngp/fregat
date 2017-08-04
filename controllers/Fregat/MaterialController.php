<?php

namespace app\controllers\Fregat;

use app\models\Fregat\MaterialDocfiles;
use app\models\Fregat\MaterialDocfilesSearch;
use app\models\Fregat\Nakladmaterials;
use app\models\Fregat\NakladmaterialsSearch;
use app\models\Fregat\SpismatmaterialsSearch;
use app\models\Fregat\Spisosnovmaterials;
use app\models\Fregat\SpisosnovmaterialsSearch;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrMatSearch;
use app\models\Fregat\UploadDocFile;
use Yii;
use app\models\Fregat\Material;
use app\models\Fregat\MaterialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\MattrafficSearch;
use app\models\Fregat\OsmotraktSearch;
use app\models\Fregat\RecoverysendaktSearch;
use app\models\Fregat\TrMatOsmotrSearch;
use app\models\Fregat\RecoveryrecieveaktSearch;
use app\models\Fregat\RecoveryrecieveaktmatSearch;
use app\models\Fregat\MaterialFilter;

/**
 * MaterialController implements the CRUD actions for Material model.
 */
class MaterialController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'forinstallakt_mat', 'assign-to-select2', 'materialfilter', 'toexcel', 'selectinput'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update'],
                        'allow' => true,
                        'roles' => ['MaterialEdit'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new MaterialSearch();
        $filter = Proc::SetFilter($searchModel->formName(), new MaterialFilter);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    public function actionCreate()
    {
        $model = new Material();
        if (isset($model->scenarios()['prihod']))
            $model->scenario = 'prihod';
        $model->material_importdo = 1;

        $Mattraffic = new Mattraffic;

        if ($model->load(Yii::$app->request->post())) {

            if (empty($model->material_name1c))
                $model->material_name1c = $model->material_name;

            if ($model->material_tip == 1)
                $model->material_number = 1;

            if ($model->save()) {
                $Mattraffic->id_material = empty($Mattraffic->id_material) ? $model->material_id : $Mattraffic->id_material;
                $Mattraffic->mattraffic_number = empty($Mattraffic->mattraffic_number) ? $model->material_number : $Mattraffic->mattraffic_number;
                $Mattraffic->mattraffic_tip = empty($Mattraffic->mattraffic_tip) ? 1 : $Mattraffic->mattraffic_tip;

                if ($Mattraffic->load(Yii::$app->request->post()) && $Mattraffic->save()) {
                    Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
                    return $this->redirect(['update', 'id' => $model->primaryKey]);
                } else
                    return $this->render('create', [
                        'model' => $model,
                        'Mattraffic' => $Mattraffic,
                    ]);
            } else
                return $this->render('create', [
                    'model' => $model,
                    'Mattraffic' => $Mattraffic,
                ]);
        } else {
            $model->material_number = empty($model->material_number) ? 1 : $model->material_number;
            $model->material_price = empty($model->material_price) ? 1 : $model->material_price;
            $model->material_tip = empty($model->material_tip) ? 1 : $model->material_tip;
            $model->id_matvid = empty($model->id_matvid) ? 1 : $model->id_matvid;
            $model->id_izmer = empty($model->id_izmer) ? 1 : $model->id_izmer;
            $Mattraffic->mattraffic_date = empty($Mattraffic->mattraffic_date) ? date('Y-m-d') : $Mattraffic->mattraffic_date;

            return $this->render('create', [
                'model' => $model,
                'Mattraffic' => $Mattraffic,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $UploadFile = new UploadDocFile;

        $Mattraffic = Mattraffic::find()
            ->andWhere(['id_material' => $model->material_id])
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            ->orderBy('mattraffic_date desc, mattraffic_id desc')
            ->one();

        $searchModel_mattraffic = new MattrafficSearch();
        $dataProvider_mattraffic = $searchModel_mattraffic->searchformaterialmattraffic(Yii::$app->request->queryParams);

        $searchModelmd = new MaterialDocfilesSearch();
        $dataProvidermd = $searchModelmd->search(Yii::$app->request->queryParams);

        $searchModel_recovery = new OsmotraktSearch();
        $dataProvider_recovery = $searchModel_recovery->searchformaterialkarta(Yii::$app->request->queryParams);

        $searchModel_recoverymat = new TrMatOsmotrSearch();
        $dataProvider_recoverymat = $searchModel_recoverymat->searchformaterialkarta(Yii::$app->request->queryParams);

        $searchModel_recoverysend = new RecoveryrecieveaktSearch();
        $dataProvider_recoverysend = $searchModel_recoverysend->searchformaterialkarta(Yii::$app->request->queryParams);

        $searchModel_recoverysendmat = new RecoveryrecieveaktmatSearch();
        $dataProvider_recoverysendmat = $searchModel_recoverysendmat->searchformaterialkarta(Yii::$app->request->queryParams);

        $searchModel_mattraffic_contain = new TrMatSearch();
        $dataProvider_mattraffic_contain = $searchModel_mattraffic_contain->searchformaterialcontain(Yii::$app->request->queryParams);

        $searchModel_spisosnovakt = new SpisosnovmaterialsSearch();
        $dataProvider_spisosnovakt = $searchModel_spisosnovakt->searchformaterialspisosnovakt(Yii::$app->request->queryParams);

        $searchModel_spismat = new SpismatmaterialsSearch();
        $dataProvider_spismat = $searchModel_spismat->searchformaterialspismatakt(Yii::$app->request->queryParams);

        $searchModel_naklad = new NakladmaterialsSearch();
        $dataProvider_naklad = $searchModel_naklad->searchformaterialnaklad(Yii::$app->request->queryParams);

        if (Yii::$app->user->can('MaterialEdit') && $model->load(Yii::$app->request->post()) && $model->save() && $Mattraffic->load(Yii::$app->request->post()) && $Mattraffic->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else
            return $this->render('update', [
                'model' => $model,
                'Mattraffic' => $Mattraffic,
                'UploadFile' => $UploadFile,
                'searchModel_mattraffic' => $searchModel_mattraffic,
                'dataProvider_mattraffic' => $dataProvider_mattraffic,
                'searchModelmd' => $searchModelmd,
                'dataProvidermd' => $dataProvidermd,
                'searchModel_recovery' => $searchModel_recovery,
                'dataProvider_recovery' => $dataProvider_recovery,
                'searchModel_recoverymat' => $searchModel_recoverymat,
                'dataProvider_recoverymat' => $dataProvider_recoverymat,
                'searchModel_recoverysend' => $searchModel_recoverysend,
                'dataProvider_recoverysend' => $dataProvider_recoverysend,
                'searchModel_recoverysendmat' => $searchModel_recoverysendmat,
                'dataProvider_recoverysendmat' => $dataProvider_recoverysendmat,
                'searchModel_mattraffic_contain' => $searchModel_mattraffic_contain,
                'dataProvider_mattraffic_contain' => $dataProvider_mattraffic_contain,
                'searchModel_spisosnovakt' => $searchModel_spisosnovakt,
                'dataProvider_spisosnovakt' => $dataProvider_spisosnovakt,
                'searchModel_spismat' => $searchModel_spismat,
                'dataProvider_spismat' => $dataProvider_spismat,
                'searchModel_naklad' => $searchModel_naklad,
                'dataProvider_naklad' => $dataProvider_naklad,
                'gallery' => MaterialDocfiles::getImagesList((string)filter_input(INPUT_GET, 'id')),
            ]);
    }

    public function actionForinstallakt_mat()
    {
        $searchModel = new MaterialSearch();
        $dataProvider = $searchModel->searchforinstallakt_mat(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'foreigndo' => (string)filter_input(INPUT_GET, 'foreigndo'),
        ]);
    }

    public function actionAssignToSelect2()
    {
        Proc::AssignToModelFromGrid();
    }

    public function actionMaterialfilter()
    {
        $model = new MaterialFilter();

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            Proc::PopulateFilterForm('MaterialSearch', $model);

            return $this->renderAjax('_materialfilter', [
                'model' => $model,
            ]);
        }
    }

    public function actionSelectinput($q = null)
    {
        return Proc::ResultSelect2([
            'model' => new Material,
            'q' => $q,
            'methodquery' => 'selectinput',
        ]);
    }

    public function actionToexcel()
    {
        $searchModel = new MaterialSearch();
        $params = Yii::$app->request->queryParams;
        $inputdata = json_decode($params['inputdata']);
        $modelname = $searchModel->formName();
        $dataProvider = $searchModel->search(Proc::GetArrayValuesByKeyName($modelname, $inputdata));
        $selectvalues = json_decode($params['selectvalues']);
        $labelvalues = isset($params['labelvalues']) ? json_decode($params['labelvalues']) : NULL;

        Proc::Grid2Excel($dataProvider, $modelname, 'Список материальных ценностей', $selectvalues, new MaterialFilter, $labelvalues);
    }

    /**
     * Finds the Material model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Material the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Material::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
