<?php

namespace app\controllers\Fregat;

use app\func\Proc;
use app\models\Fregat\Mattraffic;
use Yii;
use app\models\Fregat\Nakladmaterials;
use app\models\Fregat\NakladmaterialsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NakladmaterialsController implements the CRUD actions for Nakladmaterials model.
 */
class NakladmaterialsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['selectinputfornakladmaterials'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete', 'max-number-material-by-mol'],
                        'allow' => true,
                        'roles' => ['NakladEdit'],
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

    /**
     * Creates a new Nakladmaterials model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Nakladmaterials();
        $model->id_naklad = (string)filter_input(INPUT_GET, 'idnaklad');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            $model->nakladmaterials_number = empty($model->nakladmaterials_number) ? 1 : $model->nakladmaterials_number;

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Nakladmaterials model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id, $idnaklad)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSelectinputfornakladmaterials($q = null/*, $idnaklad*/)
    {
        return Proc::ResultSelect2([
            'model' => new Mattraffic,
            'q' => $q,
            'methodquery' => 'selectinputfornakladmaterials',
         //   'MethodParams' => ['idnaklad' => $idnaklad],
        ]);
    }

    public function actionMaxNumberMaterialByMol()
    {
        if (Yii::$app->request->isAjax) {
            $mattraffic_id = Yii::$app->request->post('mattraffic_id');
            if (!empty($mattraffic_id)) {
                $query = Mattraffic::findOne($mattraffic_id);
                if (!empty($query)) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'mattraffic_number' => $query->mattraffic_number,
                    ];
                }
            }
        }
    }

    /**
     * Deletes an existing Nakladmaterials model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    /**
     * Finds the Nakladmaterials model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Nakladmaterials the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Nakladmaterials::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
