<?php

namespace app\controllers\Fregat;

use app\func\Proc;
use Yii;
use app\models\Fregat\Spisosnovmaterials;
use app\models\Fregat\SpisosnovmaterialsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SpisosnovmaterialsController implements the CRUD actions for Spisosnovmaterials model.
 */
class SpisosnovmaterialsController extends Controller
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
                        'actions' => ['create', 'update', 'delete', 'addmattraffic'],
                        'allow' => true,
                        'roles' => ['SpisosnovaktEdit'],
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
     * Creates a new Spisosnovmaterials model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idspisosnovakt)
    {
        $model = new Spisosnovmaterials();
        $model->id_spisosnovakt = $idspisosnovakt;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            $model->spisosnovmaterials_number = empty($model->spisosnovmaterials_number) ? 1 : $model->spisosnovmaterials_number;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Spisosnovmaterials model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
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

    /**
     * Deletes an existing Spisosnovmaterials model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    // Для быстрого добавления материальной ценности в таблицу заявки на списание основных средств на форме "Обновить заявку на списание основных средств"
    public function actionAddmattraffic()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $id_mattraffic = Yii::$app->request->post('id_mattraffic');
            $id_spisosnovakt = Yii::$app->request->post('id_spisosnovakt');
            if (!empty($id_mattraffic) && !empty($id_spisosnovakt)) {
                $Spisosnovmaterials = new Spisosnovmaterials;
                $Spisosnovmaterials->id_mattraffic = $id_mattraffic;
                $Spisosnovmaterials->id_spisosnovakt = $id_spisosnovakt;
                $Spisosnovmaterials->spisosnovmaterials_number = 1;
                return [
                    'status' => $Spisosnovmaterials->save(),
                ];
            }
        }
    }

    /**
     * Finds the Spisosnovmaterials model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Spisosnovmaterials the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Spisosnovmaterials::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
