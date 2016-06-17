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
use yii\base\Model;

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
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['GlaukOperatorPermission'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['PatientRemoveRole'],
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
        $Fias->AOGUID = '0bf0f4ed-13f8-446e-82f6-325498808076';

        if ($Fias->load(Yii::$app->request->post())) {
            if (!empty($Fias->AOGUID)) {
                $Fias = Fias::findOne($Fias->AOGUID);

                if ($Fias->AOLEVEL > 4 && $Fias->AOLEVEL < 7) {
                    $model->id_fias = $Fias->AOGUID;
                    $model->scenario = 'nostreetrequired';
                } else
                    $model->scenario = 'streetrequired';
            }
        }
        $Fias->scenario = 'citychooserequired';

        $dopparams = ['dopparams' => []];

        if ($patienttype === 'glauk') {
            $Glaukuchet = new Glaukuchet;
            $Glaukuchet->glaukuchet_lastvisit = date('Y-m-d');
            $Glaukuchet->scenario = 'forvalidatewithout_id_patient';
            $dopparams['dopparams']['Glaukuchet'] = $Glaukuchet;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($patienttype === 'glauk' && $model->load(Yii::$app->request->post()) && $Glaukuchet->load(Yii::$app->request->post()) && Model::validateMultiple([$model, $Glaukuchet, $Fias])) {
                $model->save(false);
                $Glaukuchet->id_patient = $model->primaryKey;
                $Glaukuchet->save(false);

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
        $dopparams = ['dopparams' => []];
        $model = $this->findModel($id);
        $modelloaded = $model->load(Yii::$app->request->post());
        $GlaukuchetIsNew = false;

        // Адрес
        $Fias = new Fias;

        // отправлена форма
        if (isset($_POST['Fias']['AOGUID'])) {

            // город не заполнен
            if (empty($_POST['Fias']['AOGUID'])) {
                $model->id_fias = NULL;
                $model->patient_dom = NULL;
                $model->patient_korp = NULL;
                $model->patient_kvartira = NULL;
            } else {// город заполнен (Если улица есть, иначе Если улицы нет)
                $Fias->AOGUID = $_POST['Fias']['AOGUID'];
                // Если есть улицы
                if (Fias::Checkstreets($_POST['Fias']['AOGUID']) > 0) {
                    // Если улица заполнена, иначе Если улица не заполнена
                    $model->id_fias = isset($_POST['Patient']['id_fias']) ? $_POST['Patient']['id_fias'] : NULL;
                } else  // Если улиц нет
                    $model->id_fias = Fias::findOne($_POST['Fias']['AOGUID'])->AOGUID;

                $model->id_fias = Fias::Checkstreets($_POST['Fias']['AOGUID']) > 0 /* isset($_POST['Patient']['id_fias']) */ ? $_POST['Patient']['id_fias'] : Fias::findOne($_POST['Fias']['AOGUID'])->AOGUID;
                $model->scenario = 'streetrequired';
            }
        } elseif (!empty($model->id_fias)) { // просто загрузка страницы, если адрес заполнен
            $address = Fias::findOne($model->id_fias);

            // Если адрес Улица, иначе Если адрес поселок без улиц
            $Fias = $address->AOLEVEL == 7 ? Fias::findOne($address->PARENTGUID) : $address;
        }

        if ($patienttype === 'glauk') {
            $Glaukuchet = Glaukuchet::findOne(['id_patient' => $model->primaryKey]);

            if (empty($Glaukuchet)) {
                $Glaukuchet = new Glaukuchet;
                $Glaukuchet->id_patient = $model->primaryKey;
                $GlaukuchetIsNew = true;
            }

            $dopparams['dopparams']['Glaukuchet'] = $Glaukuchet;
            if (!$Glaukuchet->isNewRecord) {
                $Glprep = new Glprep;
                $Glprep->load(Yii::$app->request->get(), 'Glprep');
                $Glprep->id_glaukuchet = $Glaukuchet->primaryKey;
                if ($Glprep->validate())
                    $Glprep->save(false);
            }
            $searchModelglprep = new GlprepSearch();
            $dataProviderglprep = $searchModelglprep->search(Yii::$app->request->queryParams);
            $dopparams['dopparams']['Glprep'] = $Glprep;
            $dopparams['dopparams']['searchModelglprep'] = $searchModelglprep;
            $dopparams['dopparams']['dataProviderglprep'] = $dataProviderglprep;
        }

        $Fias->scenario = 'citychooserequired';

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($patienttype === 'glauk' && $modelloaded && $Glaukuchet->load(Yii::$app->request->post()) && Model::validateMultiple([$model, $Glaukuchet, $Fias])) {
                $model->save(false);
                $Glaukuchet->save(false);

                $transaction->commit();

                if ($GlaukuchetIsNew) {


                    return $this->render('update', array_merge([
                                'model' => $model,
                                'Fias' => $Fias,
                                'patienttype' => $patienttype,
                                            ], $dopparams));
                } else {
                    return $this->redirect([$patienttype . 'index']);
                }
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
        if (Yii::$app->request->isAjax) {
            try {
                $Glaukuchet = Glaukuchet::findOne(['id_patient' => $id]);
                if (!empty($Glaukuchet)) {
                    Glprep::deleteAll(['id_glaukuchet' => $Glaukuchet->primaryKey]);
                    Glaukuchet::deleteAll(['id_patient' => $id]);
                }

                echo $this->findModel($id)->delete();
            } catch (Exception $e) {
                $transaction->rollback();
                throw new Exception($e->getMessage());
            }
        }
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
