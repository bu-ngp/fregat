<?php

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 19.10.2016
 * Time: 16:28
 */

namespace app\func\ImportData\Exec;

use app\func\ImportData\Exec\EmployeeParseFactory;
use app\func\ImportData\Exec\EmployeeParseObject;
use app\func\ImportData\Proc\ImportFromTextFile;
use app\func\ImportData\Proc\iImportLog;
use app\func\ImportData\Proc\ImportLog;
use app\models\Config\Authuser;
use app\models\Config\Profile;
use app\models\Fregat\Employee;
use app\models\Fregat\Import\Employeelog;
use Exception;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class Employees
 * @package app\func\ImportData\Exec
 */
final class Employees extends ImportFromTextFile implements iEmployees
{
    /**
     *
     */
    const Pattern = '/^(.*?)\|(Поликлиника №\s?[1,2,3] )?(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|/ui';

    /**
     * @var Authuser
     */
    private $_authUser;
    /**
     * @var bool
     */
    private $_newAuthuser;
    /**
     * @var Profile
     */
    private $_profile;
    /**
     * @var Employee
     */
    private $_employee;
    /**
     * @var EmployeeParseObject
     */
    private $_employeeObj;
    /**
     * @var array
     */
    private $_errors = [];
    /**
     * @var integer
     */
    private $_mishanya;

    /* Getters/Setters */

    /**
     * @return Employee
     */
    private function getEmployee()
    {
        return $this->_employee;
    }

    /**
     * @param Employee|ActiveRecord $employee
     */
    private function setEmployee($employee)
    {
        $this->_employee = $employee;
    }

    /**
     *
     */
    private function resetEmployee()
    {
        $this->_employee = null;
    }

    /**
     * @return Authuser
     */
    private function getAuthUser()
    {
        return $this->_authUser;
    }

    /**
     * @param Authuser|ActiveRecord $authUser
     */
    private function setAuthUser(Authuser $authUser)
    {
        $this->_authUser = $authUser;
    }

    /**
     *
     */
    private function resetAuthUser()
    {
        $this->_authUser = null;
    }

    /**
     * @return Profile
     */
    private function getProfile()
    {
        return $this->_profile;
    }

    /**
     * @param Profile|ActiveRecord $profile
     */
    private function setProfile($profile)
    {
        $this->_profile = $profile;
    }

    /**
     *
     */
    private function resetProfile()
    {
        $this->_profile = null;
    }

    /**
     * @return EmployeeParseObject
     */
    public function getEmployeeParseObject()
    {
        return $this->_employeeObj;
    }

    /**
     * @return array
     */
    private function getErrors()
    {
        return $this->_errors;
    }


    /**
     * @param array $activeRecordErrors
     */
    private function setErrors(array $activeRecordErrors)
    {
        $this->_errors = $activeRecordErrors;
    }

    /**
     * @return integer
     */
    private function getMishanya()
    {
        return $this->_mishanya;
    }

    /**
     * @param integer $mishanya
     */
    private function setMishanya($mishanya)
    {
        $this->_mishanya = $mishanya;
    }

    /* Вспомогательные */


    /**
     *
     */
    private function clearErrors()
    {
        $this->_errors = [];
    }

    /**
     * @return bool
     */
    private function hasErrors()
    {
        return !empty($this->_errors);
    }

    /**
     * @return array
     */
    private function applyValuesLog()
    {
        return [
            'employee_fio' => $this->getEmployeeParseObject()->auth_user_fullname,
            'dolzh_name' => $this->getObserverByFieldName('dolzh_name')->getValue(),
            'podraz_name' => $this->getObserverByFieldName('podraz_name')->getValue(),
            'build_name' => $this->getObserverByFieldName('build_name')->getValue(),
        ];
    }

    /**
     * @return bool
     */
    private function isNewAuthuser()
    {
        return $this->_newAuthuser;
    }

    /**
     *
     */
    private function notNewAuthuser()
    {
        $this->_newAuthuser = false;
    }

    /**
     *
     */
    private function newAuthuser()
    {
        $this->_newAuthuser = true;
    }

