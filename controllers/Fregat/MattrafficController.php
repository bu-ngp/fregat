<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\MattrafficSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * MattrafficController implements the CRUD actions for Mattraffic model.
 */
class MattrafficController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'selectinputformaterial', 'forinstallakt', 'forinstallakt_mat', 'assign-to-material'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        Proc::SetMenuButtons('fregat');
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionForinstallakt() {
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->searchforinstallakt(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'foreigndo' => (string) filter_input(INPUT_GET, 'foreigndo'),
        ]);
    }

    public function actionForinstallakt_mat() {
        $searchModel = new MattrafficSearch();
        $dataProvider = $searchModel->searchforinstallakt_mat(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'foreigndo' => (string) filter_input(INPUT_GET, 'foreigndo'),
        ]);
    }

    public function actionSelectinputformaterial($field, $q = null) {
        return Proc::select2request([
                    'model' => new Mattraffic,
                    'field' => $field,
                    'q' => $q,
                    'methodquery' => 'selectinput',
        ]);
    }

    public function actionAssignToMaterial() {
        Proc::AssignToModelFromGrid();
        $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
    }

    /**
     * Finds the Mattraffic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Mattraffic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Mattraffic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
