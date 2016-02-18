<?php

namespace app\controllers\Config;

use Yii;
use app\models\Fregat\Build;
use app\models\Fregat\BuildSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use app\func\Proc;

class AuthmanagerController extends Controller {

    public function actionIndex() {
     //   Proc::SetMenuButtons('config');
        return $this->render('//Config/authmanager/index');
    }

}
