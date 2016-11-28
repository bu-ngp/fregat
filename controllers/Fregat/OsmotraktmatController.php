<?php

namespace app\controllers\Fregat;

use app\func\ReportsTemplate\OsmotraktmatReport;
use app\models\Fregat\OsmotraktmatFilter;
use app\models\Fregat\TrMatOsmotr;
use Exception;
use Yii;
use app\models\Fregat\Osmotraktmat;
use app\models\Fregat\OsmotraktmatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\TrMatOsmotrSearch;

/**
 * OsmotraktmatController implements the CRUD actions for Osmotraktmat model.
 */
class OsmotraktmatController extends Controller
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
                        'actions' => ['index', 'fillnewinstallakt', 'selectinputforosmotrakt', 'forrecoveryrecieveakt', 'assign-to-recoveryrecieveakt', 'selectinputforrecoverysendakt', 'osmotraktmat-report', 'osmotraktmatfilter'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['OsmotraktEdit'],
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
        $searchModel = new OsmotraktmatSearch();
        $filter = Proc::SetFilter($searchModel->formName(), new OsmotraktmatFilter);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    public function actionCreate()
    {
        $model = new Osmotraktmat();
        $model->osmotraktmat_date = date('Y-m-d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->primaryKey]);
        } else {
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
            $searchModel = new TrMatOsmotrSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                TrMatOsmotr::deleteAll(['id_osmotraktmat' => $id]);
                echo $this->findModel($id)->delete();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage() . ' Удаление невозможно.');
            }
        }

    }

    // Печать акта осмотра материалов
    public function actionOsmotraktmatReport()
    {
        $Report = new OsmotraktmatReport();
        echo $Report->Execute();
    }

    public function actionOsmotraktmatfilter()
    {
        $model = new OsmotraktmatFilter;

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            Proc::PopulateFilterForm('OsmotraktmatSearch', $model);

            return $this->renderAjax('_osmotraktmatfilter', [
                'model' => $model,
            ]);
        }
    }

    protected function findModel($id)
    {
        if (($model = Osmotraktmat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
