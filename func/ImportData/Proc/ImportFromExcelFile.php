<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 27.10.2016
 * Time: 11:02
 */

namespace app\func\ImportData\Proc;


use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Import\Logreport;
use Exception;
use Yii;

abstract class ImportFromExcelFile extends ImportFile
{
    private $_chuckSize;
    private $_startRow;

    public function __construct(Importconfig $importConfig, $fieldNameDB, Logreport $logReport)
    {
        parent::__construct($importConfig, $fieldNameDB, $logReport);

        $this->setChuckSize(1000);
        $this->setStartRow(1);

    }

    /**
     * @return integer
     */
    public function getChuckSize()
    {
        return $this->_chuckSize;
    }

    /**
     * @param integer $chuckSize
     */
    public function setChuckSize($chuckSize)
    {
        $this->_chuckSize = $chuckSize;
    }

    /**
     * @return integer
     */
    public function getStartRow()
    {
        return $this->_startRow;
    }

    /**
     * @param integer $startRow
     */
    public function setStartRow($startRow)
    {
        $this->_startRow = $startRow;
    }

    /**
     *
     */
    public function iterate()
    {
        if ($this->isChanged()) {
            $this->startTime = microtime(true);

            $this->getLogReport()->save();

            $exit = false;   //флаг выхода
            $objReader = \PHPExcel_IOFactory::createReaderForFile($this->getFileName());

            $chunkFilter = new chunkReadFilter();
            $objReader->setReadFilter($chunkFilter);
            $objReader->setReadDataOnly(true);


            while (!$exit) {
                // Инициализируем переменные
                //  $row = $sheetData[self::$rownum_xls];

                $chunkFilter->setRows($this->getStartRow(), $this->getChuckSize());  //устанавливаем знаечние фильтра
                $objPHPExcel = $objReader->load($this->getFileName());  //открываем файл
                $objPHPExcel->setActiveSheetIndex(0);  //устанавливаем индекс активной страницы
                $objWorksheet = $objPHPExcel->getActiveSheet(); //делаем активной нужную страницу
                // Идем по данных excel
                for ($i = $this->getStartRow(); $i < $this->getStartRow() + $this->getChuckSize(); $i++) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {

                        $this->setRow($i);

                        if (empty(trim(htmlspecialchars($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()))))  //проверяем значение на пустоту
                            return;

                        $this->beforeIterateItem();

                        $this->processItem($objWorksheet->rangeToArray('A' . $i . ':M' . $i, null, true, true, true));

                        $this->afterIterateItem();

                        $transaction->commit();

                    } catch (Exception $e) {
                        $transaction->rollBack();
                        throw new Exception($e->getMessage() . ' $i = ' . $this->getRow() . '; $filename = ' . $this->getFileName());
                    }
                } //внутренний цикл по строкам
            }

            $this->afterIterateAll();

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