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

/**
 * Class DataFilter
 * @package app\func\ImportData\Proc
 */
class DataFilter implements iDataFilter
{
    /**
     * @var integer
     */
    private $_ID;
    /**
     * @var string
     */
    private $_fieldName;
    /**
     * @var ActiveRecord
     */
    private $_activeRecord;
    /**
     * @var mixed
     */
    private $_fieldNameValue;

    /**
     * DataFilter constructor.
     * @param $fieldName
     * @param ActiveRecord $activeRecord
     * @throws Exception
     */
    public function __construct($fieldName, ActiveRecord $activeRecord)
    {
        if (!empty($fieldName) && !is_string($fieldName))
            throw new Exception('Пустое значение параметра $fieldName');

        $this->_fieldName = $fieldName;
        $this->_activeRecord = $activeRecord;
    }

    /* Getters/Setters */

    /**
     * @return integer
     */
    public function getID()
    {
        return $this->_ID;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_fieldNameValue;
    }

    /**
     * @param mixed $Value
     */
    public function setValue($Value)
    {
        $this->_fieldNameValue = $Value;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->_fieldName;
    }

    /**
     * @return ActiveRecord
     */
    private function getActiveRecord()
    {
        return $this->_activeRecord;
    }

    /**
     * @param integer $ID
     */
    private function setID($ID)
    {
        $this->_ID = $ID;
    }

    /* Вспомагательные */

    /**
     * @return ActiveRecord
     */
    private function newActiveRecord()
    {
        return new $this->_activeRecord;
    }

    /**
     *
     */
    private function reset()
    {
        $this->_ID = NULL;
    }

    /**
     * @param mixed $Value
     * @return mixed
     */
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
        $this->reset();

        if (empty($this->getValue()))
            return;

        $fieldValue = $this->beforeProcess($this->getValue());

        $activeRecord = $this->getActiveRecord();

        $currentAR = $activeRecord::find()->andWhere(['like', $this->getFieldName(), $fieldValue, false])->one();

        if (empty($currentAR)) {
            $AR = $this->newActiveRecord();
            $AR->{$this->getFieldName()} = $fieldValue;
            if ($AR->Save()) {
                $this->setID($AR->primaryKey);
            }
        } else {
            $this->setID($currentAR->primaryKey);
        }
    }
}