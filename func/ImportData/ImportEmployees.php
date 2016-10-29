<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.10.2016
 * Time: 8:58
 */

namespace app\func\ImportData;

use app\func\ImportData\Exec\DolzhFilter;
use app\func\ImportData\Exec\Employees;
use app\func\ImportData\Exec\OsnovMaterial;
use app\func\ImportData\Proc\DataFilter;
use app\func\ImportData\Proc\ImportData;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Podraz;

/**
 * Class ImportMaterials
 * @package app\func\ImportData
 */
class ImportEmployees extends ImportData
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function execute()
    {
        if (!$this->getImportConfig()->importconfig_do) {
            echo 'Импорт отключен';
            return false;
        }

        $importEmployee = new Employees($this->getImportConfig(), 'emp_filename', $this->getLogReport());
        $importEmployee->attach(new DolzhFilter('dolzh_name', new Dolzh));
        $importEmployee->attach(new DataFilter('podraz_name', new Podraz));
        $importEmployee->attach(new DataFilter('build_name', new Build));
        $importEmployee->setDebug($this->getDebug());
        $importEmployee->iterate();

        return true;
    }
}