<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Recoverysendakt;
use app\models\Fregat\RecoverysendaktSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\Recoveryrecieveakt;
use app\models\Fregat\RecoveryrecieveaktSearch;
use app\func\ReportTemplates;
use app\models\Fregat\RecoveryrecieveaktmatSearch;

/**
 * RecoverysendaktController implements the CRUD actions for Recoverysendakt model.
 */
class RecoverysendaktController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'recoverysendakt-report', 'recoverysendaktmat-report'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
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

    public function actionIndex() {
        $searchModel = new RecoverysendaktSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Recoverysendakt();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->recoverysendakt_id]);
        } else {
            $model->recoverysendakt_date = empty($model->recoverysendakt_date) ? date('Y-m-d') : $model->recoverysendakt_date;

            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            $searchModel = new RecoveryrecieveaktSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $searchModelmat = new RecoveryrecieveaktmatSearch();
            $dataProvidermat = $searchModelmat->search(Yii::$app->request->queryParams);

            return $this->render('update', [
                        'model' => $model,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'searchModelmat' => $searchModelmat,
                        'dataProvidermat' => $dataProvidermat,
            ]);
        }
    }

    public function actionDelete($id) {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    // Печать акта осмотра
    public function actionRecoverysendaktReport() {
        ReportTemplates::Recoverysendakt();
    }

    // Печать акта отправки материалов сторонней организации
    public function actionRecoverysendaktmatReport() {
        ReportTemplates::Recoverysendaktmat();
    }

    /**
     * Finds the Recoverysendakt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Recoverysendakt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Recoverysendakt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
