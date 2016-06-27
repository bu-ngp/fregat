<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Grupavid;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * GrupavidController implements the CRUD actions for Grupavid model.
 */
class GrupavidController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['createmain', 'delete'],
                        'allow' => true,
                        'roles' => ['GrupaEdit'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'createmain' => ['post'],
                ],
            ],
        ];
    }

    public function actionCreatemain($grupavid_id, $id_grupa) {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                Grupavid::updateAll(['grupavid_main' => 0], ['id_grupa' => $id_grupa]);
                echo Grupavid::updateAll(['grupavid_main' => 1], ['grupavid_id' => $grupavid_id]);
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                throw new Exception($e->getMessage());
            }
        }
    }

    /**
     * Deletes an existing Grupavid model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    /**
     * Finds the Grupavid model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Grupavid the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Grupavid::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
