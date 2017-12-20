<?php

namespace app\controllers\Fregat;

use app\func\ImportData\ImportEmployees;
use app\func\PopulateData;
use app\func\ReportsTemplate\InstallaktReport;
use app\models\Base\Patient;
use app\models\Config\Authuser;
use app\models\Config\Profile;
use app\models\Fregat\Docfiles;
use app\models\Fregat\Employee;
use app\models\Fregat\Fregatsettings;
use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Installakt;
use app\models\Fregat\Material;
use app\models\Fregat\MaterialDocfiles;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Naklad;
use app\models\Fregat\Nakladmaterials;
use app\models\Fregat\Osmotraktmat;
use app\models\Fregat\Reason;
use app\models\Fregat\RraDocfiles;
use app\models\Fregat\Schetuchet;
use app\models\Fregat\Spisosnovakt;
use app\models\Fregat\Spisosnovmaterials;
use app\models\Fregat\SpisosnovmaterialsSearch;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrMatOsmotr;
use app\models\Fregat\TrOsnov;
use app\models\User;
use Exception;
use PDO;
use Yii;
use app\models\Fregat\Build;
use app\models\Fregat\BuildSearch;
use yii\base\Request;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
use yii\test\Fixture;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use app\func\Proc;
use yii\filters\AccessControl;
use app\func\FregatImport;
use app\func\TestMem;
use app\models\Base\Fias;
use ZipArchive;

