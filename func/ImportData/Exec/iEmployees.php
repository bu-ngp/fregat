<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.10.2016
 * Time: 11:11
 */

namespace app\func\ImportData\Exec;


use app\func\ImportData\Proc\EmployeeParseObject;

interface iEmployees
{
    /**
     * @return EmployeeParseObject
     */
    public function getEmployeeParseObject();
}