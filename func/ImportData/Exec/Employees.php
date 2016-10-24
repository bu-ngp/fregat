<?php

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 19.10.2016
 * Time: 16:28
 */

namespace app\func\ImportData\Exec;

use app\func\ImportData\Proc\DataFilter;
use app\func\ImportData\Proc\EmployeeParseFactory;
use app\func\ImportData\Proc\EmployeeParseObject;
use app\func\ImportData\Proc\ImportFromTextFile;
use app\func\ImportData\Proc\ImportLog;
use app\models\Config\Authuser;
use app\models\Config\Profile;
use app\models\Fregat\Employee;
use app\models\Fregat\Import\Employeelog;
use Exception;
use SplObserver;
use Yii;

class Employees extends ImportFromTextFile implements \SplSubject
{
    const Pattern = '/^(.*?)\|(Поликлиника №\s?[1,2,3] )?(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|/ui';

    private $observers = array();
    private $_employeeFio;
    private $_employee;
    private $_newAuthuser;
    private $_authUser;
    private $_employeeLog;
    private $_rowChanged;
    private $_employeeObj;
    private $_errors;

    /**
     * @var Profile
     */
    private $_profile;
    private $_mishanya;

    protected function getItem()
    {
        // TODO: Implement getItem() method.
    }

    protected function afterIterateItem()
    {
        // TODO: Implement afterIterateItem() method.
    }

    private function inactiveEmployee()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->db->createCommand('UPDATE employee inner join (select e.id_person from employee e group by e.id_person having count(e.id_person) = 1) e2 on employee.id_person = e2.id_person SET employee_dateinactive=NOW() WHERE employee_forinactive is null and employee_importdo = 1 and employee_dateinactive is null')
                ->execute();

            $Forlog = Employee::find()
                ->joinWith(['idperson', 'idpodraz', 'iddolzh', 'idbuild'])
                ->where('employee_forinactive is null and employee_importdo = 1 and DATE(employee_dateinactive) = CURDATE() and id_person in (select e.id_person from employee e group by e.id_person having count(e.id_person) = 1)')
                ->all();

            if (!empty($Forlog))
                foreach ($Forlog as $i => $ar) {
                    $Employeelog = new Employeelog;
                    $Employeelog->id_logreport = $this->logReport->primaryKey;
                    $Employeelog->employeelog_type = 2;
                    $Employeelog->employeelog_filename = $this->fileName;
                    $Employeelog->employeelog_filelastdate = $this->fileLastDate;
                    $Employeelog->employeelog_rownum = 0;
                    $Employeelog->employeelog_message = 'Запись изменена. Специальность сотрудника неактивна с "' . Yii::$app->formatter->asDate($ar->employee_dateinactive) . '"';
                    $Employeelog->employee_fio = $ar->idperson->auth_user_fullname;
                    $Employeelog->dolzh_name = $ar->iddolzh->dolzh_name;
                    $Employeelog->podraz_name = $ar->idpodraz->podraz_name;
                    $Employeelog->build_name = $ar->isRelationPopulated('idbuild') ? $ar->idbuild['build_name'] : '';

                    $Employeelog->save(false);
                }

