<?php

namespace app\controllers\Fregat;

use app\func\ReportsTemplate\OsmotraktReport;
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
        $model = new Osmotrakt();
        $model->scenario = 'forosmotrakt';
        $Trosnov = new TrOsnov;
        $Trosnov->scenario = 'forosmotrakt';
        $Mattraffic = new Mattraffic;
        $model->osmotrakt_date = date('Y-m-d');

        $transaction = Yii::$app->db->beginTransaction();
        try {

            $id_mattraffic = isset(Yii::$app->request->post('TrOsnov')['id_mattraffic']) ? Yii::$app->request->post('TrOsnov')['id_mattraffic'] : NULL;
            $id_tr_osnov = isset(Yii::$app->request->post('Osmotrakt')['id_tr_osnov']) ? Yii::$app->request->post('Osmotrakt')['id_tr_osnov'] : NULL;

            $instakterror = false;
            if (empty($id_tr_osnov) && !empty($id_mattraffic)) {

                $Mattrafficcurrent = Mattraffic::findOne($id_mattraffic);

                $Mattraffic->attributes = $Mattrafficcurrent->attributes;

                $Mattraffic->mattraffic_date = date('Y-m-d');
                $Mattraffic->mattraffic_number = 1;
                $Mattraffic->mattraffic_tip = 3;

                if ($Mattraffic->validate()) {
                    $Mattraffic->save(false);

                    $Trosnov->scenario = 'default';
                    $instakterror = true;
                    $Installakt = new Installakt;
                    $Installakt->installakt_date = date('Y-m-d');
                    $Installakt->id_installer = isset(Yii::$app->request->post('Osmotrakt')['id_master']) ? Yii::$app->request->post('Osmotrakt')['id_master'] : NULL;
                    if ($Installakt->validate()) {
                        $Installakt->save(false);
                        $Trosnov->load(Yii::$app->request->post());
                        $Trosnov->id_mattraffic = $Mattraffic->mattraffic_id;
                        $Trosnov->id_installakt = $Installakt->primaryKey;
                        if ($Trosnov->validate()) {
                            $Trosnov->save(false);
                            $instakterror = false;
                        }
                    }
                }
            } elseif (!empty($id_tr_osnov))
                $model->scenario = 'default';

            if ($model->load(Yii::$app->request->post())) {
                if (empty($id_tr_osnov) && !$instakterror)
                    $model->id_tr_osnov = $Trosnov->primaryKey;

                if ($model->save()) {
                    $transaction->commit();
                    return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
                } else {
                    $transaction->rollback();
                    return $this->render('create', [
                        'model' => $model,
                        'Trosnov' => $Trosnov,
                        'Mattraffic' => $Mattraffic,
                    ]);
                }
            } else {
                $transaction->rollback();
                return $this->render('create', [
                    'model' => $model,
                    'Trosnov' => $Trosnov,
                    'Mattraffic' => $Mattraffic,
                ]);
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
            $Trosnov = empty($model->id_tr_osnov) ? new TrOsnov : TrOsnov::findOne($model->id_tr_osnov);
            return $this->render('update', [
                'model' => $model,
                'Trosnov' => $Trosnov,
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
                    echo json_encode([
                        'material_name' => $query->idMaterial->material_name,
                        'auth_user_fullname' => $query->idMol->idperson->auth_user_fullname,
                        'dolzh_name' => $query->idMol->iddolzh->dolzh_name,
                        'build_name' => $query->idMol->idbuild->build_name,
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
        $Report->Execute();
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
