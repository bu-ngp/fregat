<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 24.10.2016
 * Time: 10:27
 */

namespace app\func\ImportData\Exec;
use app\func\ImportData\Proc\ParseObject;


/**
 * Class EmployeeParseObject
 * @package app\func\ImportData\Proc
 */
class EmployeeParseObject extends ParseObject
{
    /**
     * @var string
     */
    public $auth_user_fullname;
    /**
     * @var string
     */
    public $dolzh_name;
    /**
     * @var string
     */
    public $podraz_name;
    /**
     * @var string
     */
    public $build_name;
    /**
     * @var string
     */
    public $profile_dr;
    /**
     * @var integer
     */
    public $profile_pol;
    /**
     * @var string
     */
    public $profile_inn;
    /**
     * @var string
     */
    public $profile_snils;
    /**
     * @var string
     */
    public $profile_address;
}