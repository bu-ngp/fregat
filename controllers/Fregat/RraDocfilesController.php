<?php

namespace app\controllers\Fregat;

use app\func\Proc;
use app\models\Fregat\Docfiles;
use app\models\Fregat\UploadDocFile;
use Yii;
use app\models\Fregat\RraDocfiles;
use app\models\RraDocfilesSearch;
use yii\filters\AccessControl;
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => ['RecoveryEdit'],
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
                    if ($RraDocfiles->save()) {
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['ok'];
                    } else
                        throw new HttpException(500, Proc::ActiveRecordErrorsToString($RraDocfiles));
                } else
                    throw new HttpException(500, Proc::ActiveRecordErrorsToString($Docfiles));
            } else
                throw new HttpException(500, 'Ошибка при загрузке файла');
        } else
            throw new HttpException(500, 'Ошибка запроса');
    }


    /**
     * Deletes an existing RraDocfiles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $ar = $this->findModel($id);
            $id_docfiles = $ar->id_docfiles;
            if ($ar->delete())
                Proc::DeleteDocFile($id_docfiles);
        }
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
