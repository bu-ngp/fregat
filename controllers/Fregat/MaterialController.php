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
        if (isset($model->scenarios()['prihod']))
            $model->scenario = 'prihod';
        $Mattraffic = new Mattraffic;

        if ($model->load(Yii::$app->request->post())) {

            if (empty($model->material_name1c))
                $model->material_name1c = $model->material_name;

            if ($model->save()) {
                $Mattraffic->id_material = empty($Mattraffic->id_material) ? $model->material_id : $Mattraffic->id_material;
                $Mattraffic->mattraffic_number = empty($Mattraffic->mattraffic_number) ? $model->material_number : $Mattraffic->mattraffic_number;
                $Mattraffic->mattraffic_tip = empty($Mattraffic->mattraffic_tip) ? 1 : $Mattraffic->mattraffic_tip;

                if ($Mattraffic->load(Yii::$app->request->post()) && $Mattraffic->save())
                    return $this->redirect(['index']);
                else
                    return $this->render('create', [
                                'model' => $model,
                                'Mattraffic' => $Mattraffic,
                    ]);
            } else
                return $this->render('create', [
                            'model' => $model,
                            'Mattraffic' => $Mattraffic,
                ]);
        } else {
            $model->material_number = empty($model->material_number) ? 1 : $model->material_number;
            $model->material_price = empty($model->material_price) ? 1 : $model->material_price;
            $model->material_tip = empty($model->material_tip) ? 1 : $model->material_tip;
            $model->id_matvid = empty($model->id_matvid) ? 1 : $model->id_matvid;
            $model->id_izmer = empty($model->id_izmer) ? 1 : $model->id_izmer;
            $model->material_importdo = empty($model->material_importdo) ? 1 : $model->material_importdo;
            $Mattraffic->mattraffic_date = empty($Mattraffic->mattraffic_date) ? date('d.m.Y') : $Mattraffic->mattraffic_date;

            return $this->render('create', [
                        'model' => $model,
                        'Mattraffic' => $Mattraffic,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $Mattraffic = Mattraffic::find()
                ->andWhere([
                    'id_material' => $model->material_id,
                    'mattraffic_tip' => 1,
                ])
                ->orderBy('mattraffic_date desc, mattraffic_id desc')
                ->one();

        if ($model->load(Yii::$app->request->post()) && $model->save() && $Mattraffic->load(Yii::$app->request->post()) && $Mattraffic->save()) {



            return $this->redirect(['index']);
        } else {            
            $model->material_release  = Yii::$app->formatter->asDate($model->material_release);
            $Mattraffic->mattraffic_date = Yii::$app->formatter->asDate($Mattraffic->mattraffic_date);

            return $this->render('update', [
                        'model' => $model,
                        'Mattraffic' => $Mattraffic,
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
