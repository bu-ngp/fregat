<?php

namespace app\controllers\Fregat;

use app\func\Proc;
use app\models\Fregat\Docfiles;
use app\models\Fregat\UploadDocFile;
use HttpException;
use Yii;
use app\models\Fregat\MaterialDocfiles;
use app\models\Fregat\MaterialDocfilesSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MaterialDocfilesController implements the CRUD actions for MaterialDocfiles model.
 */
class MaterialDocfilesController extends Controller
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
                        'actions' => ['create', 'delete', 'get-images'],
                        'allow' => true,
                        'roles' => ['MaterialEdit'],
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
                    $MaterialDocfiles = new MaterialDocfiles;
                    $MaterialDocfiles->id_docfiles = $Docfiles->primaryKey;
                    $MaterialDocfiles->id_material = $_POST['id_material'];
                    if ($MaterialDocfiles->save()) {
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['ok'];
                    } else
                        throw new HttpException(500, Proc::ActiveRecordErrorsToString($MaterialDocfiles));
                } else
                    throw new HttpException(500, Proc::ActiveRecordErrorsToString($Docfiles));
            } else
                throw new HttpException(500, 'Ошибка при загрузке файла');
        } else
            throw new HttpException(500, 'Ошибка запроса');
    }

    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $ar = $this->findModel($id);
            $id_docfiles = $ar->id_docfiles;
            if ($ar->delete())
                Proc::DeleteDocFile($id_docfiles);

        }
    }

    public function actionGetImages()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return MaterialDocfiles::getImagesList(Yii::$app->request->post('id_material'));
        }
    }

    protected function findModel($id)
    {
        if (($model = MaterialDocfiles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
