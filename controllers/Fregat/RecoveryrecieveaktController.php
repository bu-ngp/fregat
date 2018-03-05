<?php

namespace app\controllers\Fregat;

use app\func\ReportsTemplate\RecoveryrecieveaktReport;
use app\models\Fregat\OsmotraktSearch;
use app\models\Fregat\RecoveryrecieveaktSearch;
use app\models\Fregat\RraDocfiles;
use app\models\Fregat\RraDocfilesSearch;
use app\models\Fregat\UploadDocFile;
use Yii;
use app\models\Fregat\Recoveryrecieveakt;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\func\Proc;
use yii\web\UploadedFile;

/**
 * RecoveryrecieveaktController implements the CRUD actions for Recoveryrecieveakt model.
 */
class RecoveryrecieveaktController extends Controller
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
                        'actions' => ['recoveryrecieveakt-report'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['delete', 'addosmotrakt', 'update'],
                        'allow' => true,
                        'roles' => ['RecoveryEdit'],
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

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $UploadFile = new UploadDocFile;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            $searchModelrra = new RraDocfilesSearch();
            $dataProviderrra = $searchModelrra->search(Yii::$app->request->queryParams);
            $searchModel = new RecoveryrecieveaktSearch();
            $dataProvider = $searchModel->searchbase(Yii::$app->request->queryParams);

            $model->recoveryrecieveakt_date = empty($model->recoveryrecieveakt_date) ? date('Y-m-d') : $model->recoveryrecieveakt_date;

            return $this->render('update', [
                'model' => $model,
                'UploadFile' => $UploadFile,
                'dataProvider' => $dataProvider,
                'searchModelrra' => $searchModelrra,
                'dataProviderrra' => $dataProviderrra,
            ]);
        }
    }

    // Для быстрого добавления материальной ценности в таблицу актов осмотра на форме "Акт восстановления материальной ценности"
    public function actionAddosmotrakt()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $id_osmotrakt = Yii::$app->request->post('id_osmotrakt');
            $id_recoverysendakt = Yii::$app->request->post('id_recoverysendakt');
            if (!empty($id_osmotrakt) && !empty($id_recoverysendakt)) {
                $Recoveryrecieveakt = new Recoveryrecieveakt;
                $Recoveryrecieveakt->id_osmotrakt = $id_osmotrakt;
                $Recoveryrecieveakt->id_recoverysendakt = $id_recoverysendakt;
                return [
                    'status' => $Recoveryrecieveakt->save(),
                ];
            }
        }
    }

    // Печать акта получения материальной ценности
    public function actionRecoveryrecieveaktReport()
    {
        $Report = new RecoveryrecieveaktReport();
        echo $Report->Execute();
    }

    public function actionDelete($id)
    {
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
    protected function findModel($id)
    {
        if (($model = Recoveryrecieveakt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
