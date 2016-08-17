<?php

namespace app\controllers\Fregat;

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
class TrMatController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['selectinputfortrmatchild', 'selectinputfortrmatparent'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => ['InstallEdit'],
                    ],
                    [
                        'actions' => ['fortrrmmat', 'assign-to-trrmmat'],
                        'allow' => true,
                    // 'roles' => ['RemoveEdit'],
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

    public function actionCreate() {
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

                /*    if (isset($Mattraffic->scenarios()['traffic']))
                  $Mattraffic->scenario = 'traffic'; */

                if ($Mattraffic->validate()) {
                    $Mattraffic->save(false);
                    $model->load(Yii::$app->request->post());
                    $model->id_mattraffic = $Mattraffic->mattraffic_id;
                }

                //Акт установки уже создан и берется из URL параметра
                $model->id_installakt = (string) filter_input(INPUT_GET, 'idinstallakt');
            }

            // Сохраняем модель с отправленными данными и сохраненным mattraffic
            if (!$Mattraffic->isNewRecord && $model->save()) {
                $transaction->commit();
                return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
            } else {
                /*                    // Очистить ошибку id_mattraffic, если есть ошибка по mattraffic_number (Превышено допустимое кол-во для перемещения матер. цен-ти)
                  if (isset($Mattraffic->errors['mattraffic_number']))
                  $model->clearErrors('id_mattraffic'); */

                $transaction->rollback();
                return $this->render('create', [
                            'model' => $model,
                            'Mattraffic' => $Mattraffic,
                            'mattraffic_number_max' => $mattraffic_number_max, //максимально допустимое кол-во материала
                ]);
            }
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function actionDelete($id) {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $tr_mat = $this->findModel($id);
                $id_mattraffic = $tr_mat->id_mattraffic;
                $tr_mat->delete();
                echo Mattraffic::findOne($id_mattraffic)->delete();

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                throw new Exception($e->getMessage());
            }
        }
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputfortrmatchild($field, $q = null, $idinstallakt = null) {
        if (Yii::$app->request->isAjax)
            return Proc::select2request([
                        'model' => new Mattraffic,
                        'field' => $field,
                        'q' => $q,
                        'methodquery' => 'selectinputfortrmat_child',
                        'methodparams' => ['idinstallakt' => $idinstallakt],
            ]);
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputfortrmatparent($field, $q = null, $idinstallakt = null) {
        if (Yii::$app->request->isAjax)
            return Proc::select2request([
                        'model' => new Material,
                        'field' => $field,
                        'q' => $q,
                        'methodquery' => 'selectinputfortrmat_parent',
                        'methodparams' => ['idinstallakt' => $idinstallakt],
            ]);
    }

    public function actionFortrrmmat() {
        $searchModel = new TrMatSearch();
        $dataProvider = $searchModel->searchfortrrmmat(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAssignToTrrmmat() {
        Proc::AssignToModelFromGrid(new TrRmMat, 'id_removeakt');
        $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
    }

    /**
     * Finds the TrMat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TrMat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TrMat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
