<?php

namespace app\controllers\Fregat;

use Yii;
use app\models\Fregat\Build;
use app\models\Fregat\BuildSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use app\func\Proc;
use yii\filters\AccessControl;
use app\func\FregatImport;
use app\func\TestMem;

class FregatController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'sprav'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['config', 'import'],
                        'allow' => true,
                        'roles' => ['FregatImport'],
                    ],
                    [
                        'actions' => ['import-do', 'import-employee-do', 'test'],
                        'allow' => true,
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
        return $this->render('//Fregat/index');
    }

    public function actionConfig() {
        return $this->render('//Fregat/config/index');
    }

    public function actionImport() {
        return $this->render('//Fregat/config/import');
    }

    public function actionSprav() {
        return $this->render('//Fregat/config/sprav');
    }

    public function actionImportDo() {
        FregatImport::ImportDo();
    }

    public function actionImportEmployeeDo() {
        FregatImport::ImportEmployee();
    }

    public function actionTest() {
        
        
        
        $rows = \app\models\Fregat\Import\Traflog::find()
                ->select(['traflog_filename', 'traflog_rownum', 'traflog_message', 'mattraffic_number', 'material_name1c', 'material_1c', 'material_inv', 'material_number', 'employee_fio', 'dolzh_name', 'podraz_name', 'build_name'])
                ->joinWith(['idmatlog', 'idemployeelog'])
                ->where(['traflog.id_logreport' => 1])
                ->createCommand()
                ->queryAll();
            /*    ->asArray()
                ->all();*/
        
        var_dump($rows);


        /*
          $Importconfig = \app\models\Fregat\Import\Importconfig::findOne(1);

          foreach ([$Importconfig['emp_filename'] . '.txt', $Importconfig['os_filename'] . '.xlsx', $Importconfig['mat_filename'] . '.xlsx'] as $filename) {
          $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/imp/' . $filename;

          file_exists($filename) ? var_dump($filename.': File Exist') : var_dump($filename. ': File Not Exist');
          } */
    }

}
