<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 19.10.2016
 * Time: 10:34
 */

namespace app\func\ImportData\Proc;

use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Import\Logreport;

/**
 * Class ImportData
 * @package app\func\ImportData\Proc
 */
abstract class ImportData implements iImportData
{
    /**
     * @var static
     */
    protected static $_instance;
    /**
     * @var Importconfig
     */
    private $_importConfig;
    /**
     * @var Logreport
     */
    private $_logReport;
    /**
     * @var bool
     */
    private $_debug;

    /**
     * ImportData constructor.
     */
    public function __construct()
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
     * @return static
     */
    public static function init()
    {
        self::$_instance = new static();

        return self::$_instance;
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
     * @return bool
     */
    public function getDebug()
    {
        return $this->_debug;
    }

    /**
     * @param bool $debug
     */
    private function setDebug($debug)
    {
        $this->_debug = $debug;
    }

    /**
     * @return bool
     */
    abstract public function execute();

}