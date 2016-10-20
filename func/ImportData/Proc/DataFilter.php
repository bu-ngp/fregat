<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 20.10.2016
 * Time: 14:25
 */

namespace app\func\ImportData\Proc;


use Exception;
use yii\db\ActiveRecord;

class DataFilter
{
    private $_ID;
    private $_fieldName;
    private $_activeRecord;

    public function __construct($fieldName, ActiveRecord $activeRecord)
    {
        if (!empty($fieldName) && !is_string($fieldName))
            throw new Exception('Пустое значение параметра $fieldName');

        $this->_fieldName = $fieldName;
        $this->_activeRecord = $activeRecord;
    }

    public function installValue($fieldValue)
    {
        if (!empty($fieldValue) && !is_string($fieldValue))
            throw new Exception('Пустое значение параметра $fieldValue');

        $activeRecord = $this->_activeRecord;

        $currentAR = $activeRecord::find()->andWhere(['like', $this->_fieldName, $fieldValue, false])->one();

        if (empty($currentAR)) {
            $AR = new $this->_activeRecord;
            $AR->{$this->_fieldName} = $fieldValue;
            $this->_ID = $AR->Save() ? $AR->primaryKey : NULL;
        } else
            $this->_ID = $currentAR->primaryKey;
    }

    public function getID()
    {
        return $this->_ID;
    }

}