    /**
     * @param ActiveRecord $activeRecord
     * @param string $ScenarioName
     * @return bool
     */
    private function hasScenario(ActiveRecord $activeRecord, $ScenarioName)
    {
        $Scenarios = $activeRecord->scenarios();
        return isset($Scenarios[$ScenarioName]);
    }

    /* inactiveEmployee() */

    /**
     * @return bool
     */
    private function inactiveCreate()
    {
        return Yii::$app->db->createCommand('UPDATE employee AS a INNER JOIN ( SELECT y2.employee_id FROM employee y2 WHERE y2.employee_dateinactive IS NULL and employee_importdo = 1 GROUP BY y2.id_person HAVING count(y2.employee_forinactive) = 0 ) AS b ON a.employee_id = b.employee_id  SET employee_dateinactive = DATE(NOW()), employee_forinactive = 2')
            ->execute() > 0;
    }

    /**
     * @return bool
     */
    private function inactiveLog()
    {
        $Forlog = Employee::find()
            ->joinWith(['idperson', 'idpodraz', 'iddolzh', 'idbuild'])
            ->andWhere(' DATE(employee_dateinactive) = CURDATE()')
            ->andWhere(['employee_forinactive' => 2])
            ->all();

        if (empty($Forlog))
            return false;
        else
            foreach ($Forlog as $i => $ar) {
                $Employeelog = new Employeelog;
                $Employeelog->id_logreport = $this->getLogReport()->primaryKey;
                $Employeelog->employeelog_type = 2;
                $Employeelog->employeelog_filename = $this->getFileName();
                $Employeelog->employeelog_filelastdate = $this->getFileLastDate();
                $Employeelog->employeelog_rownum = 0;
                $Employeelog->employeelog_message = 'Запись изменена. Специальность сотрудника неактивна с "' . Yii::$app->formatter->asDate($ar->employee_dateinactive) . '"';
                $Employeelog->employee_fio = $ar->idperson->auth_user_fullname;
                $Employeelog->dolzh_name = $ar->iddolzh->dolzh_name;
                $Employeelog->podraz_name = $ar->idpodraz->podraz_name;
                $Employeelog->build_name = $ar->isRelationPopulated('idbuild') ? $ar->idbuild['build_name'] : '';

                $Employeelog->save(false);
            }
        return true;
    }

    /**
     * @return bool
     */
    private function resetEmployeeForInactive()
    {
        return Employee::updateAll(['employee_forinactive' => NULL], ['in', 'employee_forinactive', [1, 2]]) > 0;
    }

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     */
    private function inactiveEmployee()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->inactiveCreate();

            $this->inactiveLog();

