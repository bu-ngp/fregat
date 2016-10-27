<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.10.2016
 * Time: 10:59
 */

namespace app\func\ImportData\Proc;


use SplSubject;

/**
 * Interface iDataFilter
 * @package app\func\ImportData\Proc
 */
interface iFilterObserver extends \SplObserver
{
    /**
     * @return integer
     */
    public function getID();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $Value
     * @return mixed
     */
    public function setValue($Value);

    /**
     * @return string
     */
    public function getFieldName();

    /**
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject);
}