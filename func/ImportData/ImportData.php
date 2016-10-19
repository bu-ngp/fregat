<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 19.10.2016
 * Time: 10:34
 */

namespace app\func\ImportData;


use app\func\ImportData\Exec\Employees;
use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Import\Logreport;

class ImportData
{
    private static $_instance;
    private $_importConfig;
    private $_logReport;
    private $_newEmployeeCount;
    private $_Debug;

    private function __construct()
    {
        $this->_importConfig = Importconfig::findOne(1);
        if (empty($this->_importConfig)) {
            echo 'Не найдена конфигурация в БД';
            return false;
        }

        $this->_logReport = new Logreport();
        $this->_logReport->logreport_date = date('Y-m-d');
        $this->_newEmployeeCount = 0;
        $this->_Debug = YII_DEBUG;

        DeleteOldReports::Init()->Execute();
    }

    public static function init()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function execute()
    {
        if (!$this->_importConfig->importconfig_do) {
            echo 'Импорт отключен';
            return false;
        }

        $importEmployee = new Employees($this->_importConfig, 'emp_filename', $this->_logReport);
        $importEmployee->iterate();

    }

}