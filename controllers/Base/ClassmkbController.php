<?php

namespace app\controllers\Base;

use Yii;
use app\models\Base\Classmkb;
use app\models\Base\ClassmkbSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\func\Proc;
use yii\filters\AccessControl;

/**
 * ClassmkbController implements the CRUD actions for Classmkb model.
 */
class ClassmkbController extends Controller
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
                        'actions' => ['index', 'indexglauk', 'selectinputfordiag', 'assign-to-select2'],
                        'allow' => true,
                        'roles' => ['GlaukUserPermission'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ClassmkbSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexglauk()
    {
        $searchModel = new ClassmkbSearch();
        $dataProvider = $searchModel->searchglauk(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // Действие наполнения списка Select2 при помощи ajax
    public function actionSelectinputfordiag($q = null)
    {
        if (Yii::$app->request->isAjax)
            return Proc::ResultSelect2([
                'model' => new Classmkb,
                'q' => $q,
                'methodquery' => 'selectinput',
            ]);
    }

    public function actionAssignToSelect2()
    {
        Proc::AssignToModelFromGrid();
    }

    /**
     * Finds the Classmkb model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Classmkb the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Classmkb::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
