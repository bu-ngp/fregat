<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Material;
use app\models\Fregat\MaterialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\Mattraffic;

/**
 * MaterialController implements the CRUD actions for Material model.
 */
class MaterialController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $searchModel = new MaterialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate() {
        $model = new Material();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)

            return $this->redirect(['update', 'id' => $model->material_id]);
        } else {
            $model->material_number = 1;
            $model->material_price = 0;
            $model->material_tip = 1;
            $model->id_matvid = 1;
            $model->id_izmer = 1;
            $model->material_importdo = 1;

            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $Mattraffic = new Mattraffic;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {



            return $this->redirect(['index']);
        } else {
            /*          $Mattraffic->load(Yii::$app->request->get(), 'Mattraffic');
              $Mattraffic->id_material = $model->primaryKey;
              $Mattraffic->mattraffic_number = $model->material_number;


              if ($Mattraffic->validate())
              $Mattraffic->save(false);
             */

            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Material model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Material the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Material::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
