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
                        'actions' => ['glaukindex'],
                        'allow' => true,
                        'roles' => ['GlaukUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update'],
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

    /**
     * Creates a new Patient model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Patient;
        $Glaukuchet = new Glaukuchet;
        $Fias = new Fias;
        $Fias->scenario = 'citychoose';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->patient_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'Fias' => $Fias,
                        'Glaukuchet' => $Glaukuchet,
            ]);
        }
    }

    /**
     * Updates an existing Patient model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $Glaukuchet = Glaukuchet::find(['id_patient' => $model->primaryKey])->one();
        $Glprep = new Glprep;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $Glprep->load(Yii::$app->request->get(), 'Glprep');
            $Glprep->id_glaukuchet = $Glaukuchet->primaryKey;
            if ($Glprep->validate())
                $Glprep->save(false);

            $searchModel = new GlprepSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('update', [
                        'model' => $model,
                        'Glaukuchet' => $Glaukuchet,
                        'Glprep' => $Glprep,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
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
