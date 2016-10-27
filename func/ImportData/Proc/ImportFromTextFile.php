<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 19.10.2016
 * Time: 15:02
 */

namespace app\func\ImportData\Proc;


use Exception;
use Yii;

/**
 * Class ImportFromTextFile
 * @package app\func\ImportData\Proc
 */
abstract class ImportFromTextFile extends ImportFile
{

    /**
     * @param string $String
     * @return string
     */
    private function removeUTF8BOM($String)
    {
        return str_replace("\xEF\xBB\xBF", '', $String);
    }

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function iterate()
    {
        if ($this->isChanged()) {
            $this->startTime = microtime(true);

            $this->getLogReport()->save();

            $this->setRow(0);
            $handle = @fopen($this->getFileName(), "r");

            if ($handle) {
                $firstRow = true;
                while (($subject = fgets($handle, 4096)) !== false) {
                    if ($firstRow) {
                        $subject = $this->removeUTF8BOM($subject);
                        $firstRow = false;
                    }
                    
                    $this->setRow($this->getRow() + 1);
                    $transaction = Yii::$app->db->beginTransaction();
                    try {

                        $this->beforeIterateItem();

                        $this->processItem($subject);

                        $this->afterIterateItem();

                        $transaction->commit();

                    } catch (Exception $e) {
                        $transaction->rollBack();
                        throw new Exception($e->getMessage() . ' $i = ' . $this->getRow() . '; $filename = ' . $this->getFileName());
                    }
                }
                fclose($handle);

                $this->afterIterateAll();
            }

            $this->getLogReport()->logreport_amount += $this->getRow();
            $this->endTime = microtime(true);
            $this->getLogReport()->logreport_executetime = gmdate('H:i:s', $this->endTime - $this->startTime);
            $this->getLogReport()->logreport_memoryused = memory_get_usage(true);
            $this->getLogReport()->save();

            echo 'ImportDo success<BR>';
            echo 'Использовано памяти: ' . Yii::$app->formatter->asShortSize(memory_get_usage(true)) . '; Время выполнения: ' . gmdate('H:i:s', $this->endTime - $this->startTime);
        } else
            echo 'Файл не изменялся. ' . $this->getFileName() . '<BR>';
    }

    /**
     *
     */
    abstract protected function beforeIterateItem();

    /**
     *
     */
    abstract protected function afterIterateItem();

    /**
     *
     */
    abstract protected function afterIterateAll();

    /**
     * @param string $String
     */
    abstract protected function processItem($String);
}