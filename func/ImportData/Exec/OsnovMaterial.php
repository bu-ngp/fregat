<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 27.10.2016
 * Time: 13:19
 */

namespace app\func\ImportData\Exec;


use app\func\ImportData\Proc\ImportFromTextFile;
use app\func\ImportData\Proc\ImportLog;
use app\models\Fregat\Import\Employeelog;
use app\models\Fregat\Import\Matlog;

class OsnovMaterial extends ImportFromTextFile
{

    /**
     *
     */
    protected function beforeIterateItem()
    {
        $this->setImportLog(new ImportLog($this, new Employeelog()));
        $this->setImportLog(new ImportLog($this, new Matlog()));
    }

    /**
     *
     */
    protected function afterIterateItem()
    {
        $this->getImportLog('Employeelog')->end($this->applyValuesEmployeeLog());
        $this->getImportLog('Matlog')->end($this->applyValuesMatLog());
    }

    /**
     *
     */
    protected function afterIterateAll()
    {
        // TODO: Implement afterIterateAll() method.
    }

    /**
     * @param string $String
     */
    protected function processItem($String)
    {
        // TODO: Implement processItem() method.
    }

    private function applyValuesEmployeeLog()
    {
        return [];
    }

    private function applyValuesMatLog()
    {
        return [];
    }
}