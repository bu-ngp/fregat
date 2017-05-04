<?php

namespace app\controllers\Fregat;

use app\func\Proc;
use app\func\ReportsTemplate\NakladReport;
use app\models\Fregat\NakladFilter;
use app\models\Fregat\Nakladmaterials;
use app\models\Fregat\NakladmaterialsSearch;
use Exception;
use Yii;
use app\models\Fregat\Naklad;
use app\models\Fregat\NakladSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NakladController implements the CRUD actions for Naklad model.
 */
class NakladController extends Controller
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
                        'actions' => ['index', 'naklad-report', 'nakladfilter'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['NakladEdit'],
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

    /**
     * Lists all Naklad models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NakladSearch();
        $filter = Proc::SetFilter($searchModel->formName(), new NakladFilter);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * Creates a new Naklad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Naklad();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->primaryKey]);
        } else {
            $model->naklad_date = empty($model->naklad_date) ? date('Y-m-d') : $model->naklad_date;

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Naklad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            $searchModel = new NakladmaterialsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    // Печать требования-накладной
    public function actionNakladReport()
    {
        $Report = new NakladReport();
        echo $Report->Execute(false);
    }

    public function actionNakladfilter()
    {
        $model = new NakladFilter;

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            Proc::PopulateFilterForm('NakladSearch', $model);

            return $this->renderAjax('_nakladfilter', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Naklad model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                Nakladmaterials::deleteAll(['id_naklad' => $id]);

                $Naklad = $this->findModel($id)->delete();

                if ($Naklad === false)
                    throw new Exception('Удаление невозможно.');

                echo $Naklad;
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage() . ' Удаление невозможно.');
            }
        }
    }

    /**
     * Finds the Naklad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Naklad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Naklad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
