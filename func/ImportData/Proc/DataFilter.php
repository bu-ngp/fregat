<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 20.10.2016
 * Time: 14:25
 */

namespace app\func\ImportData\Proc;


use Exception;
use SplSubject;
use yii\db\ActiveRecord;

class DataFilter implements \SplObserver
{
    private $_ID;
    private $_fieldName;
    private $_activeRecord;
    private $_fieldNameValue;


    public function __construct($fieldName, ActiveRecord $activeRecord)
    {
        if (!empty($fieldName) && !is_string($fieldName))
            throw new Exception('Пустое значение параметра $fieldName');

        $this->_fieldName = $fieldName;
        $this->_activeRecord = $activeRecord;
    }

    public function getID()
    {
        return $this->_ID;
    }

    public function getValue()
    {
        return $this->_fieldNameValue;
    }

    public function getFieldName()
    {
        return $this->_fieldName;
    }

    protected function beforeProcess($Value)
    {
        return $Value;
    }

    /**
     * Receive update from subject
     * @link http://php.net/manual/en/splobserver.update.php
     * @param SplSubject $subject <p>
     * The <b>SplSubject</b> notifying the observer of an update.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function update(SplSubject $subject)
    {
        $this->_ID = NULL;
        $this->_fieldNameValue = NULL;
        $fieldValue = $subject->_employeeObj->prop($this->_fieldName);
        if (!empty($fieldValue) && !is_string($fieldValue))
            throw new Exception('Пустое значение параметра $fieldValue');

        $fieldValue = $this->beforeProcess($fieldValue);

        $activeRecord = $this->_activeRecord;

        $currentAR = $activeRecord::find()->andWhere(['like', $this->_fieldName, $fieldValue, false])->one();

        if (empty($currentAR)) {
            $AR = new $this->_activeRecord;
            $AR->{$this->_fieldName} = $fieldValue;
            if ($AR->Save()) {
                $this->_ID = $AR->primaryKey;
                $this->_fieldNameValue = $AR->{$this->_fieldName};
            }
        } else {
            $this->_ID = $currentAR->primaryKey;
            $this->_fieldNameValue = $currentAR->{$this->_fieldName};
        }

    }
}