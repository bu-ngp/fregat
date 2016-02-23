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
       $ar = date("Y-m-d H:i:s", filemtime('imp/os.xls'));
       $ar = '2016-01-08 22:22:18';
               
       $row = \app\models\Fregat\Import\Matlog::find()
               ->select('MAX(matlog_filelastdate) as maxfilelastdate')
               ->asArray()
               ->one();

        echo '<pre class="xdebug-var-dump" style="max-height: 350px; font-size: 15px;">';
        print_r($row['maxfilelastdate']);
        echo '<br>';        
        print_r($ar);
        echo '<br>';  
        print_r(strtotime($ar) > strtotime($row['maxfilelastdate']) ? 'Грузим' : 'Старый файл' );
        echo '</pre>';
    }

}
