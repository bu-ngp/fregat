<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.10.2016
 * Time: 14:48
 */

namespace app\func\ImportData\Proc;


interface iParseObject
{
    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function prop($name);
}