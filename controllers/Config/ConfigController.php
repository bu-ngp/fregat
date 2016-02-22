<?php

namespace app\controllers\Config;

use Yii;
use yii\web\Controller;
use app\func\Proc;
use yii\filters\AccessControl;

class ConfigController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'test'],
                        'allow' => true,
                        'roles' => ['RoleEdit', 'UserEdit'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        Proc::SetMenuButtons('config');
        return $this->render('//Config/index');
    }

    public function actionTest() {
        $objReader = \PHPExcel_IOFactory::createReaderForFile("imp/os.xls");
        $objPHPExcel = $objReader->load("imp/os.xls");
        $objWorksheet = $objPHPExcel->getActiveSheet();
        echo $objWorksheet->getCellByColumnAndRow(7, 7)->getValue();
    }

}
