<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\TrMatOsmotr;
use app\models\Fregat\TrMatOsmotrSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\func\Proc;
use yii\filters\AccessControl;
use app\models\Fregat\Recoverysendakt;

/**
 * TrMatOsmotrController implements the CRUD actions for TrMatOsmotr model.
 */
class TrMatOsmotrController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete', 'forrecoveryrecieveaktmat', 'assign-to-recoverysendakt'],
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

    public function actionForrecoveryrecieveaktmat() {
        $searchModel = new TrMatOsmotrSearch();
        $dataProvider = $searchModel->forrecoveryrecieveaktmat(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new TrMatOsmotr();
        $model->id_osmotraktmat = (string) filter_input(INPUT_GET, 'id');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            $model->tr_mat_osmotr_number = 1;
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDelete($id) {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    public function actionAssignToRecoverysendakt() {
        Proc::AssignToModelFromGrid(new \app\models\Fregat\Recoveryrecieveaktmat, 'id_recoverysendakt');
        $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
    }

    /**
     * Finds the TrMatOsmotr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TrMatOsmotr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TrMatOsmotr::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
