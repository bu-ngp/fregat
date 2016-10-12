<?php

namespace app\controllers\Fregat;

use app\func\ReportsTemplate\RecoveryrecieveaktmatReport;
use app\models\Fregat\RecoveryrecieveaktmatSearch;
use app\models\Fregat\RramatDocfilesSearch;
use app\models\Fregat\TrMatOsmotrSearch;
use app\models\Fregat\UploadDocFile;
use Yii;
use app\models\Fregat\Recoveryrecieveaktmat;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * RecoveryrecieveaktmatController implements the CRUD actions for Recoveryrecieveaktmat model.
 */
class RecoveryrecieveaktmatController extends Controller
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
                        'actions' => ['recoveryrecieveaktmat-report'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['update', 'delete'],
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

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else {
            $searchModelrramat = new RramatDocfilesSearch();
            $dataProviderrramat = $searchModelrramat->search(Yii::$app->request->queryParams);
            $searchModel = new RecoveryrecieveaktmatSearch();
            $dataProvider = $searchModel->searchbase(Yii::$app->request->queryParams);

            return $this->render('update', [
                'model' => $model,
                'UploadFile' => $UploadFile,
                'dataProvider' => $dataProvider,
                'searchModelrramat' => $searchModelrramat,
                'dataProviderrramat' => $dataProviderrramat,
            ]);
        }
    }

    // Печать акта получения материалов у сторонней организации
    public function actionRecoveryrecieveaktmatReport()
    {
        $Report = new RecoveryrecieveaktmatReport();
        echo $Report->Execute();
    }

    /**
     * Deletes an existing Recoveryrecieveaktmat model.
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
     * Finds the Recoveryrecieveaktmat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Recoveryrecieveaktmat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Recoveryrecieveaktmat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
