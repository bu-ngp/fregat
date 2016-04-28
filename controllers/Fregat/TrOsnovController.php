<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\TrOsnov;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Material;
use app\models\Fregat\Employee;

/**
 * TrOsnovController implements the CRUD actions for TrOsnov model.
 */
class TrOsnovController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['selectinputfortrosnov', 'filltrosnov'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                    // 'roles' => ['BuildEdit'],
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
        $model = new TrOsnov();
        $Mattraffic = new Mattraffic;
        $Material = new Material;
        $Employee = new Employee;
        $mattraffic_number_max = NULL;


        $id_mattraffic = isset(Yii::$app->request->post('TrOsnov')['id_mattraffic']) ? Yii::$app->request->post('TrOsnov')['id_mattraffic'] : '';
        $mattraffic_number = isset(Yii::$app->request->post('Mattraffic')['mattraffic_number']) ? Yii::$app->request->post('Mattraffic')['mattraffic_number'] : NULL;

        $filleddata = false;

        $transaction = Yii::$app->db->beginTransaction();
        try {

            if (!empty($id_mattraffic)) {
                $Mattrafficcurrent = Mattraffic::findOne($id_mattraffic);

                $Mattraffic->attributes = $Mattrafficcurrent->attributes;

                $Mattraffic->mattraffic_date = date('Y-m-d');
                $Mattraffic->mattraffic_number = $mattraffic_number;
                $Mattraffic->mattraffic_tip = 3;

                if (isset($Mattraffic->scenarios()['traffic']))
                    $Mattraffic->scenario = 'traffic';

                if ($Mattraffic->validate()) {
                    $Mattraffic->save(false);
                    $model->id_mattraffic = $Mattraffic->mattraffic_id;
                }
                $model->id_installakt = isset($_GET['idinstallakt']) ? $_GET['idinstallakt'] : Null;
                $model->tr_osnov_kab = isset(Yii::$app->request->post('TrOsnov')['tr_osnov_kab']) ? Yii::$app->request->post('TrOsnov')['tr_osnov_kab'] : NULL;

                $filleddata = true;
            }

            if ($filleddata && $model->save()) {
                $transaction->commit();
                return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
            } else {

                $PreviusBC = Proc::GetValueForFillARs($id_mattraffic, 'TrOsnov', 'id_mattraffic');

                if (isset($Mattraffic->errors['mattraffic_number']))
                    $model->clearErrors('id_mattraffic');

                if (!empty($id_mattraffic)) {
                    $Material = Material::find()->joinWith('mattraffics')->where(['mattraffic_id' => $id_mattraffic])->one();
                    $Employee = Employee::find()->joinWith('mattraffics')->where(['mattraffic_id' => $id_mattraffic])->one();
                    $mattraffic_number_max = 'Не более ' . doubleval(Mattraffic::GetMaxNumberMattrafficForInstallAkt($id_mattraffic));

                    Proc::SetSessionValuesFromAR($Material, $PreviusBC);
                    Proc::SetSessionValuesFromAR($Employee, $PreviusBC);
                }

                $transaction->rollback();

                return $this->render('create', [
                            'model' => $model,
                            'Mattraffic' => $Mattraffic,
                            'Material' => $Material,
                            'Employee' => $Employee,
                            'mattraffic_number_max' => $mattraffic_number_max,
                ]);
            }
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception($e->getMessage());
        }
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputfortrosnov($field, $q = null) {
        if (Yii::$app->request->isAjax)
            return Proc::select2request([
                        'model' => new Mattraffic,
                        'field' => $field,
                        'q' => $q,
                        'methodquery' => 'selectinputfortrosnov',
            ]);
    }

    // Заполнение полей формы после выбора материальной ценности по инвентарнику
    public function actionFilltrosnov() {
        if (Yii::$app->request->isAjax) {
            $mattraffic_id = Yii::$app->request->post('mattraffic_id');
            if (!empty($mattraffic_id)) {
                $query = Mattraffic::findOne($mattraffic_id);
                if (!empty($query)) {
                    echo json_encode([
                        'material_tip' => $query->idMaterial->material_tip,
                        'material_name' => $query->idMaterial->material_name,
                        'material_writeoff' => $query->idMaterial->material_writeoff,
                        'auth_user_fullname' => $query->idMol->idperson->auth_user_fullname,
                        'dolzh_name' => $query->idMol->iddolzh->dolzh_name,
                        'podraz_name' => $query->idMol->idpodraz->podraz_name,
                        'build_name' => $query->idMol->idbuild->build_name,
                        'mattraffic_number' => doubleval(Mattraffic::GetMaxNumberMattrafficForInstallAkt($query->mattraffic_id)), // Максимльно возможное кол-во для перемещения
                    ]);
                }
            }
        }
    }

    public function actionDelete($id) {
        if (Yii::$app->request->isAjax) {
            $tr_osnov = $this->findModel($id);
            $id_mattraffic = $tr_osnov->id_mattraffic;
            $tr_osnov->delete();
            echo Mattraffic::findOne($id_mattraffic)->delete();
        }
    }

    /**
     * Finds the TrOsnov model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TrOsnov the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TrOsnov::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
