<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.10.2016
 * Time: 14:40
 */

namespace app\func\ImportData\Proc;


use yii\db\ActiveRecord;

/**
 * Interface iImportLog
 * @package app\func\ImportData\Proc
 */
interface iImportLog
{
    /**
     *
     */
    const ADD = 1;
    /**
     *
     */
    const CHANGE = 2;
    /**
     *
     */
    const ADD_ERROR = 3;
    /**
     *
     */
    const CHANGE_ERROR = 4;

    /**
     * @param iImportFile $ImportFile
     * @param ActiveRecord $activeRecordLog
     * @return ImportLog
     */
    public static function begin(iImportFile $ImportFile, ActiveRecord $activeRecordLog);

    /**
     * @param $TypeLog
     * @param array $activeRecordErrors
     * @param string $Message
     * @return mixed
     */
    public function setup($TypeLog, array $activeRecordErrors = [], $Message = '');

    /**
     * @param array $ApplyValuesLog
     * @return bool
     */
    public function end(array $ApplyValuesLog);
}