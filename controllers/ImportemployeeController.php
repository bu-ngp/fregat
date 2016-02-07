<?php

namespace app\controllers;

use Yii;
use app\models\Importemployee;
use app\models\ImportemployeeSearch;
use app\models\ImpemployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;

/**
 * ImportemployeeController implements the CRUD actions for Importemployee model.
 */
class ImportemployeeController extends Controller {

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
     * Lists all Importemployee models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ImportemployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
     * Displays a single Importemployee model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Importemployee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Importemployee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //  return $this->redirect(['index']);
            $session = new Session;
            $session->open();
            $bc = $session['breadcrumbs'];
            end($bc);
            unset($bc[key($bc)]);
            $session['breadcrumbs'] = $bc;
            $session->close();

            return $this->redirect(['update', 'id' => $model->importemployee_id]);
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
                    'attributes' => [
                        $field => $value,
                    ]
                ]);

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
     * Updates an existing Importemployee model.
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
                //unset($session[$fmodel]);
                $session[$fmodel] = array_replace_recursive(isset($session[$fmodel]) ? $session[$fmodel] : [], [
                    'attributes' => $model->attributes,
                ]);
            }


            $session->close();

            $searchModel = new ImpemployeeSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $dataProvider->sort->attributes['idemployee.employee_id'] = [
                'asc' => ['idemployee.employee_id' => SORT_ASC],
                'desc' => ['idemployee.employee_id' => SORT_DESC],
            ];
            
            $dataProvider->sort->attributes['idemployee.employee_fio'] = [
                'asc' => ['idemployee.employee_fio' => SORT_ASC],
                'desc' => ['idemployee.employee_fio' => SORT_DESC],
            ];

            $dataProvider->sort->attributes['idemployee.iddolzh.dolzh_name'] = [
                'asc' => ['iddolzh.dolzh_name' => SORT_ASC],
                'desc' => ['iddolzh.dolzh_name' => SORT_DESC],
            ];

            $dataProvider->sort->attributes['idemployee.idbuild.build_name'] = [
                'asc' => ['idbuild.build_name' => SORT_ASC],
                'desc' => ['idbuild.build_name' => SORT_DESC],
            ];

            $dataProvider->sort->attributes['idemployee.idpodraz.podraz_name'] = [
                'asc' => ['idpodraz.podraz_name' => SORT_ASC],
                'desc' => ['idpodraz.podraz_name' => SORT_DESC],
            ];

            return $this->render('update', [
                        'model' => $model,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Importemployee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Importemployee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Importemployee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Importemployee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
