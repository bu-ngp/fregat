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
     * @var OsnovParseObject
     */
    private $_materialObj;
    private $_employeeObj;

    /**
     * @return OsnovParseObject
     */
    public function getMaterialParseObject()
    {
        return $this->_materialObj;
    }

    /**
     * @return EmployeeParseObject
     */
    public function getEmployeeParseObject()
    {
        return $this->_employeeObj;
    }

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
     * @param array $rowExcel
     */
    protected function processItem($rowExcel)
    {
        $MaterialObj = OsnovParseFactory::material($this->getImportConfig(), $rowExcel)->create();

        if ($MaterialObj) {
            $this->installParseObject($MaterialObj);

            $this->notify();


        }
    }

    private function applyValuesEmployeeLog()
    {
        return [
            'employee_fio' => $this->getEmployeeParseObject()->auth_user_fullname,
            'dolzh_name' => $this->getObserverByFieldName('dolzh_name')->getValue(),
            'podraz_name' => $this->getObserverByFieldName('podraz_name')->getValue(),
            'build_name' => $this->getObserverByFieldName('build_name')->getValue(),
        ];
    }

    private function material_invFilter($Value)
    {
        return empty($Value) || mb_strtolower($Value, 'UTF-8') === 'null' ? $this->getMaterialParseObject()->material_1c : $Value;
    }

    private function applyValuesMatLog()
    {
        return [
            'mattraffic_date' => $this->getMaterialParseObject()->mattraffic_date,
            'material_tip' => 1,
            'material_1c' => $this->getMaterialParseObject()->material_1c,
            'material_inv' => $this->material_invFilter($this->getMaterialParseObject()->material_inv),
            'material_name' => $this->getMaterialParseObject()->material_name1c,
            'material_name1c' => $this->getMaterialParseObject()->material_name1c,
            'material_price' => $this->getMaterialParseObject()->material_price,
            'material_serial' => $this->getMaterialParseObject()->material_serial,
            'material_release' => $this->getMaterialParseObject()->material_release,
            'material_status' => $this->getMaterialParseObject()->material_status,
            'schetuchet_kod' => $this->getObserverByFieldName('schetuchet_kod')->getValue(),
            'schetuchet_name' => $this->getMaterialParseObject()->schetuchet_name,
        ];
    }

    /**
     * @param OsnovParseObject $ParseObject
     */
    public function installParseObject(OsnovParseObject $ParseObject)
    {
        $this->_materialObj = $ParseObject;

        foreach ($this->getObservers() as $value) {
            $value->setValue($ParseObject->prop($value->getFieldName()));
        }
    }
}