<?php

namespace app\controllers\Glauk;

use Yii;
use app\models\Glauk\Glprep;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * GlprepController implements the CRUD actions for Glprep model.
 */
class GlprepController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => ['GlaukUserPermission'],
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

    public function actionCreate($idglaukuchet) {
        $model = new Glprep();
        $model->id_glaukuchet = $idglaukuchet;

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else
            return $this->render('create', [
                        'model' => $model,
                        'idglaukuchet' => $idglaukuchet,
            ]);
    }

    public function actionDelete($id) {
        if (Yii::$app->request->isAjax) {
            $id_glaukuchet = $this->findModel($id)->id_glaukuchet;
            echo $this->findModel($id)->delete();
        }
    }

    /**
     * Finds the Glprep model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Glprep the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Glprep::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
