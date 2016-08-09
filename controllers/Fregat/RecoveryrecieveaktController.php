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
use app\models\Fregat\Osmotrakt;

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
                        'actions' => ['delete', 'addosmotrakt', 'update'],
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

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    // Для быстрого добавления материальной ценности в таблицу актов осмотра на форме "Акт восстановления материальной ценности"
    public function actionAddosmotrakt() {
        if (Yii::$app->request->isAjax) {
            $id_osmotrakt = Yii::$app->request->post('id_osmotrakt');
            $id_recoverysendakt = Yii::$app->request->post('id_recoverysendakt');
            if (!empty($id_osmotrakt) && !empty($id_recoverysendakt)) {
                $Recoveryrecieveakt = new Recoveryrecieveakt;
                $Recoveryrecieveakt->id_osmotrakt = $id_osmotrakt;
                $Recoveryrecieveakt->id_recoverysendakt = $id_recoverysendakt;
                echo json_encode([
                    'status' => $Recoveryrecieveakt->save(),
                ]);
            }
        }
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