            Employee::updateAll(['employee_forinactive' => NULL], ['employee_forinactive' => 1]);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            Employee::updateAll(['employee_forinactive' => NULL], ['employee_forinactive' => 1]);
            throw new Exception($e->getMessage() . ' InactiveEmployee(), $filename = ' . $this->fileName);
        }
    }

    protected function afterIterateAll()
    {
        if (!YII_DEBUG)
            $this->inactiveEmployee();
    }

    private function getObserverByFieldName($FieldName)
    {
        foreach ($this->observers as $observer) {
            if ($observer->getFieldName === $FieldName)
                return $observer;
        }

        return false;
    }

    private function getEmployee()
    {
        $Result = Employee::find()
            ->joinWith('idperson')
            ->where(array_merge([
                'id_dolzh' => $this->getObserverByFieldName('dolzh_name')->getID(),
                'id_podraz' => $this->getObserverByFieldName('podraz_name')->getID(),
            ], empty($this->getObserverByFieldName('build_name')->getID()) ? [] : ['id_build' => $this->getObserverByFieldName('build_name')->getID()]))
            ->andFilterWhere(['like', 'auth_user_fullname', $this->_employeeFio, false])
            ->orderBy(['employee_id' => SORT_DESC])
            ->one();

        if (empty($Result))
            return false;
        else {
            $this->_employee = $Result;
            if (isset($this->_employee->scenarios()['import1c']))
                $this->_employee->scenario = 'import1c';
        }

        return true;
    }

    protected function createAuthuser()
    {
        if (empty($this->_errors)) {
            $AuthuserCount = Authuser::find()
                ->where(['like', 'auth_user_fullname', $this->_employeeFio, false])
                ->count();

            $this->_authUser = $AuthuserCount == 1 ? Authuser::find()
                ->where(['like', 'auth_user_fullname', $this->_employeeFio, false])
                ->one() : false;

            $this->_newAuthuser = false;
            if (empty($this->_authUser) || $AuthuserCount > 1) {
                $this->_newAuthuser = true;
                $this->_authUser = $this->generateAuthuser();
            }

            if (isset($this->_authUser->scenarios()['import1c']))
                $this->_authUser->scenario = 'import1c';

            if (!$this->_authUser->save()) {
                $this->_errors = $this->_authUser->getErrors();
            }
        }
        return $this;
    }

    protected function generateAuthuser()
    {
        $Authuser = new Authuser;
        $Authuser->auth_user_fullname = $this->_employeeFio;
        $Authuser->auth_user_login = $this->createLogin($this->_employeeFio);
        $Authuser->auth_user_password = Yii::$app->getSecurity()->generatePasswordHash('11111111');
        return $Authuser;
    }

    protected function createProfile(EmployeeParseObject $employeeParseObject)
    {
        if (empty($this->_errors)) {
            $this->_profile = Profile::findOne($this->_authUser->primaryKey);
            if (!$this->_profile)
                $this->_profile = new Profile();

            $this->_profile->profile_id = $this->_authUser->primaryKey;
            $this->_profile->profile_dr = $employeeParseObject->profile_dr;
            $this->_profile->profile_pol = $employeeParseObject->profile_pol;
            $this->_profile->profile_inn = $employeeParseObject->profile_inn;
            $this->_profile->profile_snils = $employeeParseObject->profile_snils;
            $this->_profile->profile_address = $employeeParseObject->profile_address;

            if (!$this->_profile->save())
                $this->_errors = $this->_profile->getErrors();
        }

        return $this;
    }

    protected function createEmployee()
    {
        if (empty($this->_errors)) {
            $this->_employee = new Employee;
            $this->_employee->attributes = [
                'id_dolzh' => $this->getObserverByFieldName('dolzh_name')->getID(),
                'id_podraz' => $this->getObserverByFieldName('podraz_name')->getID(),
                'id_build' => $this->getObserverByFieldName('build_name')->getID(),
                'employee_forinactive' => 1,
            ];

            if (isset($this->_employee->scenarios()['import1c']))
                $this->_employee->scenario = 'import1c';


            $this->_employee->id_person = $this->_authUser->primaryKey;
            if ($this->_employee->save()) {
                $this->_newAuthuser ? $this->logReport->logreport_additions++ : $this->logReport->logreport_updates++;
            } else
                $this->_errors = $this->_employee->getErrors();
        }

        return $this;
    }

    protected function mishanya()
    {
        if ($this->_mishanya < 3) {
            $dr = Yii::$app->formatter->asDate($this->_profile->profile_dr);
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

            if ($this->_profile->profile_pol == 'Ж' && $diff->y <= 35) {
                $this->_mishanya++;
                Yii::$app->mailer->compose('/site/misha', [
                    'fio' => $this->_authUser->auth_user_fullname,
                    'dr' => $dr,
                    'vozrast' => $diff->y,
                    'dolzh' => $this->getObserverByFieldName('dolzh_name')->getValue(),
                    'podraz' => $this->getObserverByFieldName('podraz_name')->getValue(),
                    'build' => $this->getObserverByFieldName('build_name')->getValue(),
                    'address' => $this->_profile->profile_address,
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
     * Фукния создает Логин пользователя на основе полного ФИО.
     * Например, $Fullname = 'Иванов Петр Сергеевич' выведет 'IvanovPS'.
     * Если логин 'IvanovPS' существует, то метод добавит количество совпадающих логинов в конце результата, т.е. IvanovPS1, IvanovPS2, и т.д.
     * @param $Fullname ФИО пользователя полностью.
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

    private function hasEmployeeInactiveByPerson()
    {
        $countEmployee = Employee::find()
            ->andWhere([
                'id_person' => $this->_employee->id_person,
            ])
            ->count();

        return Employee::find()
            ->andWhere([
                'id_person' => $this->_employee->id_person,
            ])
            ->andWhere('not employee_dateinactive is null')
            ->count() == $countEmployee;
    }

    private function getErrors()
    {
        return $this->_errors;
    }

    protected function ProcessItem($String)
    {
        preg_match(self::Pattern, $String, $Matches);

        if ($Matches[0] !== NULL) {

            $ImportLog = ImportLog::begin($this, new Employeelog);

            $this->_employeeObj = EmployeeParseFactory::employee($String)->create();

            $this->_employeeFio = $this->_employeeObj->auth_user_fullname;

            $this->notify();

            if ($this->getEmployee()) {
                $this->_authUser = Authuser::findOne($this->_employee->id_person);
                if ($this->_employee->employee_importdo === 1) {

                    if ($this->hasEmployeeInactiveByPerson()) {
                        $ImportLog->setup(ImportLog::CHANGE, NULL, 'Очищена дата неактивности специальности "' . Yii::$app->formatter->asDate($this->_employee->employee_dateinactive) . '"');
                        $this->_employee->employee_dateinactive = null;
                    }

                    $this->_employee->employee_forinactive = 1;
                    $this->_employee->save(false);

                    $ImportLog->setup(ImportLog::ADD_ERROR, $this->createProfile($this->_employeeObj), 'Ошибка при добавлении записи профиля пользователя');
                }
            } else {
                $AR_Errors = $this->createAuthuser()->createProfile($this->_employeeObj)->createEmployee()->getErrors();

                if (!$AR_Errors && $this->_newAuthuser && !YII_DEBUG)
                    $this->mishanya();
                else
                    $ImportLog->setup(ImportLog::ADD_ERROR, $AR_Errors);
            }

            $ImportLog->end();
        }
    }

    /**
     * Attach an SplObserver
     * @link http://php.net/manual/en/splsubject.attach.php
     * @param SplObserver $observer <p>
     * The <b>SplObserver</b> to attach.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function attach(SplObserver $observer)
    {
        $this->observers[] = $observer;
    }

    /**
     * Detach an observer
     * @link http://php.net/manual/en/splsubject.detach.php
     * @param SplObserver $observer <p>
     * The <b>SplObserver</b> to detach.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function detach(SplObserver $observer)
    {
        $key = array_search($observer, $this->observers, true);
        if (false !== $key) {
            unset($this->observers[$key]);
        }
    }

    /**
     * Notify an observer
     * @link http://php.net/manual/en/splsubject.notify.php
     * @return void
     * @since 5.1.0
     */
    public function notify()
    {
        foreach ($this->observers as $value) {
            $value->update($this);
        }
    }
}