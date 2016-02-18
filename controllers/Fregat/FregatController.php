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

class FregatController extends Controller {

    public function behaviors() {
        return [
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

}
