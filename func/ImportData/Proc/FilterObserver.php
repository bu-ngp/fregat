<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 27.10.2016
 * Time: 10:24
 */

namespace app\func\ImportData\Proc;

use Exception;
use SplSubject;
use yii\db\ActiveRecord;

class FilterObserver implements iFilterObserver
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
    protected function getActiveRecord()
    {
        return $this->_activeRecord;
    }

    /**
     * @param integer $ID
     */
    protected function setID($ID)
    {
        $this->_ID = $ID;
    }

    /* Вспомагательные */

    /**
     * @return ActiveRecord
     */
    protected function newActiveRecord()
    {
        return new $this->_activeRecord;
    }

    /**
     *
     */
    protected function reset()
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

    }

}