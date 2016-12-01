<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 01.12.2016
 * Time: 8:23
 */

namespace app\func;


use app\models\Base\Classmkb;
use app\models\Base\Fias;
use app\models\Base\Patient;
use app\models\Base\Preparat;
use app\models\Fregat\Employee;
use app\models\Fregat\Installakt;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Naklad;
use app\models\Fregat\Nakladmaterials;
use app\models\Fregat\Osmotrakt;
use app\models\Fregat\Osmotraktmat;
use app\models\Fregat\Reason;
use app\models\Fregat\Removeakt;
use app\models\Fregat\Spisosnovakt;
use app\models\Fregat\Spisosnovmaterials;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrMatOsmotr;
use app\models\Fregat\TrOsnov;
use app\models\Fregat\TrRmMat;
use app\models\Glauk\Glaukuchet;
use app\models\Glauk\Glprep;
use Exception;
use Yii;
use yii\db\Expression;

class PopulateData
{
    public static function init()
    {
        return new self;
    }

    public function installAkt($count)
    {

        for ($i = 1; $i <= $count; $i++) {
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

    public function osmotrAktMat($count)
    {
        for ($i = 1; $i <= $count; $i++) {
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

    public function spisOsnovAkt($count)
    {
        for ($i = 1; $i <= $count; $i++) {
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

    public function naklad($count)
    {
        for ($i = 1; $i <= $count; $i++) {
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

    public function glauk($count)
    {
        for ($i = 1; $i <= $count; $i++) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $datetmp = date('Y-m-d', mt_rand(strtotime('2016-10-01'), strtotime(date('Y-m-d'))));

                $patientFile = [
                    dirname(__DIR__) . '/tests/_data/patients_m.csv',
                    dirname(__DIR__) . '/tests/_data/patients_f.csv',
                ];

                $patient_pol = rand(1, 2);
                $f_contents = file($patientFile[$patient_pol - 1]);
                $patientLine = $f_contents[rand(0, count($f_contents) - 1)];

                $patientFio = explode(';', $patientLine);

                $Fias = Fias::find()
                    ->andWhere(['SHORTNAME' => 'ул'])
                    ->andWhere(['PARENTGUID' => '0bf0f4ed-13f8-446e-82f6-325498808076'])
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $Classmkb = Classmkb::find()
                    ->andWhere(['or', ['like', 'code', 'H40%', false], ['like', 'code', 'Q15.0', false]])
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $Vrach = Employee::find()
                    ->joinWith('iddolzh')
                    ->andWhere(['like', 'iddolzh.dolzh_name', 'ВРАЧ-ОФТАЛЬМОЛОГ', false])
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
                if (!$Patient->save())
                    throw new \yii\base\Exception('error');

                $Glaukuchet = new Glaukuchet;
                $Glaukuchet->glaukuchet_uchetbegin = $datetmp;
                $Glaukuchet->id_patient = $Patient->primaryKey;
                $Glaukuchet->id_class_mkb = $Classmkb->primaryKey;
                $Glaukuchet->id_employee = $Vrach->primaryKey;
                $Glaukuchet->glaukuchet_detect = rand(1, 2);

                $Glaukuchet->glaukuchet_stage = rand(1, 4);
                if (!rand(0, 4))
                    $Glaukuchet->glaukuchet_operdate = date('Y-m-d', mt_rand(strtotime('2000-01-01'), strtotime('2016-11-30')));

                if (!rand(0, 4))
                    $Glaukuchet->glaukuchet_invalid = rand(1, 3);

                $Glaukuchet->glaukuchet_lastvisit = date('Y-m-d', mt_rand(strtotime($datetmp), strtotime(date('Y-m-d'))));

                if (!rand(0, 2))
                    $Glaukuchet->glaukuchet_lastmetabol = date('Y-m-d', mt_rand(strtotime('2015-01-01'), strtotime('2016-11-30')));

                if (!$Glaukuchet->save())
                    throw new \yii\base\Exception('error');

                if (!rand(0, 3)) {
                    $Preparat = Preparat::find()
                        ->orderBy(new Expression('rand()'))
                        ->limit(rand(1, 3))
                        ->all();

                    foreach ($Preparat as $ar) {
                        $Glprep = new Glprep;
                        $Glprep->id_glaukuchet = $Glaukuchet->primaryKey;
                        $Glprep->id_preparat = $ar->primaryKey;

                        if (!rand(0, 2))
                            $Glprep->glprep_rlocat = rand(1, 2);

                        if (!$Glprep->save())
                            throw new \yii\base\Exception('error');
                    }

                }

                $transaction->commit();
            } catch (Exception $e) {
                echo $e->getMessage();

                var_dump($Patient->errors);
                var_dump($Glaukuchet->errors);
                var_dump($Glprep->errors);

                $transaction->rollBack();
            }

        }
    }

    public function osmotrakt($count)
    {
        for ($i = 1; $i <= $count; $i++) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $datetmp = date('Y-m-d', mt_rand(strtotime('2016-10-01'), strtotime(date('Y-m-d'))));

                $Master = Employee::find()
                    ->andWhere(['in', 'id_dolzh', [149, 151]])
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $User = Employee::find()
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $Reason = Reason::find()
                    ->andWhere(['not', ['reason_id' => 10]])
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $trOsnov = TrOsnov::find()
                    ->joinWith('idMattraffic.idMaterial.idMatv.grupavids')
                    ->andWhere(['grupavids.id_grupa' => 1])
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $Osmotrakt = new Osmotrakt;
                $Osmotrakt->osmotrakt_date = $datetmp;
                $Osmotrakt->id_master = $Master->primaryKey;
                $Osmotrakt->id_user = $User->primaryKey;
                $Osmotrakt->id_reason = $Reason->primaryKey;
                $Osmotrakt->id_tr_osnov = $trOsnov->primaryKey;


                if (!$Osmotrakt->save())
                    throw new \yii\base\Exception('error');


                $transaction->commit();
            } catch (Exception $e) {
                var_dump($Osmotrakt->errors);
                $transaction->rollBack();
            }

        }
    }

    public function removeAkt($count)
    {
        for ($i = 1; $i <= $count; $i++) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $datetmp = date('Y-m-d', mt_rand(strtotime('2016-10-01'), strtotime(date('Y-m-d'))));

                $Remover = Employee::find()
                    ->andWhere(['in', 'id_dolzh', [149, 151]])
                    ->orderBy(new Expression('rand()'))
                    ->limit(1)
                    ->one();

                $Removeakt = new Removeakt;
                $Removeakt->removeakt_date = $datetmp;
                $Removeakt->id_remover = $Remover->primaryKey;
                if (!$Removeakt->save())
                    throw new \yii\base\Exception('error');

                $TrMat = TrMat::find()
                    ->orderBy(new Expression('rand()'))
                    ->limit(rand(1, 5))
                    ->all();

                foreach ($TrMat as $ar) {
                    $TrRmMat = new TrRmMat;
                    $TrRmMat->id_removeakt = $Removeakt->primaryKey;
                    $TrRmMat->id_tr_mat = $ar->primaryKey;

                    if (!$TrRmMat->save())
                        throw new \yii\base\Exception('error');
                }

                $transaction->commit();
            } catch (Exception $e) {
                var_dump($Removeakt->errors);
                var_dump($TrRmMat->errors);
                $transaction->rollBack();
            }

        }
    }

}