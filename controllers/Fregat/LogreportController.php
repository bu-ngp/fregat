<?php

namespace app\controllers\Fregat;

use app\func\Proc;
use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Mattraffic;
use Yii;
use app\models\Fregat\Import\Logreport;
use app\models\Fregat\Import\LogreportSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Fregat\Import\Matlog;
use app\models\Fregat\Import\Traflog;
use app\models\Fregat\Import\Employeelog;

/**
 * LogreportController implements the CRUD actions for Logreport model.
 */
class LogreportController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'clear', 'remove-import'],
                        'allow' => true,
                        'roles' => ['FregatImport'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'clear' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new LogreportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $Importconfig = Importconfig::findOne(1);

        if ($Importconfig->load(Yii::$app->request->post()) && $Importconfig->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            return $this->render('index', [
                'Importconfig' => $Importconfig,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionClear()
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                Traflog::deleteAll();
                Matlog::deleteAll();
                Employeelog::deleteAll();
                echo Logreport::deleteAll();

                // Удалить все файлы с расширением .xlsx в папке "importreports"
                array_map('unlink', glob("importreports/*.xlsx"));
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        }
    }

    public function actionRemoveImport($id)
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                echo 'Traflog deleted '. Traflog::deleteAll(['id_logreport' => $id]) . ' rows.<br>';
                echo 'Matlog deleted '. Matlog::deleteAll(['id_logreport' => $id]) . ' rows.<br>';
                echo 'Employeelog deleted '. Employeelog::deleteAll(['id_logreport' => $id]) . ' rows.<br>';
                echo 'Logreport deleted '. Logreport::deleteAll(['logreport_id' => $id]) . ' rows.<br>';

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }

        }
    }

    /**
     * Finds the Logreport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Logreport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Logreport::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
