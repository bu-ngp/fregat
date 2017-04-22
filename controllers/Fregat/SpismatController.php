<?php

namespace app\controllers\Fregat;

use app\func\ReportsTemplate\SpismatReport;
use app\models\Fregat\SpismatFilter;
use app\models\Fregat\Spismatmaterials;
use app\models\Fregat\SpismatmaterialsSearch;
use app\models\Fregat\TrMat;
use Exception;
use Yii;
use app\func\Proc;
use app\models\Fregat\Spismat;
use app\models\Fregat\SpismatSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SpismatController implements the CRUD actions for Spismat model.
 */
class SpismatController extends Controller
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
                        'actions' => ['index', 'spismat-report', 'spismatfilter'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete', 'create-by-installakt', 'check-materials'],
                        'allow' => true,
                        'roles' => ['SpismatEdit'],
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
     * Lists all Spismat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpismatSearch();
        $filter = Proc::SetFilter($searchModel->formName(), new SpismatFilter);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * Creates a new Spismat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Spismat();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
            return $this->redirect(['update', 'id' => $model->primaryKey]);
        } else {
            $model->spismat_date = empty($model->spismat_date) ? date('Y-m-d') : $model->spismat_date;

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Spismat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateByInstallakt()
    {
        $model = new Spismat();
        $params = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load($params)
                && TrMat::getCountMaterials($params['Spismat']['id_mol'], $params['Spismat']['period_beg'], $params['Spismat']['period_end'], $params['Spismat']['spisinclude']) > 0
                && $model->save()
            ) {
                $rows = TrMat::getMaterialsSpismat($params['Spismat']['id_mol'], $params['Spismat']['period_beg'], $params['Spismat']['period_end'], $params['Spismat']['spisinclude']);
                foreach ($rows as $ar) {
                    $sm = new Spismatmaterials;
                    $sm->id_spismat = $model->primaryKey;
                    $sm->id_mattraffic = $ar['id_mattraffic'];
                    $sm->save();
                }

                Proc::RemoveLastBreadcrumbsFromSession(); // Удаляем последнюю хлебную крошку из сессии (Создать меняется на Обновить)
                $transaction->commit();
                return $this->redirect(['update', 'id' => $model->primaryKey]);
            } else {
                $model->spismat_date = empty($model->spismat_date) ? date('Y-m-d') : $model->spismat_date;
                $transaction->rollBack();
                return $this->render('create_by_installakt', [
                    'model' => $model,
                ]);
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Updates an existing Spismat model.
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
            $searchModel = new SpismatmaterialsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionSpismatfilter()
    {
        $model = new SpismatFilter;

        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {
            Proc::PopulateFilterForm('SpismatSearch', $model);

            return $this->renderAjax('_spismatfilter', [
                'model' => $model,
            ]);
        }
    }

    public function actionCheckMaterials()
    {
        if (Yii::$app->request->isAjax) {
            $params = json_decode(Yii::$app->request->post()['params']);

            if ($params === null)
                throw new HttpException(500, 'не валидный JSON запрос');

            if (!ctype_digit($params->id_mol))
                throw new HttpException(500, 'не валидный id_mol');

            if (!preg_match('/\d{4}-\d{2}-\d{2}/', $params->period_beg))
                throw new HttpException(500, 'не валидный period_beg');

            if (!preg_match('/\d{4}-\d{2}-\d{2}/', $params->period_end))
                throw new HttpException(500, 'не валидный period_end');

            return json_encode(['count' => TrMat::getCountMaterials($params->id_mol, $params->period_beg, $params->period_end, $params->spisinclude)]);
        } else
            throw new HttpException(500, 'не Ajax запрос');
    }

    // Печать ведомости списания материалов
    public function actionSpismatReport()
    {
        $Report = new SpismatReport();
        echo $Report->Execute(false);
    }

    /**
     * Deletes an existing Spismat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                Spismatmaterials::deleteAll(['id_spismat' => $id]);
                echo $this->findModel($id)->delete();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage() . ' Удаление невозможно.');
            }
        }
    }

    /**
     * Finds the Spismat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Spismat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Spismat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
