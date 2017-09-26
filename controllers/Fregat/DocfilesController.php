<?php

namespace app\controllers\Fregat;

use app\func\Proc;
use app\models\Fregat\MaterialDocfiles;
use app\models\Fregat\RraDocfiles;
use app\models\Fregat\RramatDocfiles;
use app\models\Fregat\UploadDocFile;
use Yii;
use app\models\Fregat\Docfiles;
use app\models\Fregat\DocfilesSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DocfilesController implements the CRUD actions for Docfiles model.
 */
class DocfilesController extends Controller
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
                        'actions' => ['index', 'selectinput', 'assign-to-select2', 'assign-to-grid', 'download-file'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['DocfilesEdit'],
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
     * Lists all Docfiles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocfilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new UploadDocFile;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Docfiles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $model = new UploadDocFile();
            $model->docFile = UploadedFile::getInstance($model, 'docFile');
            $UploadTrigger = $model->upload();
            if ($UploadTrigger['success']) {
                $Docfiles = new Docfiles;
                $Docfiles->docfiles_hash = $UploadTrigger['savedhashfilename_utf8'];
                $Docfiles->docfiles_name = $UploadTrigger['savedfilename'];
                $Docfiles->docfiles_ext = $UploadTrigger['fileextension'];
                if ($Docfiles->save())
                    echo json_encode(['ok']);
                else
                    throw new HttpException(500, Proc::ActiveRecordErrorsToString($Docfiles));
            } else
                throw new HttpException(500, Proc::ActiveRecordErrorsToString($UploadTrigger['errors']));
        } else
            throw new HttpException(500, 'Ошибка запроса');
    }

    /**
     * Deletes an existing Docfiles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $hash = Yii::$app->basePath . '/docs/' . $this->findModel($id)->docfiles_hash;
            $fileroot = (DIRECTORY_SEPARATOR === '/') ? $hash : mb_convert_encoding($hash, 'Windows-1251', 'UTF-8');

            if ($this->findModel($id)->delete())
                if (file_exists($fileroot))
                    unlink($fileroot);
        }
    }

    public function actionDownloadFile($id)
    {
        $Docfiles = $this->findModel($id);

        $hash = Yii::$app->basePath . '/docs/' . $Docfiles->docfiles_hash;
        $fileroot = (DIRECTORY_SEPARATOR === '/') ? $hash : mb_convert_encoding($hash, 'Windows-1251', 'UTF-8');

        return file_exists($fileroot) ? Yii::$app->response->sendFile($fileroot, $Docfiles->docfiles_name) : false;
    }

    public function actionAssignToGrid()
    {
        $LastBC = Proc::GetLastBreadcrumbsFromSession();
        if ($LastBC['dopparams']['foreign']['model'] === 'MaterialDocfiles')
            Proc::AssignToModelFromGrid(True, new MaterialDocfiles, 'id_material');
        elseif ($LastBC['dopparams']['foreign']['model'] === 'RraDocfiles')
            Proc::AssignToModelFromGrid(True, new RraDocfiles, 'id_recoveryrecieveakt');
        elseif ($LastBC['dopparams']['foreign']['model'] === 'RramatDocfiles')
            Proc::AssignToModelFromGrid(True, new RramatDocfiles, 'id_recoveryrecieveaktmat');
    }

    /**
     * Finds the Docfiles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Docfiles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Docfiles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
