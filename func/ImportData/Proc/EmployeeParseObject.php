<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 24.10.2016
 * Time: 10:27
 */

namespace app\func\ImportData\Proc;


class EmployeeParseObject
{
    public $auth_user_fullname;
    public $dolzh_name;
    public $podraz_name;
    public $build_name;
    public $profile_dr;
    public $profile_pol;
    public $profile_inn;
    public $profile_snils;
    public $profile_address;

    public function prop($name)
    {
        if (property_exists(self::class, $name))
            return $this->$name;
        else
            throw  new \Exception('Свойство ' . $name . ' не существует');
    }

}