class FregatController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['sprav', 'config'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission', 'GlaukUserPermission'],
                    ],
                    [
                        'actions' => ['mainmenu'],
                        'allow' => true,
                        'roles' => ['FregatUserPermission'],
                    ],
                    [
                        'actions' => ['import'],
                        'allow' => true,
                        'roles' => ['FregatImport'],
                    ],
                    [
                        'actions' => ['settings', 'options'],
                        'allow' => true,
                        'roles' => ['FregatConfig'],
                    ],
                    [
                        'actions' => [
                            'import-do',
                            'test',
                            'genpass',
                            'uppercaseemployee',
                            'removeinactiveemployee',
                            'import-glauk',
                            'import-remont',
                            'update-profiles',
                            'import-do2',
                            'populate-data',
                            'rename-prog',
                            'generate-authkeys',
                        ],
                        'allow' => true,
                        'ips' => ['172.19.17.30', '127.0.0.1', 'localhost', '::1', '172.19.17.81', '172.19.17.253'],
                    ],
                    [
                        'actions' => ['resetadmin', 'mat-files'],
                        'allow' => true,
                        'ips' => ['172.19.17.30', '127.0.0.1', 'localhost', '::1', '172.19.17.253', '172.19.17.81', '172.19.18.88'],
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

    public function actionMainmenu()
    {
        Proc::SetMenuButtons('fregat');
        return $this->render('//Fregat/mainmenu/index');
    }

    public function actionConfig()
    {
        return $this->render('//Fregat/config/index');
    }

    public function actionSettings()
    {
        $model = Fregatsettings::findOne(1);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            return $this->render('//Fregat/config/settingsupdate', [
                'model' => $model,
            ]);
        }
    }

    public function actionImport()
    {
        return $this->render('//Fregat/config/import');
    }

    public function actionSprav()
    {
        return $this->render('//Fregat/config/sprav');
    }

    public function actionOptions()
    {
        $model = Fregatsettings::findOne(1);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Proc::GetPreviousURLBreadcrumbsFromSession());
        } else {
            return $this->render('//Fregat/config/update_fregat', [
                'model' => $model,
            ]);
        }
    }

    public function actionImportDo()
    {
        FregatImport::ImportDo();
    }

    public function actionFias()
    {
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

    public function actionGenpass()
    {
        $Importconfig = \app\models\Fregat\Import\Importconfig::findOne(1);
        ini_set('max_execution_time', $Importconfig['max_execution_time']);  // 1000 seconds
        ini_set('memory_limit', $Importconfig['memory_limit']); // 1Gbyte Max Memory
        $users = \app\models\Config\Authuser::find()->where('auth_user_id <> 1')->all();
        foreach ($users as $ar) {
            $ar->auth_user_password = Yii::$app->getSecurity()->generatePasswordHash('11111111');
            $ar->save();
        }
        echo 'готово: ' . count((array)$users);
    }

    public function actionUppercaseemployee()
    {
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

    public function actionRemoveinactiveemployee()
    {
        $au = \app\models\Fregat\Employee::find()
            ->select(['employee_id', 'id_person'])
            ->groupBy(['id_person'])
            ->all();
        $del = 0;
        $nodel = 0;
        $count = count((array)$au);
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
                    $au2 = \app\models\Config\Authuser::findOne($ar->id_person)->auth_user_fullname;
                    \app\models\Fregat\Employee::deleteAll(['id_person' => $ar->id_person]);
                    \app\models\Config\Authuser::deleteAll(['auth_user_id' => $ar->id_person]);
                    Profile::deleteAll(['profile_id' => $ar->id_person]);
                    $transaction->commit();
                    $del++;
                    echo 'Deleted "' . $au2 . '"<br>';
                } catch (\yii\db\IntegrityException $e) {
                    $nodel++;
                    echo 'Can\'t delete "' . $au2 . '"<br>';
                    $transaction->rollBack();
                }
            }
        }
        echo 'Removed ' . $del . ' from ' . $count . '. Errors = ' . $nodel;
    }

    public function actionImportGlauk()
    {
        header('Content-Type: text/html');
        $Glauk = Authuser::find()
            ->andWhere(['in', 'auth_user_id', [64, 403, 410, 419, 875, 877, 882, 885, 887, 891, 1133, 1196, 1211, 1196]])
            ->all();

        foreach ($Glauk as $ar) {
            $ar2 = Authuser::findOne($ar->primaryKey);
            $ar2->scenario = 'Changepassword';
            $ar2->auth_user_password = '55555555';
            $ar2->auth_user_password2 = '55555555';
            $ar2->save();
            $auth = Yii::$app->authManager;
            $Role = $auth->getRole('GlaukOperatorRole');
            $auth->assign($Role, $ar->primaryKey);
        }

        //murin
        $ar2 = Authuser::findOne(883);
        $ar2->scenario = 'Changepassword';
        $ar2->auth_user_password = '55555555';
        $ar2->auth_user_password2 = '55555555';
        $ar2->save();
        $auth = Yii::$app->authManager;
        $Role = $auth->getRole('GlaukAdmin');
        $auth->assign($Role, 883);
        $Role = $auth->getRole('EmployeeSpecEditRole');
        $auth->assign($Role, 883);

        $pol1 = Employee::updateAll(['id_build' => 1], ['employee_id' => [63, 882, 886, 890, 1132, 1184, 1214, 1181]]);
        $pol3 = Employee::updateAll(['id_build' => 2], ['employee_id' => [874, 884]]);
        $pol3 = Employee::updateAll(['id_build' => 3], ['employee_id' => [876, 881]]);

        echo 'ok';
    }

    public function actionUpdateProfiles()
    {
        $Importconfig = Importconfig::findOne(1);
        $filename = 'imp/' . $Importconfig['emp_filename'] . '.txt';
        if (file_exists($filename)) {
            ini_set('max_execution_time', $Importconfig['max_execution_time']);  // 1000 seconds
            ini_set('memory_limit', $Importconfig['memory_limit']); // 1Gbyte Max Memory

            $i = 0;
            $j = 0;
            $handle = @fopen($filename, "r");

            if ($handle) {
                $UTF8deleteBOM = true;
                while (($subject = fgets($handle, 4096)) !== false) {
                    if ($UTF8deleteBOM) {
                        $subject = str_replace("\xEF\xBB\xBF", '', $subject);
                        $UTF8deleteBOM = false;
                    }

                    $pattern = '/^(.*?)\|(Поликлиника №\s?[1,2,3] )?(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|/ui';
                    preg_match($pattern, $subject, $matches);

                    $employee_fio = $matches[1];

                    $AuthuserCount = Authuser::find()
                        ->where(['like', 'auth_user_fullname', $employee_fio, false])
                        ->count();

                    $Authuser = $AuthuserCount == 1 ? Authuser::find()
                        ->where(['like', 'auth_user_fullname', $employee_fio, false])
                        ->one() : false;

                    if (!empty($Authuser)) {
                        $Profile = Profile::findOne($Authuser->primaryKey);
                        $Profile = empty($Profile) ? new Profile : $Profile;
                        $Profile->profile_id = $Authuser->primaryKey;
                        $Profile->profile_dr = $matches[16];
                        $Profile->profile_pol = $matches[15];
                        $Profile->profile_inn = $matches[11];
                        $Profile->profile_snils = $matches[12];
                        $Profile->profile_address = $matches[10];
                        $Profile->save();
                        if ($Profile->getErrors())
                            var_dump($Profile->getErrors());
                        else
                            $i++;
                    }
                    $j++;
                }
                fclose($handle);
                echo 'Профилей создано ' . $i . ' из ' . $j;
            }
        } else
            echo 'Файл не существует ' . $filename;
    }

    public function actionResetadmin()
    {
        $auth = Yii::$app->authManager;

        $auth->revokeAll(1);
        $ar = Authuser::findOne(1);
        if ($ar) {
            $ar->scenario = 'Changepassword';
            $ar->auth_user_login = 'admin';
            $ar->auth_user_fullname = 'Администратор';
            $ar->auth_user_password = 'admin';
            $ar->auth_user_password2 = 'admin';
            if ($ar->save()) {
                $item = $auth->getRole('Administrator');
                if ($item) {
                    $auth->assign($item, 1);
                    echo 'The Administrator Reset<br>';
                } else
                    echo 'Role "Administrator" is missing<br>';
            } else {
                echo 'An error occurred while resetting the password and login of administrator:<br>';
                var_dump($ar->errors);
            }
        } else
            echo 'User ID = 1 Not Found<br>';
    }

    public function actionImportDo2()
    {
        ImportEmployees::init()->execute();
    }

    public function actionTest()
    {
        $fileName = 'Акты установки для ведомости №2.zip';
        $filesArray = [
            'Акт установки матер-ых цен-тей №2.xlsx',
            'Акт установки матер-ых цен-тей №3.xlsx',
            'Акт установки матер-ых цен-тей №4.xlsx',
            'Акт установки матер-ых цен-тей №5.xlsx',
        ];

        $zip = new ZipArchive();
        $zip->open(Yii::$app->basePath . '/web/files/' . $fileName);
        $filesFromZip = [];

        var_dump($zip->getNameIndex(0));
        var_dump(mb_convert_encoding($zip->getNameIndex(0), 'UTF-8', 'ASCII'));
        //var_dump(mb_detect_encoding($zip->getNameIndex(0), mb_detect_order(), true));
        var_dump(mb_detect_order());
        /*   for ($i = 0; $i < $zip->numFiles; $i++) {
               $filesFromZip[] = mb_convert_encoding($zip->getNameIndex($i), 'UTF-8', 'CP866');
           }

           $result = array_diff($filesArray, $filesFromZip);

           var_dump($result);
           var_dump($filesArray);
           var_dump($filesFromZip);*/

        $zip->close();
    }

    public function actionPopulateData()
    {
        $Importconfig = Importconfig::findOne(1);

        ini_set('max_execution_time', $Importconfig['max_execution_time']);  // 1000 seconds
        ini_set('memory_limit', $Importconfig['memory_limit']); // 1Gbyte Max Memory
        PopulateData::init()->installAkt(rand(349, 364));
        PopulateData::init()->osmotrAktMat(rand(101, 109));
        PopulateData::init()->spisOsnovAkt(rand(127, 134));
        PopulateData::init()->naklad(rand(151, 169));
        PopulateData::init()->removeAkt(rand(51, 69));
        PopulateData::init()->osmotrakt(rand(101, 199));
        PopulateData::init()->glauk(rand(10074, 10124));
    }

    public function actionRenameProg()
    {
        Authuser::updateAll(['auth_user_fullname' => 'НАУМОВ АЛЕКСЕЙ ОЛЕГОВИЧ'], ['auth_user_id' => 984]);
        Authuser::updateAll(['auth_user_fullname' => 'МЯГКОВ АНАТОЛИЙ НИКОЛАЕВИЧ]'], ['auth_user_id' => 985]);
        Authuser::updateAll(['auth_user_fullname' => 'КИЛИМНИК АНДРЕЙ ПЕТРОВИЧ'], ['auth_user_id' => 986]);
        Authuser::updateAll(['auth_user_fullname' => 'БРЫКИН ДМИТРИЙ ВИКТОРОВИЧ'], ['auth_user_id' => 987]);
        Authuser::updateAll(['auth_user_fullname' => 'БАЙНАКОВ БУЛАТ МУРАЛЕЕВИЧ'], ['auth_user_id' => 988]);
        Authuser::updateAll(['auth_user_fullname' => 'СТЕЦЮК АНДРЕЙ ЕВГЕНЬЕВИЧ'], ['auth_user_id' => 989]);
        Authuser::updateAll(['auth_user_fullname' => 'СУВОРОВ ДМИТРИЙ АЛЕКСАНДРОВИЧ'], ['auth_user_id' => 1020]);
    }

    public function actionMatFiles($id_docfiles, $material_name)
    {
        $count = Material::find()->andWhere(['like', 'material_name', $material_name])->count();
        $Docfiles = Docfiles::findOne($id_docfiles);
        if (empty($Docfiles))
            echo 'Файл и ИД ' . $id_docfiles . ' не найден.';
        else {
            if ($count > 0 && $count <= 200) {
                $query = Material::find()->andWhere(['like', 'material_name', $material_name])->all();
                $i = 0;
                $suc = 0;
                $err = 0;
                foreach ($query as $ar) {
                    /** @var $ar Material */
                    $MaterialDocfiles = new MaterialDocfiles;
                    $MaterialDocfiles->id_docfiles = $id_docfiles;
                    $MaterialDocfiles->id_material = $ar->primaryKey;
                    if ($MaterialDocfiles->save())
                        $suc++;
                    else {
                        $err++;
                        echo 'Ошибка при сохранении файла материальной ценности.<BR>';
                        echo 'Материальная ценность ' . $ar->material_inv . ', ' . $ar->material_name . '<BR>';
                        echo 'Ошибки:<BR>';
                        print_r($MaterialDocfiles->errors);
                        echo '<BR>';
                    }
                    $i++;
                }

                echo '<BR> Из ' . $count . ' записей. Успешно добавлены ' . $suc . ' файлов. Ошибок: ' . $err . '.';
            } elseif ($count == 0) {
                echo 'Найдено ' . $count . ' записей по условию "' . $material_name . '"';
            } else {
                echo 'Установлено ограничение в 200 материальных ценностей. По данному наименованию найдено ' . $count . '.';
            }
        }
    }

    public function actionGenerateAuthkeys()
    {
        /** @var User $auth_user */
        foreach (User::find()->all() as $auth_user) {
            $auth_user->generateAuthKey();
            $auth_user->save();
        }

        echo 'finish';
    }
}
                                        