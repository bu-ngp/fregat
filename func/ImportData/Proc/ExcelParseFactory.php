<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 28.10.2016
 * Time: 8:22
 */

namespace app\func\ImportData\Proc;


use app\models\Fregat\Import\Importconfig;

abstract class ExcelParseFactory
{
    private static $_instance;
    private $_rowExcel;
    private $_importconfig;

    private function __construct($RowExcel)
    {
        $this->setRowExcel($RowExcel);
    }

    /**
     * @return string
     */
    public function getRowExcel()
    {
        return $this->_rowExcel;
    }

    /**
     * @param string $rowExcel
     */
    public function setRowExcel($rowExcel)
    {
        $this->_rowExcel = $rowExcel;
    }

    /**
     * @return Importconfig
     */
    public function getImportconfig()
    {
        return $this->_importconfig;
    }

    /**
     * @param Importconfig $importconfig
     */
    private function setImportconfig(Importconfig $importconfig)
    {
        $this->_importconfig = $importconfig;
    }

    public function instanceAssign()
    {
        return !is_null(self::$_instance);
    }

    public static function material(Importconfig $importconfig, $RowExcel)
    {
        if (empty($RowExcel) || !is_array($RowExcel))
            throw new \Exception('$RowExcel должен быть массивом');

        self::$_instance = new static($RowExcel);

        self::$_instance->setImportconfig($importconfig);

        return self::$_instance;
    }

    abstract public function create();

}