<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Import\Logreport;
use app\models\Fregat\Import\LogreportSearch;
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
class LogreportController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'clear'],
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

    public function actionIndex() {
        $searchModel = new LogreportSearch();
        $dataProvider = $searchModel->searchreport(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionClear() {
        Traflog::deleteAll();
        Matlog::deleteAll();
        Employeelog::deleteAll();
        Logreport::deleteAll();

        // Удалить все файлы с расширением .xlsx в папке "importreports"
        array_map('unlink', glob("importreports/*.xlsx"));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Logreport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Logreport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Logreport::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
