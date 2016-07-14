<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Recoveryrecieveakt;
use app\models\Fregat\RecoveryrecieveaktSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\func\Proc;

/**
 * RecoveryrecieveaktController implements the CRUD actions for Recoveryrecieveakt model.
 */
class RecoveryrecieveaktController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                    // 'roles' => ['RecoveryEdit'],
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

    public function actionDelete($id) {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    /**
     * Finds the Recoveryrecieveakt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Recoveryrecieveakt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Recoveryrecieveakt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
