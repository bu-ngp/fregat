<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\TrOsnov;
use app\models\Fregat\TrOsnovSearch;
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


        $id_mattraffic = isset(Yii::$app->request->post('TrOsnov')['id_mattraffic']) ? Yii::$app->request->post('TrOsnov')['id_mattraffic'] : '';
        $mattraffic_number = isset(Yii::$app->request->post('Mattraffic')['mattraffic_number']) ? Yii::$app->request->post('Mattraffic')['mattraffic_number'] : NULL;

        $filleddata = false;

        if (!empty($id_mattraffic)) {
            $Mattrafficcurrent = Mattraffic::findOne($id_mattraffic);

            $Mattraffic->attributes = $Mattrafficcurrent->attributes;

            $Mattraffic->mattraffic_date = date('Y-m-d');
            $Mattraffic->mattraffic_number = $mattraffic_number;
            $Mattraffic->mattraffic_tip = 3;
            if ($Mattraffic->validate()) {
                $Mattraffic->save(false);
                $model->id_mattraffic = $Mattraffic->mattraffic_id;
            }
            $model->id_installakt = isset($_GET['idinstallakt']) ? $_GET['idinstallakt'] : Null;
            $model->tr_osnov_kab = isset(Yii::$app->request->post('TrOsnov')['tr_osnov_kab']) ? Yii::$app->request->post('TrOsnov')['tr_osnov_kab'] : NULL;

            $filleddata = true;
        }

        if ($filleddata && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            $id_mattraffic = isset(Yii::$app->request->get('TrOsnov')['id_mattraffic']) ? Yii::$app->request->get('TrOsnov')['id_mattraffic'] : '';

            if (!empty($id_mattraffic)) {
                $material_id = Mattraffic::findOne($id_mattraffic)->id_material;
                $employee_id = Mattraffic::findOne($id_mattraffic)->id_mol;

                $Material = Material::findOne($material_id);
                $Employee = Employee::findOne($employee_id);
                $Employee->id_person = $Employee->idperson->auth_user_fullname;
             //   $Employee->id_dolzh = $Employee->iddolzh->dolzh_name;
                $Employee->id_podraz = $Employee->idpodraz->podraz_name;
                $Employee->id_build = $Employee->idbuild->build_name;

                Proc::SetSessionValuesFromAR($Material, true);
                Proc::SetSessionValuesFromAR($Employee, true);
            }

            return $this->render('create', [
                        'model' => $model,
                        'Mattraffic' => $Mattraffic,
                        'Material' => $Material,
                        'Employee' => $Employee,
            ]);
        }
    }

    public function actionSelectinputfortrosnov($field, $q = null) {
        return Proc::select2request([
                    'model' => new Mattraffic,
                    'field' => $field,
                    'q' => $q,
                    'methodquery' => 'selectinputfortrosnov',
        ]);
    }

    public function actionFilltrosnov() {
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
                    'mattraffic_number' => $query->mattraffic_number,
                ]);
            }
        }
    }

    public function actionDelete($id) {

        $tr_osnov = $this->findModel($id);
        $id_mattraffic = $tr_osnov->id_mattraffic;
        $tr_osnov->delete();
        Mattraffic::findOne($id_mattraffic)->delete();

        //  return $this->redirect(Proc::GetLastURLBreadcrumbsFromSession());
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
