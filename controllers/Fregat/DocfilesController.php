<?php

namespace app\controllers\Fregat;

use app\func\Proc;
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
                        'actions' => ['index', 'selectinput', 'assign-to-select2'],
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
                else {
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
     * Deletes an existing Docfiles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            $hash = Yii::$app->basePath . '/docs/' . $this->findModel($id)->docfiles_hash;
            //$hash = str_replace(" ", "\ ", $hash);
            $fileroot = (DIRECTORY_SEPARATOR === '/') ? $hash : mb_convert_encoding($hash, 'Windows-1251', 'UTF-8');

            if ($this->findModel($id)->delete())
                if (file_exists($fileroot))
                    unlink($fileroot);
        }
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
