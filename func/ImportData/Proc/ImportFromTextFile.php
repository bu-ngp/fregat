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

abstract class ImportFromTextFile extends ImportFile
{

    public function iterate()
    {
        if ($this->isChanged()) {
            ini_set('max_execution_time', $this->importConfig->max_execution_time);  // 1000 seconds
            ini_set('memory_limit', $this->importConfig->memory_limit); // 1Gbyte Max Memory
            $this->startTime = microtime(true);

            $this->logReport->save();

            $i = 0;
            $handle = @fopen($this->fileName, "r");

            if ($handle) {
                $firstRow = true;
                while (($subject = fgets($handle, 4096)) !== false) {
                    if ($firstRow) {
                        $subject = $this->removeUTF8BOM($subject);
                        $firstRow = false;
                    }

                    $transaction = Yii::$app->db->beginTransaction();
                    $i++;
                    try {
                        $this->ProcessItem($subject);

                        $transaction->commit();

                        $this->afterIterateItem();
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        throw new Exception($e->getMessage() . ' $i = ' . $i . '; $filename = ' . $this->fileName);
                    }
                }
                fclose($handle);

                $this->afterIterateAll();
            }

            $this->logReport->logreport_amount += $i;
            $this->endTime = microtime(true);
            $this->logReport->logreport_executetime = gmdate('H:i:s', $this->endTime - $this->startTime);
            $this->logReport->logreport_memoryused = memory_get_usage(true);
            $this->logReport->save();

            echo 'ImportDo success<BR>';
            echo 'Использовано памяти: ' . Yii::$app->formatter->asShortSize(memory_get_usage(true)) . '; Время выполнения: ' . gmdate('H:i:s', $this->endTime - $this->startTime);
        }
    }

    public function removeUTF8BOM($String)
    {
        return str_replace("\xEF\xBB\xBF", '', $String);
    }

    abstract protected function getItem();

    abstract protected function afterIterateItem();

    abstract protected function afterIterateAll();

    abstract protected function ProcessItem($String);
}