<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 20.10.2016
 * Time: 14:25
 */

namespace app\func\ImportData\Proc;

use SplSubject;

/**
 * Class DataFilter
 * @package app\func\ImportData\Proc
 */
class DataFilter extends FilterObserver
{

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