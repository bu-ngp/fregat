<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 19.10.2016
 * Time: 10:34
 */

namespace app\func\ImportData;


use app\func\ImportData\Exec\DolzhFilter;
use app\func\ImportData\Exec\Employees;
use app\func\ImportData\Proc\DataFilter;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Import\Logreport;
use app\models\Fregat\Podraz;

class ImportData
{
    private static $_instance;
    private $_importConfig;
    private $_logReport;
    private $_debug;

    private function __construct()
    {
        $this->setImportConfig(Importconfig::findOne(1));
        if (empty($this->getImportConfig())) {
            echo 'Не найдена конфигурация в БД';
            return false;
        }

        $this->setLogReport(new Logreport());
        $this->getLogReport()->logreport_date = date('Y-m-d');
        $this->setDebug(YII_DEBUG);

        DeleteOldReports::Init()->Execute();
    }


    /**
     * @return Importconfig
     */
    public function getImportConfig()
    {
        return $this->_importConfig;
    }

    /**
     * @param Importconfig $importConfig
     */
    private function setImportConfig(Importconfig $importConfig)
    {
        $this->_importConfig = $importConfig;
    }


    /**
     * @return Logreport
     */
    public function getLogReport()
    {
        return $this->_logReport;
    }

    /**
     * @param Logreport $logReport
     */
    private function setLogReport(Logreport $logReport)
    {
        $this->_logReport = $logReport;
    }

    /**
     * @return mixed
     */
    public function getDebug()
    {
        return $this->_debug;
    }

    /**
     * @param mixed $debug
     */
    private function setDebug($debug)
    {
        $this->_debug = $debug;
    }

    public static function init()
    {
        self::$_instance = new self();

        return self::$_instance;
    }

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
        $importEmployee->iterate();

    }


}