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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tr_osnov_id]);
        } else {
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
