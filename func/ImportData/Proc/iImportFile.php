<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.10.2016
 * Time: 14:33
 */

namespace app\func\ImportData\Proc;


use app\models\Fregat\Import\Logreport;

/**
 * Interface iImportFile
 * @package app\func\ImportData\Proc
 */
interface iImportFile extends \SplSubject
{
    /**
     * @return Logreport
     */
    public function getLogReport();

    /**
     * @return string
     */
    public function getFileName();

    /**
     * @return bool|string
     */
    public function getFileLastDate();

    /**
     * @return integer
     */
    public function getRow();

    /**
     * @return bool
     */
    public function getDebug();

    /**
     * @param bool $debug
     */
    public function setDebug($debug);
}