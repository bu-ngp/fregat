<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 24.10.2016
 * Time: 14:28
 */

namespace app\func\ImportData\Proc;

use yii\db\ActiveRecord;

/**
 * Class ImportLog
 * @package app\func\ImportData\Proc
 */
class ImportLog implements iImportLog
{
    /**
     * @var static
     */
    private static $_instance;
    /**
     * @var ActiveRecord
     */
    private $_activeRecordLog;
    /**
     * @var ImportFile
     */
    private $_importFile;
    /**
     * @var integer
     */
    private $_typeLog;

    /**
     * @var string
     */
    private $_cType;
    /**
     * @var string
     */
    private $_cFilename;
    /**
     * @var string
     */
    private $_cFilelastdate;
    /**
     * @var string
     */
    private $_cRownum;
    /**
     * @var string
     */
    private $_cMessage;
    /**
     * @var bool
     */
    private $_saveDo = false;

    /**
     * ImportLog constructor.
     * @param iImportFile $ImportFile
     * @param ActiveRecord $activeRecordLog
     * @throws \Exception
     */
    private function __construct(iImportFile $ImportFile, ActiveRecord $activeRecordLog)
    {
        $formName = strtolower($activeRecordLog->formName());
        $this->setType($formName . '_type');
        $this->setFilename($formName . '_filename');
        $this->setFilelastdate($formName . '_filelastdate');
        $this->setRownum($formName . '_rownum');
        $this->setMessage($formName . '_message');

        $this->setImportFile($ImportFile);

        $this->setActiveRecordLog($activeRecordLog);

        if (!$this->getActiveRecordLog()->hasAttribute('id_logreport'))
            throw new \Exception('Отсутствует свойство id_logreport');

        $this->getActiveRecordLog()->id_logreport = $ImportFile->getLogReport()->primaryKey;
        $this->getActiveRecordLog()->{$this->getType()} = 1;
        $this->getActiveRecordLog()->{$this->getFilename()} = $ImportFile->getFileName();
        $this->getActiveRecordLog()->{$this->getFilelastdate()} = $ImportFile->getFileLastDate();
        $this->getActiveRecordLog()->{$this->getRownum()} = $ImportFile->getRow();
        $this->getActiveRecordLog()->{$this->getMessage()} = 'Запись добавлена.';
    }

    /* Getters/Setters */

    /**
     * @return string
     */
    private function getType()
    {
        return $this->_cType;
    }

    /**
     * @param string $cType
     */
    private function setType($cType)
    {
        $this->_cType = $cType;
    }

    /**
     * @return string
     */
    private function getFilename()
    {
        return $this->_cFilename;
    }

    /**
     * @param string $cFilename
     */
    private function setFilename($cFilename)
    {
        $this->_cFilename = $cFilename;
    }

    /**
     * @return string
     */
    private function getFilelastdate()
    {
        return $this->_cFilelastdate;
    }

    /**
     * @param string $cFilelastdate
     */
    private function setFilelastdate($cFilelastdate)
    {
        $this->_cFilelastdate = $cFilelastdate;
    }

    /**
     * @return integer
     */
    private function getRownum()
    {
        return $this->_cRownum;
    }

    /**
     * @param integer $cRownum
     */
    private function setRownum($cRownum)
    {
        $this->_cRownum = $cRownum;
    }

    /**
     * @return string
     */
    private function getMessage()
    {
        return $this->_cMessage;
    }

    /**
     * @param string $cMessage
     */
    private function setMessage($cMessage)
    {
        $this->_cMessage = $cMessage;
    }

    /**
     * @return iImportFile
     */
    private function getImportFile()
    {
        return $this->_importFile;
    }

    /**
     * @param iImportFile $importFile
     */
    private function setImportFile(iImportFile $importFile)
    {
        $this->_importFile = $importFile;
    }

    /**
     * @return ActiveRecord
     */
    private function getActiveRecordLog()
    {
        return $this->_activeRecordLog;
    }

    /**
     * @param ActiveRecord $activeRecordLog
     */
    private function setActiveRecordLog(ActiveRecord $activeRecordLog)
    {
        $this->_activeRecordLog = $activeRecordLog;
    }

    /**
     * @return integer
     */
    private function getTypeLog()
    {
        return $this->_typeLog;
    }

    /**
     * @param integer $typeLog
     */
    private function setTypeLog($typeLog)
    {
        $this->_typeLog = $typeLog;
    }

    /* Вспомогательные */

    /**
     *
     */
    private function saveDo()
    {
        $this->_saveDo = true;
    }

    /**
     * @return bool
     */
    private function isSaveDo()
    {
        return $this->_saveDo;
    }

    /**
     * @return string
     */
    private function message()
    {
        switch ($this->getTypeLog()) {
            case 1:
                return 'Запись добавлена. ';
            case 2:
                return 'Запись изменена. ';
            case 3:
                return 'Ошибка при добавлении записи: ';
            case 4:
                return 'Ошибка при изменении записи: ';
            default:
                return '';
        }
    }

    /**
     *
     */
    private function counter()
    {
        $this->getImportFile()->getLogReport()->logreport_amount++;
        switch ($this->getType()) {
            case 1:
                $this->getImportFile()->getLogReport()->logreport_additions++;
                break;
            case 2:
                $this->getImportFile()->getLogReport()->logreport_updates++;
                break;
            case 3:
                $this->getImportFile()->getLogReport()->logreport_errors++;
                break;
            case 4:
                $this->getImportFile()->getLogReport()->logreport_errors++;
                break;
        }
    }

    /**
     * @param array $ApplyValues
     * @return bool
     */
    private function ApplyValuesLog(array $ApplyValues = [])
    {
        if (!empty($ApplyValues) && is_array($ApplyValues) && $this->_saveDo) {
            foreach ($ApplyValues as $FieldNameARLog => $Value) {
                $this->getActiveRecordLog()->$FieldNameARLog = $Value;
            }
            return true;
        }
        return false;
    }

    /**
     * @param iImportFile $ImportFile
     * @param ActiveRecord $activeRecordLog
     * @return ImportLog
     */
    public static function begin(iImportFile $ImportFile, ActiveRecord $activeRecordLog)
    {
        self::$_instance = new self($ImportFile, $activeRecordLog);
        return self::$_instance;
    }

    /**
     * @param integer $TypeLog
     * @param array $activeRecordErrors
     * @param string $Message
     */
    public function setup($TypeLog, array $activeRecordErrors = [], $Message = '')
    {
        $this->setTypeLog($TypeLog);
        $this->getActiveRecordLog()->{$this->getType()} = $TypeLog;
        $this->getActiveRecordLog()->{$this->getMessage()} = $this->message() . (empty($Message) ? '' : ($Message . ' '));

        foreach ($activeRecordErrors as $fields)
            $this->getActiveRecordLog()->{$this->getMessage()} .= implode(' ', $fields) . ' ';

        $this->saveDo();
    }

    /**
     * @param array $ApplyValuesLog
     * @return bool
     */
    public function end(array $ApplyValuesLog)
    {
        if ($this->isSaveDo()) {
            $this->counter();
            if (!empty($ApplyValuesLog) && is_array($ApplyValuesLog))
                $this->ApplyValuesLog($ApplyValuesLog);
            return $this->getActiveRecordLog()->save();
        }

        return false;
    }

}