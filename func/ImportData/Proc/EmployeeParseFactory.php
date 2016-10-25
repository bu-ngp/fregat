<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 24.10.2016
 * Time: 10:27
 */

namespace app\func\ImportData\Proc;


class EmployeeParseFactory
{
    const BasePattern = '/^(.*?)\|(Поликлиника №\s?[1,2,3] )?(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|/ui';
    const BuildPattern = '/(^Поликлиника №)\s?([1,2,3])\s?$/ui';
    const PodrazPattern = '/^(.+) БУ "Нижневартовская городская поликлиника"$/ui';

    private static $_instance;
    private $_stringEmployee;

    private function __construct($StringEmployee)
    {
        $this->_stringEmployee = $StringEmployee;
    }

    public static function employee($StringEmployee)
    {
        if (empty($StringEmployee) || !is_string($StringEmployee))
            throw new \Exception('$StringEmployee должен быть строкой');

        self::$_instance = new self($StringEmployee);

        return self::$_instance;
    }

    public function create()
    {
        if (is_null(self::$_instance))
            throw new \Exception('Сотрудник не инициализирован');

        preg_match(self::BasePattern, $this->_stringEmployee, $Matches);

        if ($Matches[0] === NULL)
            throw new \Exception('Неверный формат строки');

        $EmployeeObj = new EmployeeParseObject();

        $EmployeeObj->auth_user_fullname = trim($Matches[1]);
        $EmployeeObj->dolzh_name = trim($Matches[4]);
        $EmployeeObj->podraz_name = preg_replace(self::PodrazPattern, '$1', trim($Matches[3]));
        $EmployeeObj->build_name = trim($Matches[3]) === 'Поликлиника профилактических осмотров' ? trim($Matches[3]) : preg_replace(self::BuildPattern, 'Взрослая $1$2', mb_strtolower(trim($Matches[2]), 'UTF-8'));
        $EmployeeObj->profile_dr = trim($Matches[16]);
        $EmployeeObj->profile_pol = trim($Matches[15]);
        $EmployeeObj->profile_inn = trim($Matches[11]);
        $EmployeeObj->profile_snils = trim($Matches[12]);
        $EmployeeObj->profile_address = trim($Matches[10]);

        return $EmployeeObj;
    }

}