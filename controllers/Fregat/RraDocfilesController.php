<?php

namespace app\controllers\Fregat;

use app\models\Fregat\Docfiles;
use app\models\Fregat\UploadDocFile;
use Yii;
use app\models\Fregat\RraDocfiles;
use app\models\FregatRraDocfilesSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RraDocfilesController implements the CRUD actions for RraDocfiles model.
 */
class RraDocfilesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all RraDocfiles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FregatRraDocfilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new RraDocfiles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $UploadFile = new UploadDocFile();
            $UploadFile->docFile = UploadedFile::getInstance($UploadFile, 'docFile');
            $UploadTrigger = $UploadFile->upload();
            if ($UploadTrigger['success']) {
                $Docfiles = new Docfiles;
                $Docfiles->docfiles_hash = $UploadTrigger['savedhashfilename_utf8'];
                $Docfiles->docfiles_name = $UploadTrigger['savedfilename'];
                $Docfiles->docfiles_ext = $UploadTrigger['fileextension'];
                if ($Docfiles->save()) {

                    $RraDocfiles = new RraDocfiles;
                    $RraDocfiles->id_docfiles = $Docfiles->primaryKey;
                    $RraDocfiles->id_recoveryrecieveakt = $_POST['id_recoveryrecieveakt'];
                    if ($RraDocfiles->save())
                        echo json_encode(['ok']);
                    else {
                        $strerr = '';
                        foreach ($RraDocfiles->getErrors() as $attr)
                            foreach ($attr as $errmsg)
                                $strerr .= $errmsg . ', ';
                        if (!empty($strerr))
                            $strerr = mb_substr($strerr, 0, mb_strlen($strerr, 'UTF-8') - 2, 'UTF-8');

                        throw new HttpException(500, $strerr);
                    }
                } else {
                    $strerr = '';
                    foreach ($Docfiles->getErrors() as $attr)
                        foreach ($attr as $errmsg)
                            $strerr .= $errmsg . ', ';
                    if (!empty($strerr))
                        $strerr = mb_substr($strerr, 0, mb_strlen($strerr, 'UTF-8') - 2, 'UTF-8');

                    throw new HttpException(500, $strerr);
                }


            } else
                throw new HttpException(500, 'Ошибка при загрузке файла');
        } else
            throw new HttpException(500, 'Ошибка запроса');

    }

    /**
     * Updates an existing RraDocfiles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->rra_docfiles_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RraDocfiles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax)
            echo $this->findModel($id)->delete();
    }

    /**
     * Finds the RraDocfiles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RraDocfiles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RraDocfiles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
