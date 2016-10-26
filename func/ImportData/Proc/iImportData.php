<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.10.2016
 * Time: 14:31
 */

namespace app\func\ImportData\Proc;

use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Import\Logreport;

/**
 * Interface iImportData
 * @package app\func\ImportData\Proc
 */
interface iImportData
{
    /**
     * @return static
     */
    public static function init();

    /**
     * @return Importconfig
     */
    public function getImportConfig();

    /**
     * @return Logreport
     */
    public function getLogReport();

    /**
     * @return bool
     */
    public function getDebug();
}