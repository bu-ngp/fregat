<?php

namespace app\controllers\Fregat;

use app\func\ReportsTemplate\InstallaktReport;
use app\models\Fregat\InstallaktFilter;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrOsnov;
use Exception;
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
use app\func\ReportTemplates;

/**
 * InstallaktController implements the CRUD actions for Installakt model.
 */
class InstallaktController extends Controller
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
                        'actions' => ['index', 'installakt-report', 'installaktfilter'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['InstallEdit'],
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

    public function actionIndex()
    {
        $searchModel = new InstallaktSearch();
        $filter = Proc::SetFilter($searchModel->formName(), new InstallaktFilter);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    public function actionCreate()
    {
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

    public function actionUpdate($id)
    {
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

    // Печать акта перемещения материальных ценностей
    public function actionInstallaktReport()
    {
        $Report = new InstallaktReport();
        echo $Report->Execute();
    }

    public function actionInstallaktfilter()
    {
        $model = new InstallaktFilter;

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            Proc::PopulateFilterForm('InstallaktSearch', $model);

            return $this->renderAjax('_installaktfilter', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                TrOsnov::deleteAll(['id_installakt' => $id]);
                TrMat::deleteAll(['id_installakt' => $id]);

                $Installakt = $this->findModel($id)->delete();

                if ($Installakt === false)
                    throw new Exception('Удаление невозможно.');

                echo $Installakt;
                
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage() . ' Удаление невозможно.');
            }
        }
    }

    /**
     * Finds the Installakt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Installakt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Installakt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
