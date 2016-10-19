<?php

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 19.10.2016
 * Time: 13:57
 */

namespace app\func\ImportData\Proc;

use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Import\Logreport;
use Exception;

abstract class ImportFile
{
    protected $importConfig;
    protected $fileName;
    protected $fileLastDate;
    protected $fieldNameDB;
    protected $typeFile;
    protected $importFileLastDateFieldDB;
    protected $logReport;
    protected $startTime;
    protected $endTime;

    public function __construct(Importconfig $importConfig, $fieldNameDB, Logreport $logReport)
    {
        $this->logReport = $logReport;
        $this->fieldNameDB = $fieldNameDB;
        $this->setTypeFile();
        $this->importConfig = $importConfig;
        $fileName = dirname($_SERVER['SCRIPT_FILENAME']) . '/imp/' . $this->importConfig[$fieldNameDB] . '.' . $this->typeFile;

        if (file_exists($fileName))
            throw new Exception('Файл не существует. ' . $fileName);

        $this->fileName = $fileName;
        $this->fileLastDate = date("Y-m-d H:i:s", filemtime($this->fileName));
    }

    protected function setTypeFile()
    {
        switch ($this->fieldNameDB) {
            case 'emp_filename':
                return $this->typeFile = 'txt';
            case 'os_filename':
                return $this->typeFile = 'xlsx';
            case 'mat_filename':
                return $this->typeFile = 'xlsx';
            case 'gu_filename':
                return $this->typeFile = 'xlsx';
        }

        return false;
    }

    protected function getMaxFileLastDate()
    {
        switch ($this->fieldNameDB) {
            case 'emp_filename':
                return $this->importFileLastDateFieldDB = 'logreport_employeelastdate';
            case 'os_filename':
                return $this->importFileLastDateFieldDB = 'logreport_oslastdate';
            case 'mat_filename':
                return $this->importFileLastDateFieldDB = 'logreport_matlastdate';
            case 'gu_filename':
                return $this->importFileLastDateFieldDB = 'logreport_gulastdate';
        }
        return false;
    }

    public function isChanged()
    {
        $Field = $this->getMaxFileLastDate();

        if (!$Field)
            return false;

        $fileLastDateFromDB = Logreport::find()->max($Field);

        if (empty($fileLastDateFromDB))
            return false;

        return strtotime($this->fileLastDate) > strtotime($fileLastDateFromDB);
    }

    protected function setLastDateImportFileToDB()
    {
        $this->logReport->{$this->importFileLastDateFieldDB} = $this->fileLastDate;
    }

    abstract public function iterate();

}