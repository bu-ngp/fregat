<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 26.08.2016
 * Time: 8:54
 */

namespace app\func;

use Yii;

/**
 * Базовый класс для формирования отчетов по шаблонам Excel
 *
 * Class BaseReportPortal
 * @package app\func
 */
abstract class BaseReportPortal
{
    /**
     * Пароль на отчеты в Excel
     */
    const PasswordReport = '265463';
    /**
     * @var string $_TemplateFileName Имя Excel файла шаблона в папке Templates/*.xlsx
     */
    private $_TemplateFileName;
    /**
     * @var string $_ReportName Имя скачиваемого файла отчета
     */
    private $_ReportName;
    /**
     * @var \PHPExcel Объект PHPExcel
     */
    private $_objPHPExcel;
    /**
     * @var array $_params Параметры установленные вручную
     */
    private $_params;
    /**
     * @var array $_Dopparams Дополнительные переменные POST, отправленные Ajax запросом
     */
    private $_Dopparams;

    /**
     * @var string $_DirectoryFiles Директория сохранения файлов отчетов
     */
    private $_DirectoryFiles;

    /**
     * @var array $TITLE Стиль PHPExcel для заголовка
     */
    protected static $TITLE = [
        'font' => [
            'bold' => true,
            'name' => 'Tahoma',
            'size' => 9,
        ],
        'alignment' => [
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ],
    ];

    /**
     * @var array $TITLELEFT Стиль PHPExcel для заголовка с выравниванием слева
     */
    protected static $TITLELEFT = [
        'font' => [
            'bold' => true,
            'name' => 'Tahoma',
            'size' => 9,
        ],
        'alignment' => [
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        ],
        'borders' => [
            'allborders' => [
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
            ],
        ],
    ];

    /**
     * @var array $CAPTION Стиль PHPExcel для шапки таблицы
     */
    protected static $CAPTION = [
        'font' => [
            'bold' => true,
            'name' => 'Tahoma',
            'size' => 9,
        ],
        'alignment' => [
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'allborders' => [
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
            ],
        ],
    ];

    /**
     * @var array $NUMS Стиль PHPExcel для нумерации колонок
     */
    protected static $NUMS = [
        'font' => [
            'bold' => false,
            'name' => 'Tahoma',
            'size' => 8,
        ],
        'alignment' => [
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'allborders' => [
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
            ],
        ],
    ];

    /**
     * @var array $DATA Стиль PHPExcel для данных таблиц
     */
    protected static $DATA = [
        'font' => [
            'bold' => false,
            'name' => 'Tahoma',
            'size' => 8,
        ],
        'alignment' => [
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        ],
        'borders' => [
            'allborders' => [
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
            ],
        ],
    ];

    /**
     * @var array $SIGN Стиль PHPExcel для строки с заголовками подписей
     */
    protected static $SIGN = [
        'font' => [
            'bold' => false,
            'name' => 'Tahoma',
            'size' => 7,
        ],
        'alignment' => [
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ],
    ];

    /**
     * @var array $SIGNDATA Стиль PHPExcel для данных подписи
     */
    protected static $SIGNDATA = [
        'font' => [
            'bold' => false,
            'name' => 'Tahoma',
            'size' => 9,
        ],
        'alignment' => [
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        ],
        'borders' => [
            'allborders' => [
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
            ],
        ],
    ];

    /**
     * Функция читает дополнительные параметры из URL и присваивает свойству $this->_Dopparams класса
     */
    private function getDopparams()
    {
        $dopparams = json_decode(Yii::$app->request->post()['dopparams']);
        if (!empty($dopparams))
            $this->_Dopparams = $dopparams;
    }

    /**
     * Создаем PHPExcel объект и записываем его в внутренее свойство класса $this->_objPHPExcel
     */
    private function CreateExcelPHP()
    {
        $this->_objPHPExcel = \PHPExcel_IOFactory::load(Yii::$app->basePath . '/templates/' . $this->_TemplateFileName . '.xlsx');
    }

