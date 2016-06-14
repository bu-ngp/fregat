<?php

namespace app\controllers\Base;

use Yii;
use app\models\Base\Patient;
use app\models\Base\PatientSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Glauk\Glprep;
use app\models\Glauk\Glaukuchet;
use app\models\Glauk\GlprepSearch;
use app\models\Base\Fias;

/**
 * PatientController implements the CRUD actions for Patient model.
 */
class PatientController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['glaukindex', 'update'],
                        'allow' => true,
                        'roles' => ['GlaukUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => ['GlaukOperatorPermission'],
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

    public function actionGlaukindex() {
        Proc::SetMenuButtons('glauk');
        $searchModel = new PatientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('glaukindex', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($patienttype) {
        $model = new Patient;
        $Fias = new Fias;
        $Fias->scenario = 'citychoose';
        $dopparams = [];

        if ($patienttype === 'glauk') {
            $Glaukuchet = new Glaukuchet;
            $dopparams['Glaukuchet'] = $Glaukuchet;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($patienttype === 'glauk' && $model->load(Yii::$app->request->post()) && $model->save() && $Glaukuchet->load(array_replace_recursive(Yii::$app->request->post(), ['Glaukuchet' => ['id_patient' => $model->patient_id]])) && $Glaukuchet->save()) {
                Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
                $transaction->commit();
                return $this->redirect(['update', 'id' => $model->patient_id, 'patienttype' => $patienttype]);
            } else {
                // Откатываем транзакцию
                $transaction->rollback();
                return $this->render('create', array_merge([
                            'model' => $model,
                            'Fias' => $Fias,
                            'patienttype' => $patienttype,
                                        ], $dopparams));
            }
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function actionUpdate($id, $patienttype) {
        $dopparams = [];
        $model = $this->findModel($id);
        $Fias = Fias::FindOne(Fias::findOne($model->id_fias)->PARENTGUID);

        if ($patienttype === 'glauk') {
            $Glaukuchet = Glaukuchet::findOne(['id_patient' => $model->primaryKey]);
            $dopparams['Glaukuchet'] = $Glaukuchet;
            $Glprep = new Glprep;
            $Glprep->load(Yii::$app->request->get(), 'Glprep');
            $Glprep->id_glaukuchet = $Glaukuchet->primaryKey;
            if ($Glprep->validate())
                $Glprep->save(false);

            $searchModelglprep = new GlprepSearch();
            $dataProviderglprep = $searchModelglprep->search(Yii::$app->request->queryParams);
            $dopparams['Glprep'] = $Glprep;
            $dopparams['searchModelglprep'] = $searchModelglprep;
            $dopparams['dataProviderglprep'] = $dataProviderglprep;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {

            if ($patienttype === 'glauk' && $model->load(Yii::$app->request->post()) && $model->save() && $Glaukuchet->load(Yii::$app->request->post()) && $Glaukuchet->save()) {
                $transaction->commit();
                return $this->redirect([$patienttype . 'index']);
            } else {
                // Откатываем транзакцию
                $transaction->rollback();
                return $this->render('update', array_merge([
                            'model' => $model,
                            'Fias' => $Fias,
                            'patienttype' => $patienttype,
                                        ], $dopparams));
            }
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Deletes an existing Patient model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Patient model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Patient the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Patient::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
