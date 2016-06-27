<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Import\Importconfig;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\func\Proc;

/**
 * ImportconfigController implements the CRUD actions for Importconfig model.
 */
class ImportconfigController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['FregatImport'],
                    ],
                ],
            ],
        ];
    }

    public function actionUpdate() {
        $model = Importconfig::findOne(1);

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else
            return $this->render('update', [
                        'model' => $model,
            ]);
    }

    /**
     * Finds the Importconfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Importconfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Importconfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
