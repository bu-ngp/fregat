<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Installakt;
use app\models\Fregat\InstallaktSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\TrOsnovSearch;
use app\models\Fregat\TrMatSearch;

/**
 * InstallaktController implements the CRUD actions for Installakt model.
 */
class InstallaktController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                    //  'roles' => ['InstallaktEdit'],
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
        $searchModel = new InstallaktSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Installakt();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->installakt_id]);
        } else {
            $model->installakt_date = empty($model->installakt_date) ? date('Y-m-d') : $model->installakt_date;

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
            $Request = Yii::$app->request->queryParams;
            $searchModelOsn = new TrOsnovSearch();
            $dataProviderOsn = $searchModelOsn->search($Request);
            $searchModelMat = new TrMatSearch();
            $dataProviderMat = $searchModelMat->search($Request);

            return $this->render('update', [
                        'model' => $model,
                        'searchModelOsn' => $searchModelOsn,
                        'dataProviderOsn' => $dataProviderOsn,
                        'searchModelMat' => $searchModelMat,
                        'dataProviderMat' => $dataProviderMat,
            ]);
        }
    }

    public function actionDelete($id) {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    /**
     * Finds the Installakt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Installakt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Installakt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