    /**
     * Выводит порядковое имя файла, если файл уже существует
     *
     * @param string $fileroot Имя файла под которым необходимо сохранить файл
     * @return string Имя файла под которым сохранится файл (Если существует, то имя будет инкрементироваться Файл(1) Файл(2) и т.д. )
     */
    private function SaveFileIfExists($fileroot)
    {
        $counter = 1;
        $filename = substr($fileroot, strrpos($fileroot, '/') + 1);

        while (file_exists($fileroot)) {
            preg_match('/(.+\/)(.+?)((\(.+)?\.)(.+)/i', $fileroot, $file_arr);
            // $file_arr[1] - Директория, $file_arr[2] - Имя файла, end($file_arr) - Расширение файла
            $fileroot = $file_arr[1] . $file_arr[2] . '(' . $counter . ')' . '.' . end($file_arr);
            $filename = $file_arr[2] . '(' . $counter . ')' . '.' . end($file_arr);
            $counter++;
        }

        return empty($filename) ? 'Файл.xlsx' : $filename;
    }

    /**
     * Скачиваем отчет Excel
     *
     * @param string $FileName Имя файла для сохранения отчета
     * @param bool $Protect Если true, то установить защиту книги и листа Excel
     * @return string
     * @throws \Exception
     * @throws \PHPExcel_Reader_Exception
     */
    private function DownloadExcelPHP($FileName = 'Файл.xlsx', $Protect = true)
    {
        if ($this->_objPHPExcel instanceof \PHPExcel) {

            if ($Protect) {
                $this->_objPHPExcel->getSecurity()->setLockWindows(true);
                $this->_objPHPExcel->getSecurity()->setLockStructure(true);
                $this->_objPHPExcel->getSecurity()->setWorkbookPassword(self::PasswordReport);
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setPassword(self::PasswordReport);
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setSheet(true); // This should be enabled in order to enable any of the following!
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setInsertColumns(true);
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setDeleteRows(true);
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setDeleteColumns(true);
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setObjects(true);
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setFormatColumns(true);
                $this->_objPHPExcel->getActiveSheet()->getProtection()->setFormatRows(true);
            }
            // Костыль - PHP Excel устанавливает ширину в колонке после последней. В результате на печать выводится второй лист, пустой.
            $HighestColumn = $this->_objPHPExcel->getActiveSheet()->getHighestColumn();
            $HighestDataColumn = $this->_objPHPExcel->getActiveSheet()->getHighestDataColumn();
            if ($HighestColumn !== $HighestDataColumn)
                $this->_objPHPExcel->getActiveSheet()->getColumnDimension($HighestColumn)->setWidth(0);

            $objWriter = \PHPExcel_IOFactory::createWriter($this->_objPHPExcel, 'Excel2007');

            $FileName = DIRECTORY_SEPARATOR === '/' ? $FileName : mb_convert_encoding($FileName, 'Windows-1251', 'UTF-8');
            //this->SaveFileIfExists() - Функция выводит подходящее имя файла, которое еще не существует. mb_convert_encoding() - Изменяем кодировку на кодировку Windows
            $fileroot = $this->SaveFileIfExists($this->getDirectoryFiles() . '/' . $FileName . '.xlsx');
            // Сохраняем файл в папку "files"
            $objWriter->save($this->getDirectoryFiles() . '/' . $fileroot);
            // Возвращаем имя файла Excel
            if (DIRECTORY_SEPARATOR === '/')
                return $fileroot;
            else
                return mb_convert_encoding($fileroot, 'UTF-8', 'Windows-1251');
        } else
            throw new \Exception('Ошибка в BaseReportPortal->DownloadExcelPHP()');
    }

    /**
     * @param string $paramName
     * @param mixed $value
     */
    public function setParams($paramName, $value)
    {
        $this->_params[$paramName] = $value;
    }

    /**
     * @param string $paramName
     * @return mixed
     */
    public function getParams($paramName)
    {
        return $this->_params[$paramName];
    }

    /**
     * @return string
     */
    public function getDirectoryFiles()
    {
        return $this->_DirectoryFiles;
    }

    /**
     * @param string $DirectoryFiles
     */
    public function setDirectoryFiles($DirectoryFiles)
    {
        if (is_string($DirectoryFiles))
            $this->_DirectoryFiles = $DirectoryFiles;
    }

