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
use app\models\Base\Fias;

class FregatController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['sprav'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission', 'GlaukUserPermission'],
                    ],
                    [
                        'actions' => ['config', 'import'],
                        'allow' => true,
                        'roles' => ['FregatImport'],
                    ],
                    [
                        'actions' => ['import-do', 'test', 'genpass', 'uppercaseemployee', 'removeinactiveemployee'],
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

    public function actionFias() {
        $file = 'AS_ADDROBJ_20160609_c5080ba4-9f46-4b6e-aecc-72a630730b3a.XML';
        $interestingNodes = array('AOGUID');
        $xmlObject = new \XMLReader();
        $xmlObject->open($file);
        header('Content-Type: text/html; charset=utf-8');

        $i = 0;
        while ($xmlObject->read() /* && $i <= 50 */) {
            if ($xmlObject->name == 'Object') {
                if ($xmlObject->getAttribute('IFNSFL') == '8603') {
                    // if (($xmlObject->getAttribute('PARENTGUID') == '0bf0f4ed-13f8-446e-82f6-325498808076' && $xmlObject->getAttribute('AOLEVEL') == '7') || $xmlObject->getAttribute('AOGUID') == '0bf0f4ed-13f8-446e-82f6-325498808076') {
                    $fias = new Fias;
                    $fias->AOGUID = $xmlObject->getAttribute('AOGUID');
                    $fias->OFFNAME = $xmlObject->getAttribute('OFFNAME');
                    $fias->SHORTNAME = $xmlObject->getAttribute('SHORTNAME');
                    $fias->IFNSFL = $xmlObject->getAttribute('IFNSFL');
                    $fias->AOLEVEL = $xmlObject->getAttribute('AOLEVEL');
                    $fias->PARENTGUID = $xmlObject->getAttribute('PARENTGUID');
                    if ($fias->validate())
                        $fias->save();
                    else {
                        var_dump($fias->attributes);
                        var_dump($fias->getErrors());
                    }
                    //    $i++;
                }
            }
        }
        ECHO 'ok';
        $xmlObject->close();
    }

    public function actionGenpass() {
        $users = \app\models\Config\Authuser::find()->where('auth_user_id <> 1')->all();
        foreach ($users as $ar) {
            $ar->auth_user_password = Yii::$app->getSecurity()->generatePasswordHash('11111111');
            $ar->save();
        }
        echo 'готово: ' . (array) count($users);
    }

    public function actionUppercaseemployee() {
        foreach (\app\models\Config\Authuser::find()->all() as $AR)
            $AR->save();
        foreach (\app\models\Fregat\Dolzh::find()->all() as $AR)
            $AR->save();
        foreach (\app\models\Fregat\Podraz::find()->all() as $AR)
            $AR->save();
        foreach (Build::find()->all() as $AR)
            $AR->save();

        echo 'OK_';
    }

    public function actionRemoveinactiveemployee() {
        $au = \app\models\Fregat\Employee::find()
                ->select(['employee_id', 'id_person'])
                ->groupBy(['id_person'])
                ->all();
        foreach ($au as $ar) {
            $inactivePerson = \app\models\Fregat\Employee::find()
                    ->andWhere([
                        'id_person' => $ar->id_person,
                        'employee_dateinactive' => NULL,
                    ])
                    ->count();

            if (empty($inactivePerson)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    \app\models\Fregat\Employee::deleteAll(['id_person' => $ar->id_person]);
                    $au = \app\models\Config\Authuser::findOne($ar->id_person)->auth_user_fullname;
                    \app\models\Config\Authuser::deleteAll(['auth_user_id' => $ar->id_person]);
                    $transaction->commit();
                } catch (Exception $e) {
                    echo 'Can\'t delete "' . $au . '"';
                    $transaction->rollback();
                }
            }
        }
    }

    public function actionTest() {
        foreach (\app\models\Config\Authuser::find()->all() as $AR)
            $AR->save();
        foreach (\app\models\Fregat\Dolzh::find()->all() as $AR)
            $AR->save();
        foreach (\app\models\Fregat\Podraz::find()->all() as $AR)
            $AR->save();
        foreach (Build::find()->all() as $AR)
            $AR->save();

        echo 'OK_';
    }

}
