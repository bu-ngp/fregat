<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 24.10.2016
 * Time: 14:28
 */

namespace app\func\ImportData\Proc;


use app\models\Fregat\Import\Employeelog;
use yii\db\ActiveRecord;

class ImportLog
{
    const ADD = 1;
    const CHANGE = 2;
    const ADD_ERROR = 3;
    const CHANGE_ERROR = 4;

    private static $_instance;
    private $_activeRecordLog;
    private $_importFile;
    private $_typeLog;

    private $_cType;
    private $_cFilename;
    private $_cFilelastdate;
    private $_cRownum;
    private $_cMessage;
    private $_saveDo;

    private function __construct(ImportFile $ImportFile, ActiveRecord $activeRecordLog)
    {
        $formName = strtolower($activeRecordLog->formName());
        $this->_cType = $formName . '_type';
        $this->_cFilename = $formName . '_filename';
        $this->_cFilelastdate = $formName . '_filelastdate';
        $this->_cRownum = $formName . '_rownum';
        $this->_cMessage = $formName . '_message';

        $this->_importFile = $ImportFile;

        $this->_activeRecordLog = $activeRecordLog;
        $this->_activeRecordLog->id_logreport = $ImportFile->logReport->primaryKey;
        $this->_activeRecordLog->{$this->_cType} = 1;
        $this->_activeRecordLog->{$this->_cFilename} = $ImportFile->fileName;
        $this->_activeRecordLog->{$this->_cFilelastdate} = $ImportFile->fileLastDate;
        $this->_activeRecordLog->{$this->_cRownum} = $ImportFile->row;
        $this->_activeRecordLog->{$this->_cMessage} = 'Запись добавлена.';
        $this->_saveDo = false;
    }

    public static function begin(ImportFile $ImportFile, ActiveRecord $activeRecordLog)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($ImportFile, $activeRecordLog);
        }
        return self::$_instance;
    }

    private function message()
    {
        switch ($this->_typeLog) {
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

    private function counter()
    {
        $this->_importFile->logReport->logreport_amount++;
        switch ($this->_typeLog) {
            case 1:
                $this->_importFile->logReport->logreport_additions++;
                break;
            case 2:
                $this->_importFile->logReport->logreport_updates++;
                break;
            case 3:
                $this->_importFile->logReport->logreport_errors++;
                break;
            case 4:
                $this->_importFile->logReport->logreport_errors++;
                break;
        }
    }

    public function setup($TypeLog, ActiveRecord $activeRecordErrors = NULL, $Message = '')
    {
        $this->_typeLog = $TypeLog;
        $this->_activeRecordLog->{$this->_cType} = $TypeLog;
        $this->_activeRecordLog->{$this->_cMessage} = $this->message() . (empty($Message) ? '' : ($Message . ' '));

        foreach ($activeRecordErrors as $fields)
            $this->_activeRecordLog->{$this->_cMessage} .= implode(' ', $fields) . ' ';

        $this->_saveDo = true;
    }

    public function end()
    {
        if ($this->_saveDo) {
            $this->counter();
            return $this->_activeRecordLog->save();
        }

        return false;
    }
}