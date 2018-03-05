<?php

namespace app\controllers\Fregat;

use app\func\OSHelper;
use app\func\ReportsTemplate\OsmotraktReport;
use app\models\Fregat\Fregatsettings;
use app\models\Fregat\InstallTrOsnov;
use app\models\Fregat\Material;
use app\models\Fregat\Organ;
use app\models\Fregat\OsmotraktFilter;
use Exception;
use Yii;
use app\models\Fregat\Osmotrakt;
use app\models\Fregat\OsmotraktSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\Mattraffic;
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
                        'actions' => ['index', 'fillnewinstallakt', 'selectinputforosmotrakt', 'forrecoveryrecieveakt', 'assign-to-grid', 'selectinputforrecoverysendakt', 'osmotrakt-report', 'osmotraktfilter', 'toexcel'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete', 'send-osmotrakt-content', 'osmotrakt-send'],
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
        $filter = Proc::SetFilter($searchModel->formName(), new OsmotraktFilter);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter,
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
                        $transaction->rollBack();
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

                    $transaction->rollBack();
                    return $this->render('create', [
                        'model' => $model,
                        'InstallTrOsnov' => $InstallTrOsnov,
                    ]);
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
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
    public function actionSelectinputforosmotrakt($q = null)
    {
        if (Yii::$app->request->isAjax)
            return Proc::ResultSelect2([
                'model' => new Mattraffic,
                'q' => $q,
                'methodquery' => 'selectinputforosmotrakt',
            ]);
    }

    // Заполнение полей формы после выбора материальной ценности по инвентарнику
    public function actionFillnewinstallakt()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $id_mattraffic = Yii::$app->request->post('id_mattraffic');
            if (!empty($id_mattraffic)) {
                $query = Mattraffic::findOne($id_mattraffic);
                if (!empty($query)) {
                    $material_writeoff = Material::VariablesValues('material_writeoff');
                    return [
                        'material_id' => $query->id_material,
                        'material_name' => $query->idMaterial->material_name,
                        'material_writeoff' => $material_writeoff[$query->idMaterial->material_writeoff],
                        'auth_user_fullname' => $query->idMol->idperson->auth_user_fullname,
                        'dolzh_name' => $query->idMol->iddolzh->dolzh_name,
                        'build_name' => $query->idMol->idbuild->build_name,
                        'mattraffic_number' => $query->mattraffic_number,
                    ];
                }
            }

            return "";
        }
    }

    public function actionAssignToGrid()
    {
        Proc::AssignToModelFromGrid(True, new Recoveryrecieveakt, 'id_recoverysendakt');
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputforrecoverysendakt($q = null)
    {
        if (Yii::$app->request->isAjax)
            return Proc::ResultSelect2([
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


    public function actionSendOsmotraktContent($osmotrakt_id)
    {
        $model = new Organ;

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            return $this->renderAjax('_osmotraktsend', [
                'model' => $model,
                'osmotrakt_id' => $osmotrakt_id,
            ]);
        }
    }

    public function actionOsmotraktSend()
    {
        $dopparams = json_decode(Yii::$app->request->post()['dopparams']);
        if (Yii::$app->request->isAjax) {
            $organ_id = Yii::$app->request->post('organ_id');
            if (!empty($dopparams->id) && !empty($organ_id)) {
                $Organ = Organ::findOne($organ_id);
                if (!empty($Organ->organ_email)) {
                    $Report = new OsmotraktReport();
                    $Report->setDirectoryFiles('tmpfiles');
                    $filename = $Report->Execute();
                    $fnutf8 = $filename;
                    $fregatsettings = Fregatsettings::findOne(1);

                    $fl = OSHelper::setFileNameByOS('tmpfiles/' . $filename);

                    $sended = Yii::$app->mailer->compose('//Fregat/osmotrakt/_send', [
                        'filename' => $filename,
                    ])
                        ->setFrom($fregatsettings->fregatsettings_recoverysend_emailfrom)
                        ->setTo([
                            YII_DEBUG ? 'karpovvv@mugp-nv.ru' : Organ::findOne($organ_id)->organ_email,
                        ])
                        ->setSubject($fregatsettings->fregatsettings_recoverysend_emailtheme)
                        ->attach($fl, ['fileName' => $fnutf8])
                        ->send();
                    if (!$sended)
                        throw new HttpException(500, 'Возникла ошибка при отправке письма');
                    else {
                        $FileName = OSHelper::setFileNameByOS('tmpfiles/' . $filename);
                        unlink($FileName);
                    }
                    echo $fnutf8;
                } else
                    throw new HttpException(500, 'Не заполнен Email у организации');
            }
        }
    }

    public function actionOsmotraktfilter()
    {
        $model = new OsmotraktFilter();

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            Proc::PopulateFilterForm('OsmotraktSearch', $model);

            return $this->renderAjax('_osmotraktfilter', [
                'model' => $model,
            ]);
        }
    }

    public function actionToexcel()
    {
        $searchModel = new OsmotraktSearch();
        $params = Yii::$app->request->queryParams;
        $inputdata = json_decode($params['inputdata']);
        $modelname = $searchModel->formName();
        $dataProvider = $searchModel->search(Proc::GetArrayValuesByKeyName($modelname, $inputdata));
        $selectvalues = json_decode($params['selectvalues']);
        $labelvalues = isset($params['labelvalues']) ? json_decode($params['labelvalues']) : NULL;

        Proc::Grid2Excel($dataProvider, $modelname, 'Журнал осмотров материальных ценностей', $selectvalues, new OsmotraktFilter, $labelvalues);
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
