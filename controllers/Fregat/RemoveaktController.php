<?php

namespace app\controllers\Fregat;

use app\func\ReportsTemplate\RemoveaktReport;
use app\models\Fregat\RemoveaktFilter;
use app\models\Fregat\TrRmMat;
use Exception;
use Yii;
use app\models\Fregat\Removeakt;
use app\models\Fregat\RemoveaktSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\TrRmMatSearch;

/**
 * RemoveaktController implements the CRUD actions for Removeakt model.
 */
class RemoveaktController extends Controller
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
                        'actions' => ['index', 'removeakt-report', 'removeaktfilter'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['RemoveaktEdit'],
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
        $searchModel = new RemoveaktSearch();
        $filter = Proc::SetFilter($searchModel->formName(), new RemoveaktFilter);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    public function actionCreate()
    {
        $model = new Removeakt();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->removeakt_id]);
        } else {
            $model->removeakt_date = empty($model->removeakt_date) ? date('Y-m-d') : $model->removeakt_date;

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
            $searchModel = new TrRmMatSearch();
            $dataProvider = $searchModel->search($Request);

            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    // Печать акта снятия комплектующих с материальных ценностей
    public function actionRemoveaktReport()
    {
        $Report = new RemoveaktReport();
        echo $Report->Execute();
    }

    public function actionRemoveaktfilter()
    {
        $model = new RemoveaktFilter;

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            Proc::PopulateFilterForm('RemoveaktSearch', $model);

            return $this->renderAjax('_removeaktfilter', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                TrRmMat::deleteAll(['id_removeakt' => $id]);

                $Removeakt = $this->findModel($id)->delete();

                if ($Removeakt === false)
                    throw new Exception('Удаление невозможно.');

                echo $Removeakt;

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage() . ' Удаление невозможно.');
            }
        }
    }

    /**
     * Finds the Removeakt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Removeakt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Removeakt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
