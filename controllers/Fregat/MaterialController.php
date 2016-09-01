<?php

namespace app\controllers\Fregat;

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
                        'actions' => ['index', 'update', 'forinstallakt_mat', 'assign-material', 'materialfilter'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create'],
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
        $Mattraffic = new Mattraffic;

        if ($model->load(Yii::$app->request->post())) {

            if (empty($model->material_name1c))
                $model->material_name1c = $model->material_name;

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
            $model->material_importdo = empty($model->material_importdo) ? 1 : $model->material_importdo;
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
        $Mattraffic = Mattraffic::find()
            ->andWhere(['id_material' => $model->material_id])
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            ->orderBy('mattraffic_date desc, mattraffic_id desc')
            ->one();

        $searchModel_mattraffic = new MattrafficSearch();
        $dataProvider_mattraffic = $searchModel_mattraffic->searchformaterialmattraffic(Yii::$app->request->queryParams);

        $searchModel_recovery = new OsmotraktSearch();
        $dataProvider_recovery = $searchModel_recovery->searchformaterialkarta(Yii::$app->request->queryParams);

        $searchModel_recoverymat = new TrMatOsmotrSearch();
        $dataProvider_recoverymat = $searchModel_recoverymat->searchformaterialkarta(Yii::$app->request->queryParams);

        $searchModel_recoverysend = new RecoveryrecieveaktSearch();
        $dataProvider_recoverysend = $searchModel_recoverysend->searchformaterialkarta(Yii::$app->request->queryParams);

        $searchModel_recoverysendmat = new RecoveryrecieveaktmatSearch();
        $dataProvider_recoverysendmat = $searchModel_recoverysendmat->searchformaterialkarta(Yii::$app->request->queryParams);

        if (Yii::$app->user->can('MaterialEdit') && $model->load(Yii::$app->request->post()) && $model->save() && $Mattraffic->load(Yii::$app->request->post()) && $Mattraffic->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else
            return $this->render('update', [
                'model' => $model,
                'Mattraffic' => $Mattraffic,
                'searchModel_mattraffic' => $searchModel_mattraffic,
                'dataProvider_mattraffic' => $dataProvider_mattraffic,
                'searchModel_recovery' => $searchModel_recovery,
                'dataProvider_recovery' => $dataProvider_recovery,
                'searchModel_recoverymat' => $searchModel_recoverymat,
                'dataProvider_recoverymat' => $dataProvider_recoverymat,
                'searchModel_recoverysend' => $searchModel_recoverysend,
                'dataProvider_recoverysend' => $dataProvider_recoverysend,
                'searchModel_recoverysendmat' => $searchModel_recoverysendmat,
                'dataProvider_recoverysendmat' => $dataProvider_recoverysendmat,
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

    public function actionAssignMaterial()
    {
        Proc::AssignToModelFromGrid();
        $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
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
