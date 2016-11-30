<?php

namespace app\controllers\Fregat;

use app\func\ImportData\ImportEmployees;
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
use Exception;
use PDO;
use Yii;
use app\models\Fregat\Build;
use app\models\Fregat\BuildSearch;
use yii\base\Request;
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
                            'installakt-fill',
                            'osmotraktmat-fill',
                            'spisosnovakt-fill',
                            'naklad-fill',
                            'glauk-fill',
                        ],
                        'allow' => true,
                        'ips' => ['172.19.17.30', '127.0.0.1', 'localhost', '::1', '172.19.17.81', '172.19.17.253'],
                    ],
                    [
                        'actions' => ['resetadmin'],
                        'allow' => true,
                        'ips' => ['172.19.17.30', '127.0.0.1', 'localhost', '::1', '172.19.17.253'],
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

    public function actionImportRemont()
    {
        header('Content-Type: text/html');
        try {
            $conn = new \PDO('mysql:host=127.0.0.1;dbname=remont;charset=UTF8', 'root', '265463');
            $sql = 'SELECT akt.akt_id,
	pol.pol_name,
	obor.obor_name,
	akt.akt_inv,
	akttext.akttext_name,
	aktuser.aktuser_name,
	prog.aktuser_name AS prog_name,
	akt.akt_kab,
	akt.akt_date,
	akt.akt_serial,
	akt.akt_closedate,
	akt.akt_datesend
FROM akt
INNER JOIN akttext ON akt.id_akttext = akttext.akttext_id
INNER JOIN obor ON akt.id_obor = obor.obor_id
INNER JOIN pol ON akt.id_pol = pol.pol_id
INNER JOIN aktuser ON akt.id_aktuser = aktuser.aktuser_id
INNER JOIN aktuser prog ON akt.id_prog = prog.aktuser_id';

            $fail = 0;
            $succ = 0;

            // Prepare

            /*     $Employee = \app\models\Fregat\Employee::find()
              ->joinWith([
              'idperson' => function($query) {
              $query->from(['idperson' => 'auth_user']);
              }
              ])
              ->andWhere(['like', 'idperson.auth_user_fullname', 'ЧЕПЕНКО АЛЕКСЕЙ ВЛАДИМИРОВИЧ'])
              ->one();
              $Employee->id_build = 2;
              $Employee->save(); */

            $Employee = \app\models\Fregat\Employee::find()
                ->joinWith(['idperson'])
                ->andWhere(['like', 'idperson.auth_user_fullname', 'БАЙТИНГЕР АНАСТАСИЯ ВЛАДИМИРОВНА'])
                ->all();

            foreach ($Employee as $ar)
                \app\models\Fregat\Employee::updateAll(['id_build' => 1], ['employee_id' => $ar->employee_id]);

            $Organ = new \app\models\Fregat\Organ;
            $Organ->organ_name = 'ООО "Северная линия"';
            $Organ->save();
            $Organ = new \app\models\Fregat\Organ;
            $Organ->organ_name = 'ООО «Копи-Мастер»';
            $Organ->organ_email = '310209@mail.ru';
            $Organ->organ_phones = '8(3466)31-02-09';
            $Organ->save();

            $Reason = new Reason;
            $Reason->reason_text = 'Требуется замена термопленки';
            $Reason->save();
            $Reason = new Reason;
            $Reason->reason_text = 'Требуется замена аккумулятора';
            $Reason->save();
            $Reason = new Reason;
            $Reason->reason_text = 'Требуется замена резинового вала';
            $Reason->save();
            $Reason = new Reason;
            $Reason->reason_text = 'Требуется замена фотобарабана';
            $Reason->save();
            $Reason = new Reason;
            $Reason->reason_text = 'Требуется ремонт печки';
            $Reason->save();
            $Reason = new Reason;
            $Reason->reason_text = 'Неисправен податчик бумаги';
            $Reason->save();
            $Reason = new Reason;
            $Reason->reason_text = 'Требуется замена инвертора подсветки матрицы';
            $Reason->save();
            $Reason = new Reason;
            $Reason->reason_text = 'Выводит на печать чистые листы';
            $Reason->save();
            $Reason = new Reason;
            $Reason->reason_text = 'Не определяет наличие бумаги в лотке';
            $Reason->save();
            $Reason = new Reason;
            $Reason->reason_text = 'Требуется заправка';
            $Reason->save();

            $Authuser = Authuser::findOne(1); //admin
            $Authuser->scenario = 'Changepassword';
            $Authuser->auth_user_password = '265463';
            $Authuser->auth_user_password2 = '265463';
            $Authuser->save();

            $Authuser = Authuser::find()
                ->andWhere(['in', 'auth_user_id', [989, 986, 987, 984, 988, 985, 1020]])// karpovvv, NicenkoDN, StalmahovichMN, VelikanovAE, ChepenkoAV, GorbatovskiyDV, ZamaletdinovDK
                ->all();

            foreach ($Authuser as $ar) {
                $ar2 = Authuser::findOne($ar->primaryKey);
                $ar2->scenario = 'Changepassword';
                $ar2->auth_user_password = '265463';
                $ar2->auth_user_password2 = '265463';
                $ar2->save();
                $auth = Yii::$app->authManager;
                if (!$auth->checkAccess($ar->primaryKey, 'Administrator')) {
                    $Role = $auth->getRole('Administrator');
                    $auth->assign($Role, $ar->primaryKey);
                }
            }

            $Hos = Authuser::find()
                ->andWhere(['in', 'auth_user_id', [384, 489, 590, 614, 744, 466, 755, 614]])
                ->all();

            foreach ($Hos as $ar) {
                $ar2 = Authuser::findOne($ar->primaryKey);
                $ar2->scenario = 'Changepassword';
                $ar2->auth_user_password = '44444444';
                $ar2->auth_user_password2 = '44444444';
                $ar2->save();
                $auth = Yii::$app->authManager;
                if (!$auth->checkAccess($ar->primaryKey, 'FregatHozSister')) {
                    $Role = $auth->getRole('FregatHozSister');
                    $auth->assign($Role, $ar->primaryKey);
                }
            }

            $querybuild = Build::find()->andWhere(['like', 'build_name', 'Административный корпус'])->one();
            if (empty($querybuild)) {
                $Build = new Build;
                $Build->build_name = 'Административный корпус';
                $Build->save();
            }
            // Prepare end

            foreach ($conn->query($sql, \PDO::FETCH_ASSOC) as $row) {
                //  var_dump($row);
                $mes = '<BR>' . 'akt №' . $row['akt_id'] . ' от ' . Yii::$app->formatter->asDate($row['akt_date']) . ' (Дата закрытия: ' . Yii::$app->formatter->asDate($row['akt_closedate']) . ')';
                $ok = false;

                $fail++;

                $mattraffic = \app\models\Fregat\Mattraffic::find()
                    ->joinWith(['idMaterial'])
                    ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                    ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                    ->andWhere(['m2.mattraffic_date_m2' => NULL])
                    ->andWhere(['like', 'idMaterial.material_inv', $row['akt_inv'], false])
                    ->all();

                if (count($mattraffic) >= 2)
                    $mattraffic = \app\models\Fregat\Mattraffic::find()
                        ->joinWith(['idMaterial'])
                        ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                        ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                        ->andWhere(['m2.mattraffic_date_m2' => NULL])
                        ->andWhere(['idMaterial.material_tip' => 1])
                        ->andWhere(['like', 'idMaterial.material_inv', $row['akt_inv'], false])
                        ->all();

                //  var_dump(count($mattraffic));
                if (count($mattraffic) == 1) {
                    $mes .= '<BR>' . $mattraffic[0]->idMaterial->material_name . ' inv=' . $mattraffic[0]->idMaterial->material_inv;

                    if ($mattraffic[0]->idMol->idbuild->build_name !== mb_strtoupper($row['pol_name'], 'UTF-8')) {
                        $mes .= '<BR>' . 'Build Problem: ' . $mattraffic[0]->idMol->idbuild->build_name . ' <> ' . mb_strtoupper($row['pol_name'], 'UTF-8');

                        /*   $Employee = \app\models\Fregat\Employee::find($mattraffic[0]->id_mol)->one();
                          $Employee_new = new \app\models\Fregat\Employee;
                          $Employee_new->attributes = $Employee->attributes;
                          $Employee_new->id_build = Build::find()->andWhere(['like', 'build_name', $row['pol_name']])->one()->build_id;
                          if (!$Employee_new->save())
                          $mes.='<BR>' . print_r($Employee_new->errors, true); */
                    } else {

                        $installer = \app\models\Fregat\Employee::find()
                            ->joinWith(['idperson', 'iddolzh', 'idpodraz', 'idbuild',])
                            ->andWhere(['like', 'idperson.auth_user_fullname', $row['prog_name']])
                            ->all();

                        $mes .= '<BR>' . 'instcount = ' . count($installer);
                        if (count($installer) == 0) {
                            $mes .= '<BR>' . $row['prog_name'] . ' not found';
                        } else if (count($installer) > 1) {
                            foreach ($installer as $ar) {
                                $mes .= '<BR>' . $ar->idperson->auth_user_fullname . ' dolzh=' . $ar->iddolzh->dolzh_name;
                            }
                        } else if (count($installer) == 1) {
                            $mes .= '<BR>' . $installer[0]->idperson->auth_user_fullname . ' dolzh=' . $installer[0]->iddolzh->dolzh_name;

                            $transaction = Yii::$app->db->beginTransaction();
                            try {
                                $installakt = new \app\models\Fregat\Installakt;
                                $installakt->installakt_date = substr($row['akt_date'], 0, 10);
                                $installakt->id_installer = $installer[0]->primaryKey;
                                if (!$installakt->save()) {
                                    $mes .= '<BR>' . print_r($installakt->errors, true);
                                    $transaction->rollBack();
                                } else {
                                    $Mattraffic_tr = new \app\models\Fregat\Mattraffic;
                                    $Mattraffic_tr->attributes = $mattraffic[0]->attributes;

                                    $Mattraffic_tr->mattraffic_date = date('Y-m-d');
                                    $Mattraffic_tr->mattraffic_number = 1;
                                    $Mattraffic_tr->mattraffic_tip = 3;

                                    if ($Mattraffic_tr->validate()) {
                                        $Mattraffic_tr->save(false);
                                        $trosnov = new \app\models\Fregat\TrOsnov;
                                        $trosnov->id_installakt = $installakt->primaryKey;
                                        $trosnov->id_mattraffic = $Mattraffic_tr->primaryKey;
                                        $trosnov->tr_osnov_kab = $row['akt_kab'];
                                        if (!$trosnov->save()) {
                                            $mes .= '<BR>' . print_r($trosnov->errors);
                                            $transaction->rollBack();
                                        } else {
                                            $mes .= '<BR>' . 'aktinstall saved';


                                            $user = \app\models\Fregat\Employee::find()
                                                ->joinWith(['idperson', 'iddolzh', 'idpodraz', 'idbuild',])
                                                ->andWhere(['like', 'idperson.auth_user_fullname', $row['aktuser_name']])
                                                // ->andWhere(['is not', 'idbuild.build_name', null])
                                                ->all();
                                            $mes .= '<BR>' . 'usercount = ' . count($user);
                                            if (count($user) == 0) {
                                                $mes .= '<BR>' . $row['aktuser_name'] . ' not found';
                                                $transaction->rollBack();
                                            } else if (count($user) >= 1) {
                                                if (count($user) > 1)
                                                    foreach ($user as $ar) {
                                                        $mes .= '<BR>' . $ar->idperson->auth_user_fullname . ' dolzh=' . $ar->iddolzh->dolzh_name;
                                                    }

                                                $osmotrakt = new \app\models\Fregat\Osmotrakt;
                                                $osmotrakt->osmotrakt_id = $row['akt_id'];
                                                $osmotrakt->osmotrakt_comment = $row['akttext_name'];
                                                $osmotrakt->osmotrakt_date = substr($row['akt_date'], 0, 10);
                                                $osmotrakt->id_master = $installer[0]->primaryKey;
                                                $osmotrakt->id_user = $user[0]->primaryKey;
                                                $osmotrakt->id_tr_osnov = $trosnov->primaryKey;
                                                if (!$osmotrakt->save()) {
                                                    $mes .= '<BR>' . print_r($osmotrakt->errors);
                                                    $transaction->rollBack();
                                                } else {
                                                    $mes .= '<BR>' . 'osmotrakt saved';

                                                    if (!empty($row['akt_closedate'])) {
                                                        $Recoverysendakt = new \app\models\Fregat\Recoverysendakt;

                                                        $organname = 'Северная линия';
                                                        if (strtotime($row['akt_closedate']) >= strtotime('2016-05-04'))
                                                            $organname = 'Копи-Мастер';

                                                        $Organ = \app\models\Fregat\Organ::find()->andWhere(['like', 'organ_name', $organname])->one();
                                                        $Recoverysendakt->id_organ = $Organ->PrimaryKey;
                                                        $Recoverysendakt->recoverysendakt_date = substr($row['akt_closedate'], 0, 10);
                                                        if (!$Recoverysendakt->save()) {
                                                            $mes .= '<BR>' . print_r($Recoverysendakt->errors, true);
                                                            $transaction->rollBack();
                                                        } else {

                                                            $Recoveryrecieveakt = new \app\models\Fregat\Recoveryrecieveakt;
                                                            $Recoveryrecieveakt->id_osmotrakt = $osmotrakt->primaryKey;
                                                            $Recoveryrecieveakt->id_recoverysendakt = $Recoverysendakt->primaryKey;
                                                            $Recoveryrecieveakt->recoveryrecieveakt_result = 'Импортировано из старой программы';
                                                            $Recoveryrecieveakt->recoveryrecieveakt_repaired = mb_stripos($row['obor_name'], 'ИБП', 0, 'UTF-8') === false ? 2 : 1;
                                                            $Recoveryrecieveakt->recoveryrecieveakt_date = substr($row['akt_closedate'], 0, 10);
                                                            if (!$Recoveryrecieveakt->save()) {
                                                                $mes .= '<BR>' . print_r($Recoveryrecieveakt->errors, true);
                                                                $transaction->rollBack();
                                                            } else {
                                                                $mes .= '<BR>' . 'Recoveryrecieveakt saved';
                                                                $ok = true;
                                                                $transaction->commit();
                                                                $succ++;
                                                                $fail--;
                                                            }
                                                        }
                                                    } else {
                                                        $ok = true;
                                                        $transaction->commit();
                                                        $succ++;
                                                        $fail--;
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $mes .= '<BR>' . print_r($Mattraffic_tr->errors);
                                        $transaction->rollBack();
                                    }
                                }
                            } catch (Exception $e) {
                                $transaction->rollBack();
                                throw new Exception($e->getMessage());
                            }
                        }
                    }
                } else if (count($mattraffic) == 0) {
                    $mes .= '<BR>' . $row['akt_inv'] . ' not found';
                } else if (count($mattraffic) > 1) {
                    foreach ($mattraffic as $ar) {
                        $mes .= '<BR>' . $ar->idMaterial->material_name . ' inv=' . $ar->idMaterial->material_inv;
                    }
                }
                $mes .= '<BR>' . '------------------------------------------------------------------';
                if (!$ok)
                    echo $mes;
            }
            var_dump('success = ' . $succ . ' (' . (round($succ * 100 / ($succ + $fail), 2)) . '%), fail = ' . $fail . ' from ' . ($succ + $fail));
        } catch (PDOException $e) {
            die('Подключение не удалось: ' . $e->getMessage());
        }
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
        /*  setlocale(LC_ALL, 'ru_RU.UTF-8');
          var_dump(date('d', strtotime('2016-11-05')));
          var_dump(Yii::$app->formatter->asDate(date('M', strtotime('2016-11-05')), 'php:F'));
          var_dump(date('y', strtotime('2016-11-05')));*/

        var_dump(date('d.m.Y', mt_rand(strtotime('2016-01-01'), strtotime('2016-12-31'))));
    }

    public function actionInstallaktFill()
    {
        for ($i = 1; $i <= 100; $i++) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $datetmp = date('Y-m-d', mt_rand(strtotime('2016-10-01'), strtotime(date('Y-m-d'))));

                $isOsn = rand(1, 2);

                $Master = Employee::find()
                    ->andWhere(['in', 'id_dolzh', [149, 151]])
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $Installakt = new Installakt;
                $Installakt->installakt_date = $datetmp;
                $Installakt->id_installer = $Master->primaryKey;
                if (!$Installakt->save())
                    throw new \yii\base\Exception('error');

                $build_id = rand(1, 3);
                $printers = [3, 63];

                if ($isOsn == 1)
                    for ($j = 1; $j <= rand(1, 5); $j++) {
                        $Mattraffic = Mattraffic::find()
                            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                            ->joinWith(['idMaterial', 'idMol'])
                            ->andWhere('(idMaterial.material_tip in (1))')
                            ->andWhere(['in', 'mattraffic_tip', [1]])
                            ->andWhere([
                                'm2.mattraffic_date_m2' => NULL,
                            ])
                            ->andWhere(['idMol.id_build' => $build_id])
                            ->andWhere('not idMol.id_build is null')
                            ->orderBy(new Expression('rand()'))
                            ->limit(1)
                            ->one();

                        $MattrafficMove = new Mattraffic;
                        $MattrafficMove->attributes = $Mattraffic->attributes;
                        $MattrafficMove->mattraffic_tip = 3;
                        if (!$MattrafficMove->save())
                            throw new \yii\base\Exception('error');

                        $trOsnov = new TrOsnov();
                        $trOsnov->id_installakt = $Installakt->primaryKey;
                        $trOsnov->id_mattraffic = $MattrafficMove->primaryKey;
                        $trOsnov->tr_osnov_kab = (string)rand(1, 799);
                        if (!$trOsnov->save())
                            throw new \yii\base\Exception('error');
                        /* var_dump($Installakt->attributes);
                         var_dump($MattrafficMove->attributes);
                         var_dump($trOsnov->attributes);*/

                    }
                else
                    for ($j = 1; $j <= rand(1, 5); $j++) {
                        $Mattraffic = Mattraffic::find()
                            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1)')
                            ->joinWith(['idMaterial', 'idMol'])
                            ->andWhere('(idMaterial.material_tip in (2))')
                            ->andWhere(['in', 'mattraffic_tip', [1]])
                            ->andWhere([
                                'm2.mattraffic_date_m2' => NULL,
                            ])
                            ->andWhere(['idMol.id_build' => $build_id])
                            ->andWhere('not idMol.id_build is null')
                            ->andWhere(['idMaterial.id_matvid' => 76])
                            ->orderBy(new Expression('rand()'))
                            ->limit(1)
                            ->one();

                        $MattrafficParent = Mattraffic::find()
                            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                            ->joinWith(['idMaterial', 'idMol'])
                            ->andWhere('(idMaterial.material_tip in (1))')
                            ->andWhere(['in', 'mattraffic_tip', [1]])
                            ->andWhere([
                                'm2.mattraffic_date_m2' => NULL,
                            ])
                            ->andWhere(['idMol.id_build' => $build_id])
                            ->andWhere('not idMol.id_build is null')
                            ->andWhere(['idMaterial.id_matvid' => $printers[rand(0, 1)]])
                            ->orderBy(new Expression('rand()'))
                            ->limit(1)
                            ->one();

                        $MattrafficMove = new Mattraffic;
                        $MattrafficMove->attributes = $Mattraffic->attributes;
                        $MattrafficMove->mattraffic_tip = 4;
                        if (!$MattrafficMove->save())
                            throw new \yii\base\Exception('error');

                        $trMat = new TrMat;
                        $trMat->id_installakt = $Installakt->primaryKey;
                        $trMat->id_mattraffic = $MattrafficMove->primaryKey;
                        $trMat->id_parent = $MattrafficParent->primaryKey;

                        if (!$trMat->save())
                            throw new \yii\base\Exception('error');
                    }

                $transaction->commit();
            } catch (Exception $e) {
                var_dump($Installakt->errors);
                var_dump($MattrafficMove->errors);
                var_dump($trOsnov->errors);
                $transaction->rollBack();
            }

        }
    }

    public function actionOsmotraktmatFill()
    {
        for ($i = 1; $i <= 100; $i++) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $datetmp = date('Y-m-d', mt_rand(strtotime('2016-10-01'), strtotime(date('Y-m-d'))));

                $Master = Employee::find()
                    ->andWhere(['in', 'id_dolzh', [149, 151]])
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $Osmotraktmat = new Osmotraktmat;
                $Osmotraktmat->osmotraktmat_date = $datetmp;
                $Osmotraktmat->id_master = $Master->primaryKey;
                if (!$Osmotraktmat->save())
                    throw new \yii\base\Exception('error');

                for ($j = 1; $j <= rand(1, 5); $j++) {

                    $TrMat = TrMat::find()
                        ->orderBy(new Expression('rand()'))
                        ->limit(1)
                        ->one();

                    $TrMatOsmotr = new TrMatOsmotr;
                    $TrMatOsmotr->id_osmotraktmat = $Osmotraktmat->primaryKey;
                    $TrMatOsmotr->id_tr_mat = $TrMat->primaryKey;
                    $TrMatOsmotr->id_reason = 10;
                    $TrMatOsmotr->tr_mat_osmotr_number = rand(1, $TrMat->idMattraffic->mattraffic_number);
                    if (!$TrMatOsmotr->save())
                        throw new \yii\base\Exception('error');
                }

                $transaction->commit();
            } catch (Exception $e) {
                var_dump($Master->errors);
                var_dump($TrMat->errors);
                var_dump($Osmotraktmat->errors);

                $transaction->rollBack();
            }

        }
    }

    public function actionSpisosnovaktFill()
    {
        for ($i = 1; $i <= 50; $i++) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $datetmp = date('Y-m-d', mt_rand(strtotime('2016-10-01'), strtotime(date('Y-m-d'))));

                $MattrafficSchetuchet = Mattraffic::find()
                    ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1)')
                    ->joinWith(['idMaterial', 'idMol'])
                    ->andWhere('(idMaterial.material_tip in (1))')
                    ->andWhere(['in', 'mattraffic_tip', [1]])
                    ->andWhere([
                        'm2.mattraffic_date_m2' => NULL,
                    ])
                    ->andWhere('not idMaterial.id_schetuchet is null')
                    /* ->andWhere(['idMaterial.id_schetuchet' => $Schetuchet->primaryKey])
                     ->andWhere(['id_mol' => $Mol->id_mol])*/
                    //  ->andWhere('not idMol.id_build is null')
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $Employee = Employee::find()->orderBy(new Expression('rand()'))->limit(1)->one();

                $Spisosnovakt = new Spisosnovakt;
                $Spisosnovakt->spisosnovakt_date = $datetmp;
                $Spisosnovakt->id_schetuchet = $MattrafficSchetuchet->idMaterial->id_schetuchet;
                $Spisosnovakt->id_mol = $MattrafficSchetuchet->id_mol;
                $Spisosnovakt->id_employee = rand(0, 1) == 1 ? $Employee->primaryKey : NULL;

                // var_dump($MattrafficSchetuchet->idMaterial->idSchetuchet->schetuchet_kod);

                if (!$Spisosnovakt->save())
                    throw new \yii\base\Exception('error');

                $Mattraffic = Mattraffic::find()
                    ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1)')
                    ->joinWith(['idMaterial', 'idMol'])
                    ->andWhere('(idMaterial.material_tip in (1))')
                    ->andWhere(['in', 'mattraffic_tip', [1]])
                    ->andWhere([
                        'm2.mattraffic_date_m2' => NULL,
                        'idMaterial.material_writeoff' => 0,
                    ])
                    ->andWhere(['idMaterial.id_schetuchet' => $MattrafficSchetuchet->idMaterial->id_schetuchet])
                    ->andWhere(['id_mol' => $MattrafficSchetuchet->id_mol])
                    //  ->andWhere('not idMol.id_build is null')
                    ->orderBy(new Expression('rand()'))
                    ->limit(rand(1, 20))
                    ->all();

                if (empty($Mattraffic))
                    throw new \yii\base\Exception('error');

                foreach ($Mattraffic as $ar) {
                    // var_dump($ar->idMaterial->idSchetuchet->schetuchet_kod);
                    $Spisosnovmaterials = new Spisosnovmaterials;
                    $Spisosnovmaterials->id_spisosnovakt = $Spisosnovakt->primaryKey;
                    $Spisosnovmaterials->id_mattraffic = $ar->primaryKey;
                    $Spisosnovmaterials->spisosnovmaterials_number = 1;
                    if (!$Spisosnovmaterials->save())
                        throw new \yii\base\Exception('error');
                }

                $transaction->commit();
            } catch (Exception $e) {
                var_dump($Spisosnovakt->errors);
                var_dump($Mattraffic);
                var_dump($Spisosnovmaterials->errors);
                $transaction->rollBack();
            }

        }
    }

    public function actionNakladFill()
    {
        for ($i = 1; $i <= 50; $i++) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $datetmp = date('Y-m-d', mt_rand(strtotime('2016-10-01'), strtotime(date('Y-m-d'))));

                $MolRelease = Mattraffic::find()
                    ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1)')
                    ->joinWith(['idMaterial', 'idMol'])
                    ->andWhere('(idMaterial.material_tip in (1))')
                    ->andWhere(['in', 'mattraffic_tip', [1]])
                    ->andWhere([
                        'm2.mattraffic_date_m2' => NULL,
                    ])
                    /* ->andWhere(['idMaterial.id_schetuchet' => $Schetuchet->primaryKey])
                     ->andWhere(['id_mol' => $Mol->id_mol])*/
                    //  ->andWhere('not idMol.id_build is null')
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $MolGot = Mattraffic::find()
                    ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1)')
                    ->joinWith(['idMaterial', 'idMol'])
                    ->andWhere('(idMaterial.material_tip in (1))')
                    ->andWhere(['in', 'mattraffic_tip', [1]])
                    ->andWhere([
                        'm2.mattraffic_date_m2' => NULL,
                    ])
                    ->andWhere('id_mol <> ' . $MolRelease->id_mol)
                    /* ->andWhere(['idMaterial.id_schetuchet' => $Schetuchet->primaryKey])
                     ->andWhere(['id_mol' => $Mol->id_mol])*/
                    //  ->andWhere('not idMol.id_build is null')
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $MattrafficRelease = Mattraffic::find()
                    ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1)')
                    ->joinWith(['idMaterial', 'idMol'])
                    ->andWhere('(idMaterial.material_tip in (1))')
                    ->andWhere(['in', 'mattraffic_tip', [1]])
                    ->andWhere([
                        'm2.mattraffic_date_m2' => NULL,
                        'idMaterial.material_writeoff' => 0,
                    ])
                    ->andWhere(['id_mol' => $MolRelease->id_mol])
                    //  ->andWhere('not idMol.id_build is null')
                    ->orderBy(new Expression('rand()'))
                    ->limit(rand(1, 20))
                    ->all();

                if (empty($MattrafficRelease))
                    throw new \yii\base\Exception('error');

                $Naklad = new Naklad;
                $Naklad->naklad_date = $datetmp;
                $Naklad->id_mol_release = $MolRelease->id_mol;
                $Naklad->id_mol_got = $MolGot->id_mol;
                if (!$Naklad->save())
                    throw new \yii\base\Exception('error');

                foreach ($MattrafficRelease as $ar) {
                    $Nakladmaterials = new Nakladmaterials;
                    $Nakladmaterials->id_naklad = $Naklad->primaryKey;
                    $Nakladmaterials->id_mattraffic = $ar->primaryKey;
                    $Nakladmaterials->nakladmaterials_number = 1;
                    if (!$Nakladmaterials->save())
                        throw new \yii\base\Exception('error');
                }

                $transaction->commit();
            } catch (Exception $e) {
                echo $e->getMessage();
                var_dump($Naklad->errors);
                var_dump($Nakladmaterials->errors);
                $transaction->rollBack();
            }

        }
    }

    public function actionGlaukFill()
    {
        for ($i = 1; $i <= 1; $i++) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $datetmp = date('Y-m-d', mt_rand(strtotime('2016-10-01'), strtotime(date('Y-m-d'))));

                $patientFile = [
                    dirname(dirname(__DIR__)) . '/tests/_data/patients_f.csv',
                    dirname(dirname(__DIR__)) . '/tests/_data/patients_m.csv',
                ];

                $patient_pol = rand(0, 1);
                $f_contents = file($patientFile[$patient_pol]);
                $patientLine = $f_contents[rand(0, count($f_contents) - 1)];

                $patientFio = explode(';', $patientLine);

                $Fias = Fias::find()
                    ->andWhere(['SHORTNAME' => 'ул'])
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $Patient = new Patient;
                $Patient->patient_fam = $patientFio[0];
                $Patient->patient_im = $patientFio[1];
                $Patient->patient_ot = $patientFio[2];
                $Patient->patient_dr = date('Y-m-d', mt_rand(strtotime('1920-01-01'), strtotime('1990-12-31')));
                $Patient->patient_pol = $patient_pol;
                $Patient->id_fias = $Fias->AOGUID;
                $Patient->patient_dom = (string)rand(1, 120);
                $Patient->patient_kvartira = (string)rand(1, 199);


                $transaction->commit();
            } catch (Exception $e) {
                echo $e->getMessage();
                $transaction->rollBack();
            }

        }
    }

}
                                        