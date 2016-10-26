<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.10.2016
 * Time: 13:05
 */

namespace app\func\ImportData\Proc;


/**
 * Class ParseObject
 * @package app\func\ImportData\Proc
 */
class ParseObject implements iParseObject
{
    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function prop($name)
    {
        if (property_exists(static::class, $name))
            return $this->$name;
        else
            throw  new \Exception('Свойство ' . $name . ' не существует');
    }
}