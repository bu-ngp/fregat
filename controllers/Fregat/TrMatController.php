<?php

namespace app\controllers\Fregat;

use app\models\Fregat\TrMatOsmotr;
use Exception;
use Yii;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrMatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Material;
use app\models\Fregat\TrRmMat;

/**
 * TrMatController implements the CRUD actions for TrMat model.
 */
class TrMatController extends Controller
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
                        'actions' => ['selectinputfortrmatchild', 'selectinputfortrmatparent', 'selectinputfortrmatosmotr', 'max-number-material-by-mol'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => ['InstallEdit'],
                    ],
                    [
                        'actions' => ['fortrrmmat', 'assign-to-grid'],
                        'allow' => true,
                        'roles' => ['RemoveaktEdit'],
                    ],
                    [
                        'actions' => ['fortrmatosmotr'/*, 'assign-to-trmatosmotr'*/],
                        'allow' => true,
                        'roles' => ['OsmotraktEdit'],
                    ]
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

    public function actionCreate()
    {
        $model = new TrMat();
        $Mattraffic = new Mattraffic;
        $mattraffic_number_max = NULL;
        // Если форма отправлена на сервер, получаем выбранную материальную ценность
        $id_mattraffic = isset(Yii::$app->request->post('TrMat')['id_mattraffic']) ? Yii::$app->request->post('TrMat')['id_mattraffic'] : '';

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Если форма отправлена на сервер, то создать запись перемещения мат цен-ти в mattraffic
            if (!empty($id_mattraffic)) {
                $Mattrafficcurrent = Mattraffic::findOne($id_mattraffic);

                $Mattraffic->attributes = $Mattrafficcurrent->attributes;

                $Mattraffic->mattraffic_date = date('Y-m-d');
                $Mattraffic->mattraffic_number = isset(Yii::$app->request->post('Mattraffic')['mattraffic_number']) ? Yii::$app->request->post('Mattraffic')['mattraffic_number'] : NULL;
                $Mattraffic->mattraffic_tip = 4;

                if (isset($Mattraffic->scenarios()['trafficmat']))
                    $Mattraffic->scenario = 'trafficmat';

                if ($Mattraffic->validate()) {
                    $Mattraffic->save(false);
                    $model->load(Yii::$app->request->post());
                    $model->id_mattraffic = $Mattraffic->mattraffic_id;
                }

                //Акт установки уже создан и берется из URL параметра
                $model->id_installakt = (string)filter_input(INPUT_GET, 'idinstallakt');
            }

            // Сохраняем модель с отправленными данными и сохраненным mattraffic
            if (!$Mattraffic->isNewRecord && $model->save()) {
                $transaction->commit();
                return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
            } else {
                /*                    // Очистить ошибку id_mattraffic, если есть ошибка по mattraffic_number (Превышено допустимое кол-во для перемещения матер. цен-ти)
                  if (isset($Mattraffic->errors['mattraffic_number']))
                  $model->clearErrors('id_mattraffic'); */

                $transaction->rollBack();
                return $this->render('create', [
                    'model' => $model,
                    'Mattraffic' => $Mattraffic,
                    'mattraffic_number_max' => $mattraffic_number_max, //максимально допустимое кол-во материала
                ]);
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $tr_mat = $this->findModel($id);
                $id_mattraffic = $tr_mat->id_mattraffic;

                if ($tr_mat->delete() === false)
                    throw new Exception('Удаление невозможно.');

                $Mattraffic = Mattraffic::findOne($id_mattraffic)->delete();

                if ($Mattraffic === false)
                    throw new Exception('Удаление невозможно.');

                echo $Mattraffic;

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        }
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputfortrmatchild($q = null, $idinstallakt = null, $id_parent = null)
    {
        if (Yii::$app->request->isAjax)
            return Proc::ResultSelect2([
                'model' => new Mattraffic,
                'q' => $q,
                'methodquery' => 'selectinputfortrmat_child',
                'methodparams' => ['idinstallakt' => $idinstallakt, 'id_parent' => $id_parent],
            ]);
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputfortrmatparent($q = null, $idinstallakt = null)
    {
        if (Yii::$app->request->isAjax)
            return Proc::ResultSelect2([
                'model' => new Mattraffic,
                'q' => $q,
                'methodquery' => 'selectinputfortrmat_parent',
                'methodparams' => ['idinstallakt' => $idinstallakt],
            ]);
    }

    public function actionFortrrmmat()
    {
        $searchModel = new TrMatSearch();
        $dataProvider = $searchModel->searchfortrrmmat(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAssignToGrid()
    {
        Proc::AssignToModelFromGrid(True, new TrRmMat, 'id_removeakt');
    }

    public function actionFortrmatosmotr()
    {
        $searchModel = new TrMatSearch();
        $dataProvider = $searchModel->searchfortrmatosmotr(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /* public function actionAssignToTrmatosmotr()
     {
         Proc::AssignToModelFromGrid(True, new TrMatOsmotr, 'id_osmotraktmat');
     }*/

    public function actionSelectinputfortrmatosmotr($q = null, $idosmotraktmat = null)
    {
        if (Yii::$app->request->isAjax)
            return Proc::ResultSelect2([
                'model' => new TrMat,
                'q' => $q,
                'methodquery' => 'selectinputfortrmatosmotr',
                'methodparams' => ['idosmotraktmat' => $idosmotraktmat],
            ]);
    }

    public function actionMaxNumberMaterialByMol()
    {
        if (Yii::$app->request->isAjax) {
            $mattraffic_id = Yii::$app->request->post('mattraffic_id');
            if (!empty($mattraffic_id)) {
                $query = Mattraffic::findOne($mattraffic_id);
                if (!empty($query)) {
                    echo json_encode([
                        'mattraffic_number' => $query->mattraffic_number,
                    ]);
                }
            }
        }
    }

    /**
     * Finds the TrMat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TrMat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrMat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
