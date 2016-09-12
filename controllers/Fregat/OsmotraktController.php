<?php

namespace app\controllers\Fregat;

use app\func\ReportsTemplate\OsmotraktReport;
use app\models\Fregat\InstallTrOsnov;
use app\models\Fregat\Material;
use Exception;
use Yii;
use app\models\Fregat\Osmotrakt;
use app\models\Fregat\OsmotraktSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\TrOsnov;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Installakt;
use app\models\Fregat\Recoveryrecieveakt;

/**
 * OsmotraktController implements the CRUD actions for Osmotrakt model.
 */
class OsmotraktController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'fillnewinstallakt', 'selectinputforosmotrakt', 'forrecoveryrecieveakt', 'assign-to-recoveryrecieveakt', 'selectinputforrecoverysendakt', 'osmotrakt-report'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['OsmotraktEdit'],
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

    public function actionIndex()
    {
        $searchModel = new OsmotraktSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionForrecoveryrecieveakt()
    {
        $searchModel = new OsmotraktSearch();
        $dataProvider = $searchModel->searchforrecoveryrecieveakt(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Osmotrakt;
        $InstallTrOsnov = new InstallTrOsnov;
        $model->osmotrakt_date = date('Y-m-d');
        $InstallTrOsnov->mattraffic_number = 1;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $transaction->commit();
                return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
            } else {
                if ($InstallTrOsnov->load(Yii::$app->request->post()) && $InstallTrOsnov->save($model->id_master)) {
                    $model->id_tr_osnov = $InstallTrOsnov->mattraffic_trosnov_id;
                    if ($model->save()) {
                        $transaction->commit();
                        return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
                    } else {
                        $transaction->rollback();
                        return $this->render('create', [
                            'model' => $model,
                            'InstallTrOsnov' => $InstallTrOsnov,
                        ]);
                    }
                } else {
                    if (empty($InstallTrOsnov->id_mattraffic))
                        $InstallTrOsnov->clearErrors();
                    elseif (empty($model->id_tr_osnov) && !empty($InstallTrOsnov->id_mattraffic))
                        $model->clearErrors('id_tr_osnov');

                    $transaction->rollback();
                    return $this->render('create', [
                        'model' => $model,
                        'InstallTrOsnov' => $InstallTrOsnov,
                    ]);
                }
            }
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputforosmotrakt($field, $q = null)
    {
        if (Yii::$app->request->isAjax)
            return Proc::select2request([
                'model' => new Mattraffic,
                'field' => $field,
                'q' => $q,
                'methodquery' => 'selectinputforosmotrakt',
            ]);
    }

    // Заполнение полей формы после выбора материальной ценности по инвентарнику
    public function actionFillnewinstallakt()
    {
        if (Yii::$app->request->isAjax) {
            $id_mattraffic = Yii::$app->request->post('id_mattraffic');
            if (!empty($id_mattraffic)) {
                $query = Mattraffic::findOne($id_mattraffic);
                if (!empty($query)) {
                    $material_writeoff = Material::VariablesValues('material_writeoff');
                    echo json_encode([
                        'material_id' => $query->id_material,
                        'material_name' => $query->idMaterial->material_name,
                        'material_writeoff' => $material_writeoff[$query->idMaterial->material_writeoff],
                        'auth_user_fullname' => $query->idMol->idperson->auth_user_fullname,
                        'dolzh_name' => $query->idMol->iddolzh->dolzh_name,
                        'build_name' => $query->idMol->idbuild->build_name,
                        'mattraffic_number' => $query->mattraffic_number,
                    ]);
                }
            }
        }
    }

    public function actionAssignToRecoveryrecieveakt()
    {
        Proc::AssignToModelFromGrid(new Recoveryrecieveakt, 'id_recoverysendakt');
        $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputforrecoverysendakt($q = null)
    {
        if (Yii::$app->request->isAjax)
            return Proc::select2request([
                'model' => new Osmotrakt,
                'q' => $q,
                'methodquery' => 'selectinputforrecoverysendakt',
            ]);
    }

    // Печать акта осмотра
    public function actionOsmotraktReport()
    {
        $Report = new OsmotraktReport();
        echo $Report->Execute();
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    /**
     * Finds the Osmotrakt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Osmotrakt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Osmotrakt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
