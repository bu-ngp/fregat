<?php

namespace app\func;

use Yii;

// Класс для чтения Excel файла по частям для экономии памяти
class chunkReadFilter implements \PHPExcel_Reader_IReadFilter {

    private $_startRow = 0;
    private $_endRow = 0;

    /**  Set the list of rows that we want to read  */
    public function setRows($startRow, $chunkSize) {
        $this->_startRow = $startRow;
        $this->_endRow = $startRow + $chunkSize;
    }

    public function readCell($column, $row, $worksheetName = '') {
        //  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow 
        if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) {
            return true;
        }
        return false;
    }

}

class TestMem {

    public static function TestMemDo() {
         $starttime = microtime(true);
        $chunkSize = 100;  //размер считываемых строк за раз
        $startRow = 6;
        $inputFileName = 'imp/os.xls';
        $inputFileType = 'Excel5';
        Yii::$app->formatter->sizeFormatBase = 1000;
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        echo '<hr />';
        /**  Define how many rows we want to read for each "chunk"  * */
        $chunkSize = 20;
        /**  Create a new Instance of our Read Filter  * */
        $chunkFilter = new chunkReadFilter();
        /**  Tell the Reader that we want to use the Read Filter that we've Instantiated  * */
        $objReader->setReadFilter($chunkFilter);
        $objReader->setReadDataOnly(true);
        /**  Loop to read our worksheet in "chunk size" blocks  * */
        for ($startRow = 6; $startRow <= 240; $startRow += $chunkSize) {
            echo 'Loading WorkSheet using configurable filter for headings row 1 and for rows ', $startRow, ' to ', ($startRow + $chunkSize - 1), '<br />';
            echo '<br>';

            echo '<br>' . Yii::$app->formatter->asShortSize(memory_get_usage(true));
            /**  Tell the Read Filter, the limits on which rows we want to read this iteration  * */
            $chunkFilter->setRows($startRow, $chunkSize);
            /**  Load only the rows that match our filter from $inputFileName to a PHPExcel Object  * */
            $objPHPExcel = $objReader->load($inputFileName);
            //	Do some processing here

            for ($i = $startRow; $i < $startRow + $chunkSize; $i++) {
                $sheetData = $objPHPExcel->getActiveSheet()->rangeToArray('A' . $i . ':Q' . $i, null, true, true, true);
            }

            $objPHPExcel->disconnectWorksheets();     //чистим 
            unset($objPHPExcel);       //память

            $endtime = microtime(true);
            
            echo '<br /><br />';
            echo '<br>';
            echo 'Использовано памяти: '.Yii::$app->formatter->asShortSize(memory_get_usage(true)).'; Время выполнения: '.gmdate('H:i:s', $endtime - $starttime);
            echo '<br>';
            
        }
    }

}