            $this->resetEmployeeForInactive();

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->resetEmployeeForInactive();
            throw new Exception($e->getMessage() . ' InactiveEmployee(), $filename = ' . $this->getFileName());
        }
    }

    /* EmployeeProcess */

    /**
     * Метод транслитирирует русские символы на латинский.
     * @param string $string Строка, которую необходимо транслитирировать.
     * @return string Строка результат транслитерации.
     */
    private function Translit($string)
    {
        $replace = array(
            "'" => "",
            "`" => "",
            "а" => "a", "А" => "a",
            "б" => "b", "Б" => "b",
            "в" => "v", "В" => "v",
            "г" => "g", "Г" => "g",
            "д" => "d", "Д" => "d",
            "е" => "e", "Е" => "e",
            "ё" => "e", "Ё" => "e",
            "ж" => "zh", "Ж" => "zh",
            "з" => "z", "З" => "z",
            "и" => "i", "И" => "i",
            "й" => "y", "Й" => "y",
            "к" => "k", "К" => "k",
            "л" => "l", "Л" => "l",
            "м" => "m", "М" => "m",
            "н" => "n", "Н" => "n",
            "о" => "o", "О" => "o",
            "п" => "p", "П" => "p",
            "р" => "r", "Р" => "r",
            "с" => "s", "С" => "s",
            "т" => "t", "Т" => "t",
            "у" => "u", "У" => "u",
            "ф" => "f", "Ф" => "f",
            "х" => "h", "Х" => "h",
            "ц" => "c", "Ц" => "c",
            "ч" => "ch", "Ч" => "ch",
            "ш" => "sh", "Ш" => "sh",
            "щ" => "sch", "Щ" => "sch",
            "ъ" => "", "Ъ" => "",
            "ы" => "y", "Ы" => "y",
            "ь" => "", "Ь" => "",
            "э" => "e", "Э" => "e",
            "ю" => "yu", "Ю" => "yu",
            "я" => "ya", "Я" => "ya",
            "і" => "i", "І" => "i",
            "ї" => "yi", "Ї" => "yi",
            "є" => "e", "Є" => "e"
        );

        return $str = iconv("UTF-8", "UTF-8//IGNORE", strtr($string, $replace));
    }


    /**
     * @return bool
     */
    private function existsEmployee()
    {
        $Result = Employee::find()
            ->joinWith('idperson')
            ->where(array_merge([
                'id_dolzh' => $this->getObserverByFieldName('dolzh_name')->getID(),
                'id_podraz' => $this->getObserverByFieldName('podraz_name')->getID(),
            ], empty($this->getObserverByFieldName('build_name')->getID()) ? [] : ['id_build' => $this->getObserverByFieldName('build_name')->getID()]))
            ->andFilterWhere(['like', 'auth_user_fullname', $this->getEmployeeParseObject()->auth_user_fullname, false])
            ->orderBy(['employee_id' => SORT_DESC])
            ->one();

        if (empty($Result))
            return false;
        else {
            $this->setEmployee($Result);

            if ($this->hasScenario($this->getEmployee(), 'import1c'))
                $this->getEmployee()->scenario = 'import1c';
        }

        return true;
    }

    /**
     * @return Employees
     */
    private function createAuthuser()
    {
        if (!$this->hasErrors()) {
            $AuthuserCount = Authuser::find()
                ->where(['like', 'auth_user_fullname', $this->getEmployeeParseObject()->auth_user_fullname, false])
                ->count();

            if ($AuthuserCount == 1)
                $this->setAuthUser(Authuser::find()
                    ->where(['like', 'auth_user_fullname', $this->getEmployeeParseObject()->auth_user_fullname, false])
                    ->one());

            if (empty($this->getAuthUser()) || $AuthuserCount > 1) {
                $this->newAuthuser();
                $this->setAuthUser($this->generateAuthuser());
            }

            if ($this->hasScenario($this->getAuthUser(), 'import1c'))
                $this->getAuthUser()->scenario = 'import1c';

            if (!$this->getAuthUser()->save()) {
                $this->setErrors($this->getAuthUser()->getErrors());
            }
        }

        return $this;
    }

    /**
     * @return Authuser
     * @throws \yii\base\Exception
     */
    private function generateAuthuser()
    {
        $Authuser = new Authuser;
        $Authuser->auth_user_fullname = $this->getEmployeeParseObject()->auth_user_fullname;
        $Authuser->auth_user_login = $this->createLogin($this->getEmployeeParseObject()->auth_user_fullname);
        $Authuser->auth_user_password = Yii::$app->getSecurity()->generatePasswordHash('11111111');
        return $Authuser;
    }

    /**
     * Фукния создает Логин пользователя на основе полного ФИО.
     * Например, $Fullname = 'Иванов Петр Сергеевич' выведет 'IvanovPS'.
     * Если логин 'IvanovPS' существует, то метод добавит количество совпадающих логинов в конце результата, т.е. IvanovPS1, IvanovPS2, и т.д.
     * @param string $Fullname ФИО пользователя полностью.
     * @return string Преобразованный логин.
     */
    private function createLogin($Fullname)
    {
        preg_match('/(\w+)\s?(\w+)?\s?(\w+)?/ui', $Fullname, $matches);
        $result = '';

        if (!empty($matches[1]))
            $result .= ucfirst(self::Translit($matches[1]));
        if (!empty($matches[2]))
            $result .= ucfirst(self::Translit(mb_substr($matches[2], 0, 1, 'UTF-8')));
        if (!empty($matches[3]))
            $result .= ucfirst(self::Translit(mb_substr($matches[3], 0, 1, 'UTF-8')));

        $count = Authuser::find()
            ->where(['like', 'auth_user_login', $result . '%', false])
            ->count();

        return $count > 0 ? $result . $count : $result;
    }

    /**
     * @param EmployeeParseObject $employeeParseObject
     * @return Employees
     */
    private function createProfile(EmployeeParseObject $employeeParseObject)
    {
        if (!$this->hasErrors()) {
            $this->setProfile(Profile::findOne($this->getAuthUser()->primaryKey));
            if (!$this->getProfile())
                $this->setProfile(new Profile());

            $this->getProfile()->profile_id = $this->getAuthUser()->primaryKey;
            $this->getProfile()->profile_dr = $employeeParseObject->profile_dr;
            $this->getProfile()->profile_pol = $employeeParseObject->profile_pol;
            $this->getProfile()->profile_inn = $employeeParseObject->profile_inn;
            $this->getProfile()->profile_snils = $employeeParseObject->profile_snils;
            $this->getProfile()->profile_address = $employeeParseObject->profile_address;

            if (!$this->getProfile()->save())
                $this->setErrors($this->getProfile()->getErrors());
        }

        return $this;
    }

    /**
     * @return Employees
     */
    private function createEmployee()
    {
        if (!$this->hasErrors()) {
            $this->setEmployee(new Employee);
            $this->getEmployee()->attributes = [
                'id_dolzh' => $this->getObserverByFieldName('dolzh_name')->getID(),
                'id_podraz' => $this->getObserverByFieldName('podraz_name')->getID(),
                'id_build' => $this->getObserverByFieldName('build_name')->getID(),
                'employee_forinactive' => 1,
            ];

            if ($this->hasScenario($this->getEmployee(), 'import1c'))
                $this->getEmployee()->scenario = 'import1c';

            $this->getEmployee()->id_person = $this->getAuthUser()->primaryKey;
            if (!$this->getEmployee()->save()) {
                $this->setErrors($this->getEmployee()->getErrors());
            }
        }

        return $this;
    }

    /**
     *
     */
    private function mishanya()
    {
        if ($this->getMishanya() < 3) {
            $dr = Yii::$app->formatter->asDate($this->getProfile()->profile_dr);
            $d1 = new \DateTime($dr);
            $d2 = new \DateTime(date('Y-m-d'));
            $diff = $d2->diff($d1);

            $subthemes = [
                'Мишаня, новые телочки, пора за работу',
                'Мишаня, настало твое время',
                'Мишаня, мы все верим в тебя',
                'Мишаня, все в твоих руках, за работу',
                'Мишаня, на этот раз у тебя полюбому все получится',
                'Мишаня, я лично в тебя верю, давай',
                'Мишаня, мы будем болеть за тебя всем отделом',
                'Мишаня, Президент лично одобрил',
                'Мишаня, все схвачено, дело за тобой',
                'Мишаня, давай пацан',
                'Мишаня, у тебя все получится',
                'Мишаня, вперед, в атаку',
                'Мишаня, хватит распиздяйничать, займись делом',
                'Мишаня, появилась непыльная работенка',
                'Мишаня, кажется ты нашел свою новую жену',
                'Мишаня, тебе пора жениться',
                'Мишаня, она ждет',
                'Мишаня, кажется кто-то нуждается в твоих комплементах',
                'Мишаня, найден новый объект для комплиментиков',
                'Мишаня, с ней только серьезные отношения',
                'Мишаня, она думает о тебе',
                'Мишаня, хватит душить удава, займись делом',
                'Мишаня, тут Кондратьевские подсуетились',
                'Мишаня, получи новое задание Кунимена',
                'Мишаня, киевская развед-школа поработала на тебя',
                'Мишаня, у нее компьютер сломался, нужно проверить',
                'Мишаня, пора поработать с низкого старта',
                'Мишаня, партийное задание',
                'Мишаня, надевай тапки пиздуна, есть задание',
            ];

            if ($this->getProfile()->profile_pol == 'Ж' && $diff->y <= 35) {
                $this->setMishanya($this->getMishanya() + 1);
                Yii::$app->mailer->compose('/site/misha', [
                    'fio' => $this->getAuthUser()->auth_user_fullname,
                    'dr' => $dr,
                    'vozrast' => $diff->y,
                    'dolzh' => $this->getObserverByFieldName('dolzh_name')->getValue(),
                    'podraz' => $this->getObserverByFieldName('podraz_name')->getValue(),
                    'build' => $this->getObserverByFieldName('build_name')->getValue(),
                    'address' => $this->getProfile()->profile_address,
                ])
                    ->setFrom('portal@mugp-nv.ru')
                    ->setTo([
                        'karpovvv@mugp-nv.ru',
                        'mns@mugp-nv.ru',
                        'dnn@mugp-nv.ru',
                        'mns@mugp-nv.ru',
                        'dvg@mugp-nv.ru',
                        'chepenkoav@mugp-nv.ru',
                        'valikanovae@mugp-nv.ru',
                    ])
                    ->setSubject($subthemes[rand(0, count($subthemes) - 1)])
                    ->send();
            }
        }
    }

    /**
     * @return null|Employee|ActiveRecord
     */
    private function hasEmployeeInactiveByPerson()
    {
        return Employee::find()
            ->andWhere(['id_person' => $this->getEmployee()->id_person])
            ->groupBy(['id_person'])
            ->having('count(employee_dateinactive) = count(id_person)')
            ->one();

    }

    /* processItem() */

    /**
     *
     */
    private function reset()
    {
        $this->resetAuthUser();
        $this->resetProfile();
        $this->resetEmployee();
    }

    /**
     * @param iImportLog $importLog
     */
    private function changeExistEmployee(iImportLog $importLog)
    {
        $this->setAuthUser(Authuser::findOne($this->getEmployee()->id_person));

        if ($this->getEmployee()->employee_importdo === 1) {

            if ($this->hasEmployeeInactiveByPerson()) {
                $importLog->setup(iImportLog::CHANGE, [], 'Очищена дата неактивности специальности "' . Yii::$app->formatter->asDate($this->getEmployee()->employee_dateinactive) . '"');
                $this->getEmployee()->employee_dateinactive = null;
            }

            $this->getEmployee()->employee_forinactive = 1;
            $this->getEmployee()->save(false);

            $AR_Errors = $this->createProfile($this->getEmployeeParseObject())->getErrors();
            if ($AR_Errors)
                $importLog->setup(iImportLog::ADD_ERROR, $AR_Errors);
        }
    }

    /**
     * @param iImportLog $importLog
     */
    private function addNewEmployee(iImportLog $importLog)
    {
        $AR_Errors = $this->createAuthuser()
            ->createProfile($this->getEmployeeParseObject())
            ->createEmployee()
            ->getErrors();

        $importLog->setup($AR_Errors ? iImportLog::ADD_ERROR : iImportLog::ADD, $AR_Errors);

        if (!$AR_Errors && $this->isNewAuthuser() && !$this->getDebug()) {
            $this->mishanya();
        }
    }

    /**
     *
     */
    protected function beforeIterateItem()
    {
        $this->notNewAuthuser();

        $this->clearErrors();

        $this->reset();

        $this->setImportLog(new ImportLog($this, new Employeelog));
    }

    /**
     * @param string $String
     * @throws Exception
     */
    protected function processItem($String)
    {
        if (!empty($String) && is_string($String)) {

            $EmployeeObj = EmployeeParseFactory::employee($String)->create();

            if ($EmployeeObj) {

                $this->installParseObject($EmployeeObj);

                $this->notify();

                $this->existsEmployee() ? $this->changeExistEmployee($this->getImportLog('Employeelog')) : $this->addNewEmployee($this->getImportLog('Employeelog'));

            } else
                $this->getImportLog('Employeelog')->setup(iImportLog::ADD_ERROR, [], 'Неверный формат строки');

        } else
            $this->getImportLog('Employeelog')->setup(iImportLog::ADD_ERROR, [], 'Строка пуста.');
    }

    /**
     *
     */
    protected function afterIterateItem()
    {
        $this->getImportLog('Employeelog')->end($this->applyValuesLog());
    }

    /**
     * @throws Exception
     */
    protected function afterIterateAll()
    {
        if (!$this->getDebug())
            $this->inactiveEmployee();
    }

    /* Реализация интерфейса SplObserver Наблюдателя */

    /**
     * @param EmployeeParseObject $ParseObject
     */
    public function installParseObject(EmployeeParseObject $ParseObject)
    {
        $this->_employeeObj = $ParseObject;

        foreach ($this->getObservers() as $value) {
            $value->setValue($ParseObject->prop($value->getFieldName()));
        }
    }

}