    /**
     * Устанавливает для ячеек перенос по словам и выравнивание текста сверху
     *
     * @param string $CellCoordinate Координаты ячеек Excel, например "A1:B2"
     */
    protected function CellsWrapAndTop($CellCoordinate)
    {
        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->getStyle($CellCoordinate)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle($CellCoordinate)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
    }

    /**
     * Устанавливает имена колонок таблицы по массиву
     *
     * @param array $TitleArrayNames Массив наименований колонок таблицы, где ключ - колонка, начиная с 0. Значение - наименование колонки.
     * @param integer $RowNum Ряд листа Excel
     */
    protected function SetTitlebyArray($TitleArrayNames, $RowNum)
    {
        $objPHPExcel = $this->getObjPHPExcel();
        foreach ($TitleArrayNames as $ColumnNum => $Title)
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($ColumnNum, $RowNum, $Title);
    }

    /**
     * Установить стиль для ячеек
     *
     * @param array $StyleType Стиль PHPExcel
     * @param string $ExcelCoordinate Координаты ячеек Excel, например "A1:B2"
     */
    protected function setStyle($StyleType, $ExcelCoordinate)
    {
        $objPHPExcel = $this->getObjPHPExcel();
        $objPHPExcel->getActiveSheet()->getStyle($ExcelCoordinate)->applyFromArray($StyleType);
    }

    /**
     * Устанавливает имя отчета класса
     *
     * @param string $ReportName Имя отчета
     */
    public function setReportName($ReportName)
    {
        $this->_ReportName = $ReportName;
    }

    /**
     * Устанавливает имя шаблона класса
     *
     * @param string $TemplateFileName Имя файла шаблона
     */
    protected function setTemplateFileName($TemplateFileName = '')
    {
        $this->_TemplateFileName = $TemplateFileName;
    }

    /**
     * Возвращает имя файла шаблона
     *
     * @return string Имя файла шаблона
     */
    protected function getTemplateFileName()
    {
        return $this->_TemplateFileName;
    }

    /**
     * Возвращает номер отчета, указанный в запросе AJAX POST параметром id
     *
     * @return null|integer ИД отчета
     */
    protected function getDopparamID()
    {
        return !empty($this->_Dopparams->id) ? $this->_Dopparams->id : null;
    }

    /**
     * Восзращает объект класса PHPExcel
     *
     * @return \PHPExcel Объект PHPExcel
     */
    protected function getObjPHPExcel()
    {
        return $this->_objPHPExcel;
    }

    /**
     * Возвращает имя отчета
     *
     * @return string Имя отчета
     */
    public function getReportName()
    {
        return $this->_ReportName;
    }

    /**
     *  Устанавливает имя шаблона класса, метод переопределяется для установки имени шаблона
     */
    protected function SetupTemplateFileName()
    {
        $this->setTemplateFileName();
    }

    /**
     * Устанавливает нумерацию колонок заголовков таблицы
     *
     * @param integer $RowNum Ряд с нумерацией колонок
     * @param integer $CountNumbers Количество колоном для нумерации
     */
    protected function setColumnNumbers($RowNum, $CountNumbers)
    {
        $objPHPExcel = $this->getObjPHPExcel();
        for ($i = 0; $i <= $CountNumbers - 1; $i++)
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $RowNum, $i + 1);
    }

    /**
     *  Тело отчета, метод переопределяется
     */
    abstract protected function Body();

    public function __construct()
    {
        $this->setDirectoryFiles('files');
    }


    /**
     * Запуск формирования отчета
     *
     * @param bool $ProtectReport Если true, то защитить лист и книгу Excel
     * @return string
     * @throws \Exception
     */
    public function Execute($ProtectReport = true)
    {
        $this->getDopparams(); // Читаем дополнительные параметры из URL

        $this->SetupTemplateFileName();

        $this->CreateExcelPHP(); // Создаем объект PHPExcel

        $this->Body();

        return $this->DownloadExcelPHP($this->getReportName(), $ProtectReport); // Скачиваем сформированный отчет
    }

}