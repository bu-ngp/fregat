<?php

namespace app\controllers;

use Yii;
use app\models\Employee;
use app\models\EmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->sort->attributes['iddolzh.dolzh_name'] = [
            'asc' => ['iddolzh.dolzh_name' => SORT_ASC],
            'desc' => ['iddolzh.dolzh_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['idbuild.build_name'] = [
            'asc' => ['idbuild.build_name' => SORT_ASC],
            'desc' => ['idbuild.build_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['idpodraz.podraz_name'] = [
            'asc' => ['idpodraz.podraz_name' => SORT_ASC],
            'desc' => ['idpodraz.podraz_name' => SORT_DESC],
        ];

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('index', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Employee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $session = new Session;
            $session->open();
            $fmodel = substr($model->className(), strrpos($model->className(), '\\') + 1);
            if (is_array(['foreign']) && count($session[$fmodel]['foreign']) > 0) {
                $field = $session[$fmodel]['foreign']['field'];
                $value = '';
                
                if (isset(Yii::$app->request->get()[$fmodel][$field]))
                    $value = Yii::$app->request->get()[$fmodel][$field];
                elseif (isset($session[$fmodel]['attributes'][$field]))
                    $value = $session[$fmodel]['attributes'][$field];

                $session[$fmodel] = array_replace_recursive($session[$fmodel], [
                    'foreign' => NULL,
                ]);

                $model->load($session[$fmodel], 'attributes');
            } else
                $session[$fmodel] = [
                    'attributes' => [],
                ];


            $session->close();

            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $session = new Session;
            $session->open();
            $fmodel = substr($model->className(), strrpos($model->className(), '\\') + 1);

            if (is_array($session[$fmodel]['foreign']) && count($session[$fmodel]['foreign']) > 0) {
                $field = $session[$fmodel]['foreign']['field'];
                $value = '';
                
                if (isset(Yii::$app->request->get()[$fmodel][$field]))
                    $value = Yii::$app->request->get()[$fmodel][$field];
                elseif (isset($session[$fmodel]['attributes'][$field]))
                    $value = $session[$fmodel]['attributes'][$field];
                
                $session[$fmodel] = array_replace_recursive($session[$fmodel], [
                    'attributes' => [
                        $field => $value,
                    ]
                ]);

                $session[$fmodel] = array_replace_recursive($session[$fmodel], [
                    'foreign' => NULL,
                ]);

                $model->load($session[$fmodel], 'attributes');
            } else {
                $session[$fmodel] = array_replace_recursive(isset($session[$fmodel]) ? $session[$fmodel] : [], [
                    'attributes' => $model->attributes,
                ]);
            }

            $session->close();

            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
