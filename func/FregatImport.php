<?php

namespace app\func;

use app\models\Config\Profile;
use Yii;
use app\models\Config\Authuser;
use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Import\Employeelog;
use app\models\Fregat\Import\Logreport;
use app\models\Fregat\Import\Matlog;
use app\models\Fregat\Import\Traflog;
use app\models\Fregat\Impemployee;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Employee;
use app\models\Fregat\Importemployee;
use app\models\Fregat\Importmaterial;
use app\models\Fregat\Izmer;
use app\models\Fregat\Material;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Matvid;
use app\models\Fregat\Podraz;
use app\models\Fregat\Writeoffakt;
use yii\base\Exception;
use PDO;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Класс для чтения Excel файла по частям для экономии памяти
class chunkReadFilter implements \PHPExcel_Reader_IReadFilter
{

    private $_startRow = 0;
    private $_endRow = 0;

    /**  Set the list of rows that we want to read  */
    public function setRows($startRow, $chunkSize)
    {
        $this->_startRow = $startRow;
        $this->_endRow = $startRow + $chunkSize;
    }

    public function readCell($column, $row, $worksheetName = '')
    {
        //  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow 
        if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) {
            return true;
        }
        return false;
    }

}

class FregatImport
{
    const os = 1; // Основные средства
    const mat = 2; // Материалы
    const gu = 3; // Групповой учет основных средств

    private static $filename = ''; // Имя файла 'imp/os.xls' - (Основные средства); 'imp/mat.xls' = (Материалы);
    private static $filelastdate = null; // Дата изменения файла self::$filename
    private static $os = false; // true -  'imp/os.xls' (Основные средства); false - 'imp/mat.xls' (Материалы);
    private static $filetype = 1; // FregatImport::os - Основные средства, FregatImport::mat - Материалы, FregatImport::gu - Групповой учет основных средств;
    private static $employee = false; // true - 'imp/employee.txt' (Сотрудники);
    private static $logreport_id = 0; // ID отчета
    private static $logreport_errors = 0; // Не загружено записей из-за ошибок
    private static $logreport_updates = 0; // Записей изменено
    private static $logreport_additions = 0; // Записей добавлено
    private static $logreport_missed = 0; // Записей пропущено (исключены из обработки)
    private static $logreport_amount = 0; // Всего записей
    private static $os_start = 6; // Ряд с которого необходимо начать прохождение файла Excel Основных средств
    private static $mat_start = 9; // Ряд с которого необходимо начать прохождение файла Excel Материалов
    private static $gu_start = 2; // Ряд с которого необходимо начать прохождение файла Excel Группового учета основных средств
    private static $material_number_xls; // Пишется количество материальной ценности из файла Excel
    private static $material_price_xls; // Пишется цена материальной ценности из файла Excel
    private static $rownum_xls; // Номер строки в файле Excel
    private static $mattraffic_exist; // Проверка, найден ли у материальнной ценности сотрудник
    private static $xls;
    private static $materialexists; // Если количество материалов больше 0, то True, иначе False
    private static $Mishanya; // Ограничение на кол-во писем с новыми сотрудницами для Мишани
    private static $Debug; // Отключает функционал при отладке

    private static function SetFileType($FileType)
    {
        if (is_integer($FileType))
            self::$filetype = $FileType;
    }

    private static function IsFileType($FileType)
    {
        return $FileType === self::$filetype;
    }

    private static function Setxls()
    {
        $Importconfig = self::GetRowsPDO('select * from importconfig where importconfig_id = 1');

        self::$xls = [
            'mattraffic_date' => self::IsFileType(self::os) ? $Importconfig['os_mattraffic_date'] : '',
            'material_1c' => self::IsFileType(self::os) ? $Importconfig['os_material_1c'] : (self::IsFileType(self::gu) ? $Importconfig['gu_material_1c'] : $Importconfig['mat_material_1c']),
            'material_inv' => self::IsFileType(self::os) ? $Importconfig['os_material_inv'] : (self::IsFileType(self::gu) ? $Importconfig['gu_material_inv'] : $Importconfig['mat_material_inv']),
            'material_name1c' => self::IsFileType(self::os) ? $Importconfig['os_material_name1c'] : (self::IsFileType(self::gu) ? $Importconfig['gu_material_name1c'] : $Importconfig['mat_material_name1c']),
            'material_number' => self::IsFileType(self::mat) ? $Importconfig['mat_material_number'] : (self::IsFileType(self::gu) ? $Importconfig['gu_material_number'] : ''),
            'material_price' => self::IsFileType(self::os) ? $Importconfig['os_material_price'] : (self::IsFileType(self::gu) ? $Importconfig['gu_material_price'] : $Importconfig['mat_material_price']),
            'izmer_name' => self::IsFileType(self::mat) ? $Importconfig['mat_izmer_name'] : '',
            'employee_fio' => self::IsFileType(self::os) ? $Importconfig['os_employee_fio'] : (self::IsFileType(self::gu) ? $Importconfig['gu_employee_fio'] : $Importconfig['mat_employee_fio']),
            'dolzh_name' => self::IsFileType(self::os) ? $Importconfig['os_dolzh_name'] : (self::IsFileType(self::gu) ? $Importconfig['gu_dolzh_name'] : $Importconfig['mat_dolzh_name']),
            'podraz_name' => self::IsFileType(self::os) ? $Importconfig['os_podraz_name'] : (self::IsFileType(self::gu) ? $Importconfig['gu_podraz_name'] : $Importconfig['mat_podraz_name']),
            'material_serial' => self::IsFileType(self::os) ? $Importconfig['os_material_serial'] : (self::IsFileType(self::gu) ? $Importconfig['gu_material_serial'] : ''),
            'material_release' => self::IsFileType(self::os) ? $Importconfig['os_material_release'] : (self::IsFileType(self::gu) ? $Importconfig['gu_material_release'] : ''),
            'material_status' => self::IsFileType(self::os) ? $Importconfig['os_material_status'] : '',
            'material_tip_nomenklaturi' => self::IsFileType(self::mat) ? $Importconfig['mat_material_tip_nomenklaturi'] : '', // Колонка "ТипНоменклатуры" в файле Материалов
        ];
    }

    // Массив с координатами колонок в Excel

    private static function xls($field)
    {
        if (array_key_exists($field, self::$xls))
            return self::$xls[$field];
        else
            throw new Exception('Не существует FregatImport::xls("' . $field . '")');
    }

    private static function GetRowsPDO($sql, $params = null, $all = false)
    {
        try {
            if (empty($params))
                $params = [];
            $dbh = new PDO(Yii::$app->db->dsn, Yii::$app->db->username, Yii::$app->db->password);
            // $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            $rows = $dbh->prepare($sql);
            //    $rows->setFetchMode(PDO::FETCH_ASSOC);
            $rows->execute($params);
            $res = $all ? $rows->fetchAll(PDO::FETCH_ASSOC) : $rows->fetch(PDO::FETCH_ASSOC);
            $rows = null;
            $dbh = null;
            //    unset($rows);
            return $res;
            // return $rows;
        } catch (PDOException $e) {
            $rows = [];
        }
    }

    // Определяем вид материальной ценности
    // Разбиваем наименование на слова
    // Ищем в базе словосочетаний с первого слова, прибавляя по одному слову
    // Если не нашли, то ставится ИД = 1, т. е. "Не определен"
    private static function AssignMatvid($material_name1c)
    {
        // Определяем Вид материальной ценности
        Proc::mb_preg_match_all('/[а-яА-Яa-zA-Z\d]+/u', $material_name1c, $match_arr, PREG_OFFSET_CAPTURE, 0, 'UTF-8');

        $i = 0;
        $tmpmin = [1, ''];
        while ($i < count($match_arr[0])) {
            $word = $match_arr[0][$i];
            $str = mb_substr($material_name1c, 0, $word[1], 'UTF-8') . $word[0];

            $rows = self::GetRowsPDO('select importmaterial_combination, id_matvid from importmaterial where importmaterial_combination like :importmaterial_combination order by CHAR_LENGTH(importmaterial_combination)', ['importmaterial_combination' => $str . '%'], true);

            if (count($rows) === 1 && mb_stripos($material_name1c, $rows[0]['importmaterial_combination'], 0, 'UTF-8') === 0 || count($rows) > 1)
                $tmpmin = [$rows[0]['id_matvid'], $rows[0]['importmaterial_combination']];
            else
                break;

            $i++;
        }
        unset($match_arr);
        unset($rows);

        // Если Вид материальной ценности не определен, то ставится ключ 1 со значением "Не определен"
        return $tmpmin[0];
    }

    // Определяем Единицу измерения
    // Если единица измерения не найдена в справочнике, она добавляется.
    private static function AssignIzmer($value)
    {
        $izmer_id = self::GetRowsPDO('select izmer_id, izmer_name from izmer where izmer_name like :izmer_name', ['izmer_name' => $value]);

        if (empty($izmer_id)) {
            $Izmer = new Izmer;
            $Izmer->izmer_name = $value;
            if ($Izmer->Save())
                $izmer_id = $Izmer->izmer_id;
            unset($Izmer);
        } else
            $izmer_id = $izmer_id['izmer_id'];
        return $izmer_id;
    }

    // Конвертирует наименования должностей
    private static function DolzhConvert($value)
    {
        switch ($value) {
            case 'мед.сестра':
                $value = 'Медицинская сестра';
                break;
            case 'Медицинская сестра *регистратуры*':
                $value = 'Медицинская сестра [регистратуры]';
                break;
            case 'Ст. медсестра поликлиники':
                $value = 'Старшая медицинская сестра поликлиники';
                break;
            case 'Ст.мед.сестра':
                $value = 'Старшая медицинская сестра';
                break;
            case 'старшая мед. сестра':
                $value = 'Старшая медицинская сестра';
                break;
            case 'Старшая медицинская сестра *регистратуры*':
                $value = 'Старшая медицинская сестра [регистратуры]';
                break;
            case 'Уборщик производственных и служебных  помещений':
                $value = 'Уборщик производственных и служебных помещений';
                break;
            case 'Заведующий фельдшерским здравпунктом-фельдшер':
                $value = 'Фельдшер';
                break;
            case 'Медицинская сестра кабинета (инфекционных заболеваний)':
                $value = 'Медицинская сестра (кабинета инфекционных заболеваний)';
                break;
        }

        return $value;
    }

    // Определяем должность сотрудника
    // Если должность не найдена в справочнике, она добавляется
    private static function AssignDolzh($value)
    {
        if (trim($value) !== '') {
            $value = self::DolzhConvert($value);

            $dolzh_id = self::GetRowsPDO('select dolzh_id, dolzh_name from dolzh where dolzh_name like :dolzh_name', ['dolzh_name' => $value]);

            if (empty($dolzh_id)) {
                $Dolzh = new Dolzh;
                $Dolzh->dolzh_name = $value;
                if ($Dolzh->Save())
                    $dolzh_id = $Dolzh->dolzh_id;
            } else
                $dolzh_id = $dolzh_id['dolzh_id'];
        }

        return $dolzh_id;
    }

    // Определяем местонахождение сотрудника (Подразделение и Здание)
    // Местонахождение определяется по следующему алгоритму:
    // 1) Наименование Подразделения изется в таблице "importemployee", если не найдено создается новое подразделение, Здание ставится NULL
    // 2) Если найдено:
    // - Если в таблице "importemployee" стоит значение "id_employee", то подразделение и здание берется в соответствии с ИД сотрудника (id_employee)
    // - Если в таблице "importemployee" стоят значения "id_podraz", "id_build", то подразделение и здание берется в соответствии с ИД Подразделения (id_podraz) и ИД Здания (id_build)
    private static function AssignLocation($podraz_name, $employee_fio)
    {
        $result = (object)[
            'id_podraz' => NULL,
            'id_build' => NULL
        ];

        $importemployee = self::GetRowsPDO('select importemployee_id, id_podraz, id_build  from importemployee where importemployee_combination like :importemployee_combination', ['importemployee_combination' => $podraz_name]);

        if (empty($importemployee)) {
            $currentpodraz = self::GetRowsPDO('select podraz_id, podraz_name  from podraz where podraz_name like :podraz_name', ['podraz_name' => $podraz_name]);

            if (empty($currentpodraz)) {
                $Podraz = new Podraz;
                $Podraz->podraz_name = $podraz_name;
                if ($Podraz->Save())
                    $result->id_podraz = $Podraz->podraz_id;
            } else
                $result->id_podraz = $currentpodraz['podraz_id'];
        } else {
            $Impemployee = self::GetRowsPDO('select employee.id_podraz, employee.id_build from impemployee left join importemployee on impemployee.id_importemployee = importemployee.importemployee_id left join employee on impemployee.id_employee = employee.employee_id left join auth_user on employee.id_person = auth_user.auth_user_id where id_importemployee = :id_importemployee and auth_user_fullname like :employee_fio', [
                'employee_fio' => $employee_fio,
                'id_importemployee' => $importemployee['importemployee_id']
            ]);

            if (empty($Impemployee)) {
                $result->id_podraz = $importemployee['id_podraz'];
                $result->id_build = $importemployee['id_build'];
            } else {
                $result->id_podraz = $Impemployee['id_podraz'];
                $result->id_build = $Impemployee['id_build'];
            }
        }

        return $result;
    }

    // Определяем ИД Подразделения и Здания по их именам, и добавляем новые, если их нет
    private static function AssignLocationForEmployeeImport($podraz_name, $build_name)
    {

        $result = (object)[
            'id_podraz' => NULL,
            'id_build' => NULL
        ];

        if (trim($podraz_name) !== '') {
            $currentpodraz = self::GetRowsPDO('select podraz_id, podraz_name  from podraz where podraz_name like :podraz_name', ['podraz_name' => $podraz_name]);

            if (empty($currentpodraz)) {
                $Podraz = new Podraz;
                $Podraz->podraz_name = $podraz_name;
                if ($Podraz->Save())
                    $result->id_podraz = $Podraz->podraz_id;
            } else
                $result->id_podraz = $currentpodraz['podraz_id'];

            if (trim($build_name) !== '') {
                $currentbuild = self::GetRowsPDO('select build_id, build_name from build where build_name like :build_name', ['build_name' => $build_name]);

                if (empty($currentbuild)) {
                    $Build = new Build;
                    $Build->build_name = $build_name;
                    if ($Build->Save())
                        $result->id_build = $Build->build_id;
                } else
                    $result->id_build = $currentbuild['build_id'];
            }
        }

        return $result;
    }

    // Читаем дату в Excel и переводим в формат PHP
    // Допустимые значения в Excel - Строка содержащая подстроку формата "ДД.ММ.ГГГГ" или число формата даты Excel (Количество дней с 01.01.1900 года)
    private static function GetDateFromExcel($value)
    {
        $preg = '/(\d{2})\.(\d{2})\.(\d{4})(.*)/i';
        if (preg_match($preg, $value))
            return preg_replace($preg, '$3-$2-$1', $value);
        else
            return empty($value) ? NULL : date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($value));

        //   return PHPExcel_Shared_Date::isDateTime($objPHPExcel->getActiveSheet()->getcell('N' . $r)) ? date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getcell('N' . $r)->getValue())) : preg_replace($preg, '$3-$2-$1', $value);
    }

    // Читаем значения колонок соответствующие Материальной ценности в файле Excel
    private static function xls_attributes_material($row)
    {
        $matprice = preg_replace('/\s+/ui', '', $row[self::xls('material_price')]);
        $matprice = preg_replace('/,/ui', '.', $matprice);
        // Определяем количество материальной ценности
        if (self::IsFileType(self::os))
            $material_price = $matprice;
        else  // Если материалы или групповой учет основных средств, то цену извлекаем из суммы (сумма / количество)
            if (trim($row[self::xls('material_number')]) != '0')
                $material_price = (is_numeric($matprice) && is_numeric(trim($row[self::xls('material_number')]))) ? round($matprice / trim($row[self::xls('material_number')]), 2) : 'Ошибка при делении числа "' . $matprice . '" на число "' . trim($row[self::xls('material_number')]) . '"';
            else
                $material_price = 0;

        // Присваиваем значения в массив атрибутов
        return [
            'material_name1c' => trim($row[self::xls('material_name1c')]),
            'material_1c' => trim($row[self::xls('material_1c')]),
            'material_inv' => mb_strtolower(trim($row[self::xls('material_inv')]), 'UTF-8') === 'null' ? trim($row[self::xls('material_1c')]) : trim($row[self::xls('material_inv')]),
            'material_serial' => ((self::IsFileType(self::os) || self::IsFileType(self::gu)) && !in_array(mb_strtolower(trim($row[self::xls('material_serial')]), 'UTF-8'), ['null', 'б/н', 'б\н', 'б/н.', 'б\н.', '-'])) ? trim($row[self::xls('material_serial')]) : '',
            'material_release' => self::IsFileType(self::os) || self::IsFileType(self::gu) ? self::GetDateFromExcel(trim($row[self::xls('material_release')])) : NULL, // Определяем дату выпуска материальной ценности и переводим в формат PHP из формата Excel
            'material_number' => self::IsFileType(self::os) ? '1' : trim($row[self::xls('material_number')]), // Определяем количество материальной ценности
            'material_price' => $material_price,
            'material_tip' => self::IsFileType(self::os) ? 1 : (self::IsFileType(self::mat) ? 2 : 3), // Определяем тип материальной ценности (1 - Основное средство, 2 - Материал, 3 - Групповой учет основных средств)
            'id_matvid' => self::AssignMatvid(trim($row[self::xls('material_name1c')])), // Определяем Вид материальной ценности согласно таблицы соответствий importmaterial, если Вид материальной ценности не определен, то ставится ключ 1 со значением "Не определен"
            'id_izmer' => (self::IsFileType(self::os) || self::IsFileType(self::gu)) ? 1 : self::AssignIzmer(trim($row[self::xls('izmer_name')])), // Определяем Единицу измерения
            'material_tip_nomenklaturi' => self::IsFileType(self::mat) ? trim($row[self::xls('material_tip_nomenklaturi')]) : '', // Колонка "ТипНоменклатуры" в файле Материалов
        ];
    }

    // Читаем значения колонок соответствующие Сотруднику в файле Excel
    private static function xls_attributes_employee($row)
    {
        $employee_fio = trim($row[self::xls('employee_fio')]);

        $location = self::AssignLocation(trim($row[self::xls('podraz_name')]), $employee_fio);

        return [
            'employee_fio' => $employee_fio, // ФИО сотрудника
            'id_dolzh' => self::AssignDolzh(trim($row[self::xls('dolzh_name')])), // Определяем ID должности сотрудника
            'id_podraz' => $location->id_podraz, // Определяем ID подразделение сотрудника
            'id_build' => $location->id_build, // Определяем ID строения, в котором находится сотрудник
            'dolzh_name' => trim($row[self::xls('dolzh_name')]),
            'podraz_name' => trim($row[self::xls('podraz_name')])
        ];
    }

    // Читаем значения колонок соответствующие Операции над материальной ценностью в файле Excel
    private static function xls_attributes_mattraffic($row)
    {
        return [//--------------------????????????????????------------------------------------------------------------------------
            'mattraffic_date' => /* !self::$mattraffic_exist && */
                self::IsFileType(self::os) ? self::GetDateFromExcel(trim($row[self::xls('mattraffic_date')])) : date('Y-m-d'), // Определяем дату операции c материальной ценностью и переводим в формат PHP из формата Excel
            'mattraffic_number' => self::IsFileType(self::os) ? 1 : trim($row[self::xls('material_number')]), // Количество материала, задействованное в операции
            //   'mattraffic_tip' => self::IsFileType(self::mat) ? 1 : (in_array(trim($row[self::xls('material_status')]), ['Списан', 'Снято с учета']) ? 2 : 1),
        ];
    }

    // Добавляем в лог не измененные значения ActiveRecord
    private static function JustAddToLog($ar_Model, &$ar_LogModel)
    {
        $prop = mb_strtolower(substr($ar_LogModel->className(), strrpos($ar_LogModel->className(), '\\') + 1), 'UTF-8') . '_';
        $ar_LogModel->id_logreport = self::$logreport_id;
        $ar_LogModel[$prop . 'type'] = 5;
        $ar_LogModel[$prop . 'filename'] = self::$filename;
        $ar_LogModel[$prop . 'filelastdate'] = self::$filelastdate;
        $ar_LogModel[$prop . 'rownum'] = self::$rownum_xls;
        if ($ar_LogModel[$prop . 'message'] === '' || $ar_LogModel[$prop . 'message'] === NULL)
            $ar_LogModel[$prop . 'message'] = 'Запись не изменялась. ';
        return true;
    }

    // Валидируем значения модели и пишем в лог
    private static function ImportValidate($ar_Model, &$ar_LogModel)
    {
        $result = false;
        $prop = mb_strtolower(substr($ar_LogModel->className(), strrpos($ar_LogModel->className(), '\\') + 1), 'UTF-8') . '_';
        $ar_LogModel->id_logreport = self::$logreport_id;
        $ar_LogModel[$prop . 'type'] = $ar_Model->isNewRecord ? 1 : 2;
        $ar_LogModel[$prop . 'filename'] = self::$filename;

        if ($ar_LogModel->hasAttribute($prop . 'filelastdate'))
            $ar_LogModel[$prop . 'filelastdate'] = self::$filelastdate;
        $ar_LogModel[$prop . 'rownum'] = self::$rownum_xls;
        if ($ar_LogModel[$prop . 'message'] === '' || $ar_LogModel[$prop . 'message'] === NULL)
            $ar_LogModel[$prop . 'message'] = $ar_Model->isNewRecord ? 'Запись добавлена.' : 'Запись изменена: ';

        if (isset($ar_Model->scenarios()['import1c']))
            $ar_Model->scenario = 'import1c';
        if ($ar_Model->validate()) {
            if ($ar_Model->isNewRecord)
                self::$logreport_additions++;
            else
                self::$logreport_updates++;
            $result = true;
        } else {
            $ar_LogModel[$prop . 'type'] = $ar_Model->isNewRecord ? 3 : 4;
            $ar_LogModel[$prop . 'message'] = $ar_Model->isNewRecord ? 'Ошибка при добавлении записи: ' : 'Ошибка при изменении записи: ';
            foreach ($ar_Model->getErrors() as $fields)
                $ar_LogModel[$prop . 'message'] .= implode(' ', $fields) . ' ';
            self::$logreport_errors++;
        }
        return $result;
    }

    // Выводит актуальное количество материала у сотрудника
    private static function GetCountMaterialByID($MaterialID)
    {
        $dataReader = self::GetRowsPDO('select sum(mattraffic_number) as material_number from (select * from (select * from mattraffic m1 order by m1.mattraffic_date desc) temp group by id_material, id_mol) temp2 where id_material = :materialID group by id_material', [
            'materialID' => $MaterialID
        ]);
        /*  $sql = 'select sum(mattraffic_number) as material_number from (select * from (select * from mattraffic m1 order by m1.mattraffic_date desc) temp group by id_material, id_mol) temp2 where id_material = :materialID group by id_material';
          $dataReader = Yii::$app->db->createCommand($sql, [':materialID' => $materialID])->queryOne(); */
        if (empty($dataReader))
            return '-1';
        else
            return $dataReader['material_number'];
    }

    // Выводи последнюю дату изменения загруженных файлов
    private static function GetMaxFileLastDate($Importconfig)
    {
        if (mb_strpos(self::$filename, $Importconfig['emp_filename'], 0, 'UTF-8') > 0)
            $fieldname = 'logreport_employeelastdate';
        elseif (mb_strpos(self::$filename, $Importconfig['os_filename'], 0, 'UTF-8') > 0)
            $fieldname = 'logreport_oslastdate';
        elseif (mb_strpos(self::$filename, $Importconfig['mat_filename'], 0, 'UTF-8') > 0)
            $fieldname = 'logreport_matlastdate';

        $dataReader = self::GetRowsPDO('select max(logreport_employeelastdate) as logreport_employeelastdate, max(logreport_oslastdate) as logreport_oslastdate, max(logreport_matlastdate) as logreport_matlastdate from logreport');

        if (empty($dataReader[$fieldname]))
            return NULL;
        else
            return $dataReader[$fieldname];
    }

    private static function GetNameByID($Table, $Field, $ID)
    {
        $row = self::GetRowsPDO('select ' . $Field . ' from `' . $Table . '` where ' . $Table . '_id = :var', [
            'var' => $ID
        ]);

        return empty($row) ? null : $row[$Field];
    }

    private static function ngp_array_diff_assoc($source, $arr)
    {
        $result = [];
        foreach ($source as $key => $value)
            if (!(array_key_exists($key, $arr) && $value == $arr[$key]))
                $result[$key] = $value;
        return $result;
    }

    // Применяем изменения в атрибутах материальной ценности или создаем новую
    // Пишем в лог
    private static function MaterialDo(&$Material, &$Matlog, $row)
    {
        $result = false;
        // Присваиваем значения свойств материальной ценности из Excel в массив атрибутов
        $xls_attributes_material = self::xls_attributes_material($row);

        // Проверяем, что ТипНоменклатуры Материалов принадлежат к "Продукты питания" или "Прочие материальные запасы"
        $material_assigned = (self::IsFileType(self::os) || self::IsFileType(self::gu) || (self::IsFileType(self::mat) && in_array($xls_attributes_material['material_tip_nomenklaturi'], ['Мягкий инвентарь', 'Оборудование', 'Посуда', 'Строительные материалы', 'Продукты питания', 'Прочие материальные запасы']))) ? true : false;

        if ($material_assigned) {
            // Находим материальную ценность в базе по коду 1С, если не находим создаем новую запись
            $search = self::GetRowsPDO('select material_id from material where material_1c = :material_1c and material_tip = :material_tip ', [
                'material_1c' => $xls_attributes_material['material_1c'],
                'material_tip' => self::IsFileType(self::os) ? 1 : (self::IsFileType(self::mat) ? 2 : 3),
            ]);

            if (!empty($search))
                $Material = Material::findOne($search['material_id']);

            self::$material_number_xls = $xls_attributes_material['material_number'];
            self::$material_price_xls = $xls_attributes_material['material_price'];

            // Если материальная ценность найдена
            if (!$Material->isNewRecord) {
                $Material->material_price = floatval($Material->material_price); // т.к. $Material->material_price = "~.00"
                $Material->material_number = floatval($Material->material_number); // т.к. $Material->material_number = "1.000"
                // Убераем атрибуты, чтобы он не попали в $diff_attr
                unset($xls_attributes_material['material_tip_nomenklaturi']);
                unset($xls_attributes_material['material_number']);
                if (self::IsFileType(self::mat) || self::IsFileType(self::gu)) {
                    unset($xls_attributes_material['material_price']);
                    $Material->material_writeoff = 0;
                }

                // Если в Excel отсутствует инвентарный номер, а в базе он есть, то сделать так, что бы импорт не видел изменения
                if (empty($xls_attributes_material['material_inv']) && !empty($Material->material_inv))
                    $xls_attributes_material['material_inv'] = $Material->material_inv;

                // Массив заполняется измененными значениями атрибутов материальной ценности
                // $diff_attr = array_diff_assoc($xls_attributes_material, $Material->attributes);
                $diff_attr = self::ngp_array_diff_assoc($xls_attributes_material, $Material->attributes);
            }

            /*     echo '<br>begin------------------------------------------';

              var_dump($xls_attributes_material);
              var_dump($Material->attributes);
              var_dump($Material->isNewRecord ? 'New' : 'Edit');
              var_dump($diff_attr);

              echo '<br>end------------------------------------------'; */

            //   var_dump($diff_attr);
            // Если новая запись или произошли изменения в текущей
            if ($Material->isNewRecord || (count((array)$diff_attr) > 0 && $Material->material_importdo === 1)) {
                $Material->attributes = $xls_attributes_material;

                // material_name1с - Наименование из Excel файла. material_name - Изменяемое наименование пользователем в БД
                if ($Material->material_name === '' || $Material->material_name === NULL)
                    $Material->material_name = $Material->material_name1c;

                if (!$Material->isNewRecord && $Material->material_name !== $Material->material_name1c && $Material->material_importdo === 1) {
                    $Material->material_name = $Material->material_name1c;
                    $diff_attr['material_name'] = $Material->material_name1c;
                }

                if (!empty($Material->material_1c) && empty($Material->material_inv)) {
                    preg_match('/^(00-)?(.*)/ui', $Material->material_1c, $matches);
                    if (!empty($matches[2]))
                        $Material->material_inv = $matches[2];
                }

                $Matlog->attributes = $xls_attributes_material;
                $Matlog->material_number = self::$material_number_xls;
                $Matlog->material_price = self::$material_price_xls;

                $Matlog->matvid_name = self::GetNameByID('matvid', 'matvid_name', $Material->id_matvid);
                $Matlog->izmer_name = self::GetNameByID('izmer', 'izmer_name', $Material->id_izmer);
                $Matlog->material_writeoff = 'Нет';

                $Matlog->matlog_message = $Material->isNewRecord ? 'Запись добавлена' : 'Запись изменена: ';
                if (!$Material->isNewRecord)
                    foreach ($diff_attr as $attr => $value)
                        $Matlog->matlog_message .= '[' . $Material->getAttributeLabel($attr) . '] = "' . $value . '", ';

                // Валидируем значения модели и пишем в лог
                $result = self::ImportValidate($Material, $Matlog);
            } else { // Если изменения не внесены пишем в лог
                $Matlog->attributes = $Material->attributes;
                $Matlog->matvid_name = self::GetNameByID('matvid', 'matvid_name', $Material->id_matvid);
                $Matlog->izmer_name = self::GetNameByID('izmer', 'izmer_name', $Material->id_izmer);
                $Matlog->material_writeoff = $Material->material_writeoff === '1' ? 'Да' : 'Нет';

                // Добавляем в лог не измененные значения ActiveRecord
                $result = self::JustAddToLog($Material, $Matlog);
            }
        } else
            self::$logreport_missed += 1;
        return $result;
    }

    // Применяем изменения в атрибутах сотрудника или создаем нового
    // Пишем в лог
    private static function EmployeeDo(&$Employee, &$Employeelog, $row)
    {
        $result = false;
        $xls_attributes_employee = self::xls_attributes_employee($row);

        //    $sqlstr = empty($xls_attributes_employee['id_build']) ? ' and id_build is null' : ' and id_build = :id_build';
        // Находим сотрудника в базе, если не находим создаем новую запись
        /*    $Employee = self::GetRowsPDO('select auth_user_fullname, from employee where id_dolzh = :id_dolzh and id_podraz = :id_podraz' . $sqlstr, array_merge([
          'id_dolzh' => $xls_attributes_employee['id_dolzh'],
          'id_podraz' => $xls_attributes_employee['id_podraz']
          ], empty($xls_attributes_employee['id_build']) ? [] : ['id_build' => $xls_attributes_employee['id_build']] ));
         */
        //  if (!empty($search))
        $search = Employee::find()
            ->joinWith('idperson')
            ->where(array_merge([
                'id_dolzh' => $xls_attributes_employee['id_dolzh'],
                'id_podraz' => $xls_attributes_employee['id_podraz'],
            ], empty($xls_attributes_employee['id_build']) ? [] : ['id_build' => $xls_attributes_employee['id_build']]))
            ->andFilterWhere(['like', 'auth_user_fullname', $xls_attributes_employee['employee_fio'], false])
            ->one();

        if (!empty($search))
            $Employee = $search;

        if ($Employee->isNewRecord) { //Если новая запись (Нет соответствия по ФИО, Должности, Подразделению, Зданию)
            $Employee->attributes = $xls_attributes_employee;

            $AuthuserCount = Authuser::find()
                ->where(['like', 'auth_user_fullname', $xls_attributes_employee['employee_fio'], false])
                ->count();

            /*    $search2 = Authuser::find()
              ->where(['like', 'auth_user_fullname', $xls_attributes_employee['employee_fio']])
              ->one(); */

            $Authuser = $AuthuserCount == 1 ? Authuser::find()
                ->where(['like', 'auth_user_fullname', $xls_attributes_employee['employee_fio'], false])
                ->one() : false;


            if (empty($Authuser) || $AuthuserCount > 1) {
                $Authuser = new Authuser;
                $Authuser->auth_user_fullname = $xls_attributes_employee['employee_fio'];
                $Authuser->auth_user_login = Proc::CreateLogin($xls_attributes_employee['employee_fio']);
                $Authuser->auth_user_password = Yii::$app->getSecurity()->generatePasswordHash('11111111');
            }

            if (isset($Authuser->scenarios()['import1c']))
                $Authuser->scenario = 'import1c';

            /*  $Person = new Authuser;
              if (!empty($search2))
              $Person = $search2; */

            if ($Authuser->isNewRecord) {
                if ($Authuser->validate()) {
                    $Authuser->save(false);
                    $Employee->id_person = $Authuser->getPrimaryKey();
                } else {
                    $Employeelog->employeelog_type = 3;
                    $Employeelog->employeelog_message = 'Ошибка при добавлении записи: ';
                    foreach ($Authuser->getErrors() as $fields)
                        $Employeelog->employeelog_message .= implode(' ', $fields) . ' ';
                    self::$logreport_errors++;
                }
            } else {
                $Employee->id_person = $Authuser->getPrimaryKey();
            }

            $Employeelog->employee_fio = Authuser::findOne($Employee->id_person)->auth_user_fullname; //self::GetNameByID('auth_user', 'auth_user_fullname', $Employee->id_person);
            $Employeelog->dolzh_name = Dolzh::findOne($Employee->id_dolzh)->dolzh_name; //self::GetNameByID('dolzh', 'dolzh_name', $Employee->id_dolzh);
            $Employeelog->podraz_name = Podraz::findOne($Employee->id_podraz)->podraz_name; //self::GetNameByID('podraz', 'podraz_name', $Employee->id_podraz);
            if (!empty($Employee->id_build))
                $Employeelog->build_name = Build::findOne($Employee->id_build)->build_name; //self::GetNameByID('build', 'build_name', $Employee->id_build)


// Валидируем значения модели и пишем в лог
            $result = self::ImportValidate($Employee, $Employeelog);
        } else { // Если изменения не внесены пишем в лог
            //   $Employeelog->attributes = $Employee->attributes;
            /*   if (!empty($Employee->employee_dateinactive)) {             // Если есть в excel, то убрать дату неактивности специальности
              $Employeelog->employeelog_type = 2;
              $Employeelog->employeelog_message = 'Запись изменена. Очищена дата неактивности специальности "' . Yii::$app->formatter->asDate($Employee->employee_dateinactive) . '"';
              $Employeelog->employee_fio = Authuser::findOne($Employee->id_person)->auth_user_fullname;
              $Employeelog->dolzh_name = Dolzh::findOne($Employee->id_dolzh)->dolzh_name;
              $Employeelog->podraz_name = Podraz::findOne($Employee->id_podraz)->podraz_name;
              if (!empty($Employee->id_build))
              $Employeelog->build_name = Build::findOne($Employee->id_build)->build_name;

              $Employee->employee_dateinactive = null;

              $result = self::ImportValidate($Employee, $Employeelog);
              } */

            $Employeelog->employee_fio = Authuser::findOne($Employee->id_person)->auth_user_fullname; //self::GetNameByID('auth_user', 'auth_user_fullname', $Employee->id_person);
            $Employeelog->dolzh_name = Dolzh::findOne($Employee->id_dolzh)->dolzh_name; //self::GetNameByID('dolzh', 'dolzh_name', $Employee->id_dolzh);
            $Employeelog->podraz_name = Podraz::findOne($Employee->id_podraz)->podraz_name; //self::GetNameByID('podraz', 'podraz_name', $Employee->id_podraz);
            if (!empty($Employee->id_build))
                $Employeelog->build_name = Build::findOne($Employee->id_build)->build_name; //self::GetNameByID('build', 'build_name', $Employee->id_build);


// Добавляем в лог не измененные значения ActiveRecord
            $result = self::JustAddToLog($Employee, $Employeelog);
        }

        return $result;
    }

    // Определяем количество материальной ценности с учетом изменения
    private static function MatNumberChanging(&$Material, &$Traflog, $Number, $Diff)
    {
        if ((self::IsFileType(self::mat) || self::IsFileType(self::gu)) && $Number != 0) {
            if ($Diff && ($Material->material_number - $Number) < 0) {
                $Material->material_writeoff = 0;
                $Number = abs($Number);
            }

            $Material->material_number = $Diff ? $Material->material_number - $Number : $Number;

            if (floatval($Material->material_number) < 0)
                $Traflog->traflog_message = 'Ошибка при изменении количества [Количество материальной ценности](' . $Material->material_number . ') плюс [Количество задействованное в операции](' . $Number . ') меньше 0. ';
        }
    }

    // Применяем изменения в атрибутах "Операции над материальной ценностью" или создаем нового
    // Пишем в лог
    private static function MattrafficDo(&$Mattraffic, &$Traflog, $row, $Material, $employee_id)
    {
        $result = false;

        $material_id = empty($Material->material_id) ? -1 : $Material->material_id;
        $employee_id = empty($employee_id) ? -1 : $employee_id;

        $xls_attributes_mattraffic = array_merge(self::xls_attributes_mattraffic($row), [
            'id_material' => $material_id,
            'id_mol' => $employee_id,
            'mattraffic_tip' => 1,
        ]);

        // Ищем Материальную ценность закрепленную за сотрудником
        $search = self::GetRowsPDO('select * from mattraffic where id_material = :id_material and id_mol = :id_mol' . (self::IsFileType(self::os) ? ' and mattraffic_date = :mattraffic_date' : '') . ' order by mattraffic_date desc, mattraffic_id desc limit 1', array_merge([
            'id_material' => $xls_attributes_mattraffic['id_material'],
            'id_mol' => $xls_attributes_mattraffic['id_mol'],
        ], self::IsFileType(self::os) ? ['mattraffic_date' => $xls_attributes_mattraffic['mattraffic_date']] : []));

        // recordapply - Проверка актуальности даты операции над материальной ценностью с датой из Excel (1 - Дата актуальна, 0 - Дата не актуальна)
        // diff_number - Определяет текущее актуальное количество материальной ценности
        if (!empty($search))
            $Mattraffic = Mattraffic::find()->select('*, case when DATE(mattraffic_date) < :date_xls then true else false end as recordapply, (mattraffic_number - :mattraffic_number) AS diff_number')
                ->where([
                    'mattraffic_id' => $search['mattraffic_id'],
                ])
                ->params([
                    ':date_xls' => $xls_attributes_mattraffic['mattraffic_date'],
                    ':mattraffic_number' => $xls_attributes_mattraffic['mattraffic_number']
                ])
                ->orderBy('mattraffic_date desc')
                ->one();

        $Traflog->attributes = $xls_attributes_mattraffic;

        if (self::IsFileType(self::mat) || self::IsFileType(self::gu))
            $Mattraffic->mattraffic_forimport = 1;

        if (!$Mattraffic->isNewRecord && $Mattraffic->recordapply && ((self::IsFileType(self::mat) || self::IsFileType(self::gu)) && $Mattraffic->diff_number != '0' || self::IsFileType(self::os))) { // Если у материальной ценности найден сотрудник и запись актуальна
            // Разница в количестве (Количество из Excel - количество из БД)
            $diff_number = $Mattraffic->diff_number;
            self::$mattraffic_exist = true;

            // Если материал уже списан, но изменилась дата, просто меняем дату на актуальную
            if (self::IsFileType(self::os) && $Material->material_writeoff === '1') {
                $Mattraffic->mattraffic_date = $xls_attributes_mattraffic['mattraffic_date'];
                $Traflog->traflog_message .= 'Запись изменена: Дата изменена на "' . $Mattraffic->mattraffic_date . '"';
            } else {
                $Mattraffic = new Mattraffic;
                $Mattraffic->attributes = $xls_attributes_mattraffic;
                $Mattraffic->mattraffic_date = $xls_attributes_mattraffic['mattraffic_date'];
                $Mattraffic->mattraffic_number = self::$material_number_xls;
                if (self::IsFileType(self::mat) || self::IsFileType(self::gu))
                    $Mattraffic->mattraffic_forimport = 1;
            }

            // Определяем количество материальной ценности с учетом изменения
            self::MatNumberChanging($Material, $Traflog, $diff_number, true);

            // Валидируем значения модели и пишем в лог
            $result = self::ImportValidate($Mattraffic, $Traflog);
        } elseif ($Mattraffic->isNewRecord) { // Если у материальной ценности не найден сотрудник, создаем новую операцию
            $Mattraffic->attributes = $xls_attributes_mattraffic;
            self::$mattraffic_exist = false;

            // Количество материальной ценности у разных сотрудников
            $mat_number = self::GetCountMaterialByID($Material->material_id);

            if ($mat_number == '-1') // Если новая материальная ценность
                $mat_number = self::$material_number_xls;
            else
                $mat_number += self::$material_number_xls;

            // Определяем количество материальной ценности с учетом изменения
            self::MatNumberChanging($Material, $Traflog, $mat_number, false);

            // Валидируем значения модели и пишем в лог
            $result = self::ImportValidate($Mattraffic, $Traflog);
        } elseif (self::IsFileType(self::mat) || self::IsFileType(self::gu)) {
            if (isset($Mattraffic->scenarios()['import1c']))
                $Mattraffic->scenario = 'import1c';
            if ($Mattraffic->validate())
                $Mattraffic->save(false);
        } else
            $Mattraffic->clearErrors();

        //var_dump($Mattraffic->errors);

        /*  if (self::IsFileType(self::mat) || self::IsFileType(self::gu)) {
          var_dump($Material->material_name);
          var_dump($Mattraffic->attributes);
          } */

        return $result;
    }

    // Применяем, изменился ли статус материальной ценности на списанную, вносим изменения в БД и создаем новый акт списания
    // Пишем в лог
    private static function WriteOffDo($Material, $Matlog, $Mattraffic, $Traflog, $row)
    {
        // Если Материал списан (Сумма = 0)
        if (((self::IsFileType(self::mat) || self::IsFileType(self::gu)) && $Material->material_writeoff == 0 && self::$material_price_xls == 0 && (!self::$mattraffic_exist || self::$mattraffic_exist && $Material->material_price != 0))
            // Или Основное средство списано (Статус = "Списан")
            || (self::IsFileType(self::os) && $Material->material_writeoff == 0 &&
                in_array(trim($row[self::xls('material_status')]), ['Списан', 'Снято с учета']))
        ) {

            $Material->material_writeoff = 1;
            if (self::IsFileType(self::mat))
                $Material->material_price = 0;

            $Material->save(false);

            $Matlog->material_writeoff = 'Да';
            $Matlog->save(false);

            /*          if (isset($Mattraffic->scenarios()['import1c']))
                            $Mattraffic->scenario = 'import1c';
                        $Mattraffic->mattraffic_tip = 2; // Списание
                        $Mattraffic->save(false);
            */
            $mtcoff = new Mattraffic;
            if (isset($mtcoff->scenarios()['import1c']))
                $mtcoff->scenario = 'import1c';
            $mtcoff->mattraffic_date = date('Y-m-d');
            $mtcoff->mattraffic_number = $Mattraffic->mattraffic_number;
            $mtcoff->id_material = $Mattraffic->id_material;
            $mtcoff->id_mol = $Mattraffic->id_mol;
            $mtcoff->mattraffic_tip = 2;
            $mtcoff->save();

            $writeoffakt = new Writeoffakt();
            // $writeoffakt->id_mattraffic = $Mattraffic->mattraffic_id;
            $writeoffakt->id_mattraffic = $mtcoff->mattraffic_id;
            $writeoffakt->save(false);

            $Traflog->traflog_message .= ' Добавлен акт списания с номером "' . $writeoffakt->writeoffakt_id . '" на дату "' . date('d.m.Y', strtotime($mtcoff->mattraffic_date)) . '".';
            $Traflog->save(false);
        }
    }

    // Делает специальности сотрудников неактивными, если они не найдены в файле сотрудников для импорта
    static private function InactiveEmployee()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //  Employee::updateAll(['employee_dateinactive' => date('Y-m-d')], 'employee_forinactive is null and employee_importdo = 1 and employee_dateinactive is null and id_person in (select e.id_person from employee e group by e.id_person having count(id_person) = 1)');

            Yii::$app->db->createCommand('UPDATE employee inner join (select e.id_person from employee e group by e.id_person having count(e.id_person) = 1) e2 on employee.id_person = e2.id_person SET employee_dateinactive=NOW() WHERE employee_forinactive is null and employee_importdo = 1 and employee_dateinactive is null')
                ->execute();

            $Forlog = Employee::find()
                ->joinWith(['idperson', 'idpodraz', 'iddolzh', 'idbuild'])
                ->where('employee_forinactive is null and employee_importdo = 1 and DATE(employee_dateinactive) = CURDATE() and id_person in (select e.id_person from employee e group by e.id_person having count(e.id_person) = 1)')
                ->all();

            if (!empty($Forlog))
                foreach ($Forlog as $i => $ar) {
                    $Employeelog = new Employeelog;
                    $Employeelog->id_logreport = self::$logreport_id;
                    $Employeelog->employeelog_type = 2;
                    $Employeelog->employeelog_filename = self::$filename;
                    $Employeelog->employeelog_filelastdate = self::$filelastdate;
                    $Employeelog->employeelog_rownum = 0;
                    $Employeelog->employeelog_message = 'Запись изменена. Специальность сотрудника неактивна с "' . Yii::$app->formatter->asDate($ar->employee_dateinactive) . '"';
                    $Employeelog->employee_fio = $ar->idperson->auth_user_fullname;
                    $Employeelog->dolzh_name = $ar->iddolzh->dolzh_name;
                    $Employeelog->podraz_name = $ar->idpodraz->podraz_name;
                    $Employeelog->build_name = $ar->isRelationPopulated('idbuild') ? $ar->idbuild['build_name'] : '';

                    $Employeelog->save(false);
                }

            Employee::updateAll(['employee_forinactive' => NULL], ['employee_forinactive' => 1]);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            Employee::updateAll(['employee_forinactive' => NULL], ['employee_forinactive' => 1]);
            throw new Exception($e->getMessage() . ' InactiveEmployee(), $filename = ' . self::$filename);
        }
    }

    // Проверка, списан ли весь материал у матер. ответсв. лиц, если да, то сделат признак списания в таблице материальнных ценностей
    static private function SpisatMaterial($MaterialID, $MOLID)
    {
        $return = false;

        $Typemat = self::IsFileType(self::mat) ? 2 : 3;
        $result = Mattraffic::find()
            ->from(['m1' => 'mattraffic'])
            ->join('LEFT JOIN', 'material', 'm1.id_material = material.material_id')
            ->join('LEFT JOIN', 'mattraffic m2', 'm1.id_material = m2.id_material and m1.id_mol = m2.id_mol and m1.mattraffic_date < m2.mattraffic_date')
            ->andWhere(['m1.mattraffic_forimport' => NULL, 'material_writeoff' => 0])
            ->andWhere(['in', 'material_tip', [$Typemat]])
            ->andWhere('m1.mattraffic_tip <> 2')
            ->andWhere(['m2.mattraffic_date' => NULL])
            ->andWhere(['m1.id_material' => $MaterialID])
            ->count();

        if (!empty($result)) {
            $Material = Material::findOne($MaterialID);

            $matmol = Mattraffic::find()
                ->from(['m1' => 'mattraffic'])
                ->join('LEFT JOIN', 'material', 'm1.id_material = material.material_id')
                ->join('LEFT JOIN', 'mattraffic m2', 'm1.id_material = m2.id_material and m1.id_mol = m2.id_mol and m1.mattraffic_date < m2.mattraffic_date')
                ->andWhere(['m1.mattraffic_forimport' => NULL, 'material_writeoff' => 0])
                ->andWhere(['in', 'material_tip', [$Typemat]])
                ->andWhere('m1.mattraffic_tip <> 2')
                ->andWhere(['m2.mattraffic_date' => NULL])
                ->andWhere(['m1.id_material' => $MaterialID, 'm1.id_mol' => $MOLID])
                ->one();

            // Если материл не найден у мола в файле импорта, то вычитаем общее количество материала
            if (!empty($matmol))
                $Material->material_number -= $matmol->mattraffic_number;

            if ($Material->material_number == 0) {
                $Material->material_writeoff = 1;
                $return = true;
            }

            if (!empty($matmol) || $Material->material_number == 0)
                $Material->save(false);
        }
        return $return;
    }

    // Списание материала
    static private function MaterialSpisanie()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $Typemat = self::IsFileType(self::mat) ? 2 : 3;
            $SP = Mattraffic::find()
                //     ->joinWith('idMaterial')
                ->from(['m1' => 'mattraffic'])
                ->join('LEFT JOIN', 'material', 'm1.id_material = material.material_id')
                ->join('LEFT JOIN', 'mattraffic m2', 'm1.id_material = m2.id_material and m1.id_mol = m2.id_mol and m1.mattraffic_date < m2.mattraffic_date')
                ->andWhere(['m1.mattraffic_forimport' => NULL, 'material_writeoff' => 0])
                ->andWhere(['in', 'material_tip', [$Typemat]])
                ->andWhere('m1.mattraffic_tip <> 2')
                ->andWhere(['m2.mattraffic_date' => NULL])
                ->all();


            if (!empty($SP))
                foreach ($SP as $i => $ar) {
                    $spismat = self::SpisatMaterial($ar->id_material, $ar->id_mol);

                    $Mattraffic = new Mattraffic;
                    $Mattraffic->attributes = $ar->attributes;
                    $Mattraffic->mattraffic_date = date('Y-m-d');
                    $Mattraffic->mattraffic_number = 0;
                    $Mattraffic->mattraffic_tip = 2; // Списание                    
                    if (isset($Mattraffic->scenarios()['import1c']))
                        $Mattraffic->scenario = 'import1c';
                    $Mattraffic->save(false);

                    $writeoffakt = new Writeoffakt();
                    $writeoffakt->id_mattraffic = $Mattraffic->mattraffic_id;
                    $writeoffakt->save(false);

                    $Material = Material::findOne($ar->id_material);

                    $Matlog = new Matlog;
                    $Matlog->attributes = $Material->attributes;
                    $Matlog->id_logreport = self::$logreport_id;
                    $Matlog->matlog_filename = self::$filename;
                    $Matlog->matlog_filelastdate = self::$filelastdate;
                    $Matlog->matlog_rownum = 0;
                    $Matlog->matlog_type = $spismat ? 4 : 2;
                    $Matlog->matlog_message = $spismat ? 'Материал списан. ' : 'Запись не изменялась. ';
                    $Matlog->matvid_name = self::GetNameByID('matvid', 'matvid_name', $Material->id_matvid);
                    $Matlog->izmer_name = self::GetNameByID('izmer', 'izmer_name', $Material->id_izmer);
                    $Matlog->material_writeoff = $Material->material_writeoff === 1 ? 'Да' : 'Нет';
                    $Matlog->material_release = isset($Material->material_release) ? Yii::$app->formatter->asDate($Material->material_release) : $Material->material_release;
                    $Matlog->save(false);

                    $Employee = Employee::find()
                        ->joinWith(['idperson', 'iddolzh', 'idpodraz', 'idbuild'])
                        ->where(['employee_id' => $ar->id_mol])
                        ->one();

                    $Employeelog = new Employeelog;
                    $Employeelog->attributes = $Employee->attributes;
                    $Employeelog->id_logreport = self::$logreport_id;
                    $Employeelog->employeelog_filename = self::$filename;
                    $Employeelog->employeelog_filelastdate = self::$filelastdate;
                    $Employeelog->employeelog_rownum = 0;
                    $Employeelog->employeelog_type = 2;
                    $Employeelog->employeelog_message = 'Запись не изменилась';
                    $Employeelog->employee_fio = $Employee->idperson->auth_user_fullname;
                    $Employeelog->dolzh_name = $Employee->iddolzh->dolzh_name;
                    $Employeelog->podraz_name = $Employee->idpodraz->podraz_name;
                    $Employeelog->build_name = $Employee->isRelationPopulated('idbuild') ? $Employee->idbuild['build_name'] : '';
                    $Employeelog->save(false);


                    $Traflog = new Traflog;
                    $Traflog->id_logreport = self::$logreport_id;
                    $Traflog->traflog_type = 1;
                    $Traflog->traflog_filename = self::$filename;
                    $Traflog->traflog_rownum = 0;
                    $Traflog->traflog_message = '';
                    $Traflog->id_matlog = $Matlog->primaryKey;
                    $Traflog->id_employeelog = $Employeelog->primaryKey;
                    $Traflog->traflog_message = 'Запись добавлена. Добавлен акт списания с номером "' . $writeoffakt->writeoffakt_id . '" на дату "' . date('d.m.Y', strtotime($Mattraffic->mattraffic_date)) . '".';
                    $Traflog->mattraffic_number = $ar->mattraffic_number;
                    $Traflog->save(false);

                    self::$logreport_additions++;
                }

            Mattraffic::updateAll(['mattraffic_forimport' => NULL], ['mattraffic_forimport' => 1]);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            Mattraffic::updateAll(['mattraffic_forimport' => NULL], ['mattraffic_forimport' => 1]);
            throw new Exception($e->getMessage() . ' MaterialSpisanie(), $filename = ' . self::$filename);
        }
    }

    // Ищем жену Михаилу
    static function Mishanya($Authuser, $Employee, $matches)
    {
        if (self::$Mishanya < 3) {
            $dr = Yii::$app->formatter->asDate($matches[16]);
            $pol = $matches[15];
            $d1 = new \DateTime($dr);
            $d2 = new \DateTime(date('Y-m-d'));
            $diff = $d2->diff($d1);

            $subthemes = [
                'Мишаня, новые телочки, пора за работу',
                'Мишаня, настало твое время',
                'Мишаня, мы все верим в тебя',
                'Мишаня, все в твоих руках, за работу',
                'Мишаня, на этот раз у тебя полюбому все получится',
                'Мишаня, я лично в тебя верю, давай',
                'Мишаня, мы будем болеть за тебя всем отделом',
                'Мишаня, Президент лично одобрил',
                'Мишаня, все схвачено, дело за тобой',
                'Мишаня, давай пацан',
                'Мишаня, у тебя все получится',
                'Мишаня, вперед, в атаку',
                'Мишаня, хватит распиздяйничать, займись делом',
                'Мишаня, появилась непыльная работенка',
                'Мишаня, кажется ты нашел свою новую жену',
                'Мишаня, тебе пора жениться',
                'Мишаня, она ждет',
                'Мишаня, кажется кто-то нуждается в твоих комплементах',
                'Мишаня, найден новый объект для комплиментиков',
                'Мишаня, с ней только серьезные отношения',
                'Мишаня, она думает о тебе',
                'Мишаня, хватит душить удава, займись делом',
                'Мишаня, тут Кондратьевские подсуетились',
                'Мишаня, получи новое задание Кунимена',
                'Мишаня, киевская развед-школа поработала на тебя',
                'Мишаня, у нее компьютер сломался, нужно проверить',
                'Мишаня, пора поработать с низкого старта',
                'Мишаня, партийное задание',
                'Мишаня, надевай тапки пиздуна, есть задание',
            ];

            if ($pol == 'Ж' && $diff->y <= 35) {
                self::$Mishanya++;
                Yii::$app->mailer->compose('/site/misha', [
                    'fio' => $Authuser->auth_user_fullname,
                    'dr' => $dr,
                    'vozrast' => $diff->y,
                    'dolzh' => Dolzh::findOne($Employee->id_dolzh)->dolzh_name,
                    'podraz' => Podraz::findOne($Employee->id_podraz)->podraz_name,
                    'build' => (!empty($Employee->id_build)) ? Build::findOne($Employee->id_build)->build_name : '',
                    'address' => $matches[10],
                ])
                    ->setFrom('portal@mugp-nv.ru')
                    ->setTo([
                        'karpovvv@mugp-nv.ru',
                        'mns@mugp-nv.ru',
                        /*   'dnn@mugp-nv.ru',
                          'mns@mugp-nv.ru',
                          'dvg@mugp-nv.ru',
                          'chepenkoav@mugp-nv.ru',
                          'valikanovae@mugp-nv.ru', */
                    ])
                    ->setSubject($subthemes[rand(0, count($subthemes) - 1)])
                    ->send();
            }
        }
    }

    // Производим импорт материальных ценностей                                             // Не забыть про InactiveEmployee
    static function ImportDo()
    {
        // Делаем запись в таблицу отчетов импорта
        $logreport = new Logreport;
        $Importconfig = self::GetRowsPDO('select * from importconfig where importconfig_id = 1');

        if ($Importconfig['importconfig_do']) {
            self::$os_start = $Importconfig['os_startrow'];
            self::$mat_start = $Importconfig['mat_startrow'];
            self::$gu_start = $Importconfig['gu_startrow'];
            self::$Mishanya = 0;
            self::$Debug = YII_DEBUG;
            $starttime = microtime(true);
            $logreport->logreport_date = date('Y-m-d');
            $doreport = false;
            self::$materialexists = Material::find()->count() > 0;

            self::DeleteOldReports();

            // Идем по файлам импорта из 1С (os.xls - Основные средства, mat.xls - Материалы)
            foreach ([$Importconfig['emp_filename'] . '.txt', $Importconfig['os_filename'] . '.xlsx', $Importconfig['mat_filename'] . '.xlsx', $Importconfig['gu_filename'] . '.xlsx'] as $filename) {
                self::$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/imp/' . $filename;
                $FileType = (self::$filename === dirname($_SERVER['SCRIPT_FILENAME']) . '/imp/' . $Importconfig['os_filename'] . '.xlsx') ? self::os : ((self::$filename === dirname($_SERVER['SCRIPT_FILENAME']) . '/imp/' . $Importconfig['mat_filename'] . '.xlsx') ? self::mat : self::gu);
                self::SetFileType($FileType);
                self::Setxls();

                if (file_exists(self::$filename)) {
                    self::$filelastdate = date("Y-m-d H:i:s", filemtime(self::$filename));

                    $filelastdateFromDB = self::GetMaxFileLastDate($Importconfig);

                    if (self::$Debug || empty($filelastdateFromDB) || strtotime(self::$filelastdate) > strtotime($filelastdateFromDB)) {
                        /*   var_dump(self::$filename);
                          var_dump(self::$filelastdate);
                          var_dump($filelastdateFromDB);
                          var_dump($_SERVER);
                          var_dump($_ENV); */
                        ini_set('max_execution_time', $Importconfig['max_execution_time']);  // 1000 seconds
                        ini_set('memory_limit', $Importconfig['memory_limit']); // 1Gbyte Max Memory
                        $logreport->save();
                        self::$logreport_id = $logreport->logreport_id;
                        $doreport = true;
                        // Определяем показатели импорта
                        self::$logreport_errors = 0; // Не загружено записей из-за ошибок
                        self::$logreport_updates = 0; // Записей изменено
                        self::$logreport_additions = 0; // Записей добавлено
                        self::$logreport_missed = 0; // Записей пропущено (исключены из обработки)
                        self::$logreport_amount = 0; // Всего записей

                        if ($filename === $Importconfig['emp_filename'] . '.txt')
                            self::$employee = true;

                        if (self::$employee) {
                            $i = 0;
                            $handle = @fopen(self::$filename, "r");

                            if ($handle) {
                                $UTF8deleteBOM = true;
                                while (($subject = fgets($handle, 4096)) !== false) {
                                    if ($UTF8deleteBOM) {
                                        $subject = str_replace("\xEF\xBB\xBF", '', $subject);
                                        $UTF8deleteBOM = false;
                                    }

                                    $transaction = Yii::$app->db->beginTransaction();
                                    $i++;
                                    try {
                                        $pattern = '/^(.*?)\|(Поликлиника №\s?[1,2,3] )?(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|/ui';
                                        preg_match($pattern, $subject, $matches);

                                        if ($matches[0] !== NULL) {
                                            $pattern = '/(^Поликлиника №)\s?([1,2,3])\s?$/ui';
                                            $matches[2] = preg_replace($pattern, 'Взрослая $1$2', mb_strtolower($matches[2], 'UTF-8'));

                                            if ($matches[3] === 'Поликлиника профилактических осмотров')
                                                $matches[2] = $matches[3];

                                            $pattern = '/^(.+) БУ "Нижневартовская городская поликлиника"$/ui';
                                            $matches[3] = preg_replace($pattern, '$1', $matches[3]);

                                            $employee_fio = $matches[1];
                                            $location = self::AssignLocationForEmployeeImport(trim($matches[3]), trim($matches[2]));

                                            $id_dolzh = self::AssignDolzh(trim($matches[4]));

                                            //            $sqlstr = empty($location->id_build) ? ' and id_build is null' : ' and id_build = :id_build';

                                            $Employee = Employee::find()
                                                ->joinWith('idperson')
                                                ->where(array_merge([
                                                    'id_dolzh' => $id_dolzh,
                                                    'id_podraz' => $location->id_podraz,
                                                ], empty($location->id_build) ? [] : ['id_build' => $location->id_build]))
                                                ->andFilterWhere(['like', 'auth_user_fullname', $employee_fio, false])
                                                ->one();


                                            /*  $Employee = self::GetRowsPDO('select employee_id, auth_user_fullname, id_dolzh, id_podraz, id_build from employee inner join auth_user on employee.id_person = auth_user.auth_user_id  where auth_user_fullname like :employee_fio and id_dolzh = :id_dolzh and id_podraz = :id_podraz' . $sqlstr, array_merge([
                                              'employee_fio' => $employee_fio,
                                              'id_dolzh' => $id_dolzh,
                                              'id_podraz' => $location->id_podraz,
                                              ], empty($location->id_build) ? [] : ['id_build' => $location->id_build])); */

                                            if (empty($Employee)) {
                                                /*  var_dump('ok');
                                                  var_dump($Employee);
                                                  var_dump($employee_fio);
                                                  var_dump($location);
                                                  var_dump($id_dolzh); */

                                                $AuthuserCount = Authuser::find()
                                                    ->where(['like', 'auth_user_fullname', $employee_fio, false])
                                                    ->count();

                                                $Authuser = $AuthuserCount == 1 ? Authuser::find()
                                                    ->where(['like', 'auth_user_fullname', $employee_fio, false])
                                                    ->one() : false;

                                                $newEmployee = false;
                                                if (empty($Authuser) || $AuthuserCount > 1) {
                                                    $Authuser = new Authuser;
                                                    $Authuser->auth_user_fullname = $employee_fio;
                                                    $Authuser->auth_user_login = Proc::CreateLogin($employee_fio);
                                                    $Authuser->auth_user_password = Yii::$app->getSecurity()->generatePasswordHash('11111111');
                                                    $newEmployee = true;
                                                }

                                                $Employee = new Employee;
                                                $Employee->attributes = [
                                                    //   'employee_fio' => $employee_fio,
                                                    'id_dolzh' => $id_dolzh,
                                                    'id_podraz' => $location->id_podraz,
                                                    'id_build' => $location->id_build,
                                                    'employee_forinactive' => 1,
                                                    // 'employee_username' => 'IMPORT'
                                                ];

                                                $Employeelog = new Employeelog;
                                                $Employeelog->id_logreport = self::$logreport_id;
                                                $Employeelog->employeelog_type = 1;
                                                $Employeelog->employeelog_filename = self::$filename;
                                                $Employeelog->employeelog_filelastdate = self::$filelastdate;
                                                $Employeelog->employeelog_rownum = $i;
                                                $Employeelog->employeelog_message = 'Запись добавлена.';

                                                if (isset($Employee->scenarios()['import1c']))
                                                    $Employee->scenario = 'import1c';

                                                if (isset($Authuser->scenarios()['import1c']))
                                                    $Authuser->scenario = 'import1c';

                                                if ($Authuser->validate()) {
                                                    $Authuser->save(false);

                                                    $Profile = Profile::findOne($Authuser->primaryKey);
                                                    $Profile = empty($Profile) ? new Profile : $Profile;
                                                    $Profile->profile_id = $Authuser->primaryKey;
                                                    $Profile->profile_dr = $matches[16];
                                                    $Profile->profile_pol = $matches[15];
                                                    $Profile->profile_inn = $matches[11];
                                                    $Profile->profile_snils = $matches[12];
                                                    $Profile->profile_address = $matches[10];
                                                    $Profile->save();

                                                    if ($Profile->getErrors())
                                                        var_dump($Profile->getErrors());

                                                    $Employeelog->employeelog_message = $Profile->getErrors() ? 'Запись добавлена. Ошибка при создании профиля' : 'Запись добавлена.';

                                                    $Employee->id_person = $Authuser->getPrimaryKey();
                                                    if ($Employee->validate()) {
                                                        $newEmployee ? self::$logreport_additions++ : self::$logreport_updates++;
                                                        $Employee->save(false);

                                                        //   if ($newEmployee && !self::$Debug)
                                                        //       self::Mishanya($Authuser, $Employee, $matches);
                                                    } else {
                                                        $Employeelog->employeelog_type = 3;
                                                        $Employeelog->employeelog_message = 'Ошибка при добавлении записи: ';
                                                        foreach ($Employee->getErrors() as $fields)
                                                            $Employeelog->employeelog_message .= implode(' ', $fields) . ' ';
                                                        self::$logreport_errors++;
                                                    }
                                                } else {
                                                    $Employeelog->employeelog_type = 3;
                                                    $Employeelog->employeelog_message = 'Ошибка при добавлении записи: ';
                                                    foreach ($Authuser->getErrors() as $fields)
                                                        $Employeelog->employeelog_message .= implode(' ', $fields) . ' ';
                                                    self::$logreport_errors++;
                                                }

                                                $Employeelog->employee_fio = $Authuser->auth_user_fullname;
                                                $Employeelog->dolzh_name = Dolzh::findOne($Employee->id_dolzh)->dolzh_name; //self::GetNameByID('dolzh', 'dolzh_name', $Employee->id_dolzh);
                                                $Employeelog->podraz_name = Podraz::findOne($Employee->id_podraz)->podraz_name; //self::GetNameByID('podraz', 'podraz_name', $Employee->id_podraz);
                                                if (!empty($Employee->id_build))
                                                    $Employeelog->build_name = Build::findOne($Employee->id_build)->build_name;  //self::GetNameByID('build', 'build_name', $Employee->id_build);

                                                $Employeelog->save(false);
                                            } else {
                                                if ($Employee->employee_importdo === 1) {
                                                    if (isset($Employee->scenarios()['import1c']))
                                                        $Employee->scenario = 'import1c';

                                                    $inactivePerson = Employee::find()
                                                        ->andWhere([
                                                            'id_person' => $Employee->id_person,
                                                            'employee_dateinactive' => NULL,
                                                        ])
                                                        ->count();

                                                    if (empty($inactivePerson)) {
                                                        $Employee = Employee::find(['id_person' => $Employee->id_person])
                                                            ->andWhere([
                                                                'id_person' => $Employee->id_person,
                                                            ])
                                                            ->orderBy(['employee_id' => SORT_DESC])
                                                            ->one();

                                                        $Employeelog = new Employeelog;
                                                        $Employeelog->id_logreport = self::$logreport_id;
                                                        $Employeelog->employeelog_type = 2;
                                                        $Employeelog->employeelog_filename = self::$filename;
                                                        $Employeelog->employeelog_filelastdate = self::$filelastdate;
                                                        $Employeelog->employeelog_rownum = $i;
                                                        $Employeelog->employeelog_message = 'Запись изменена. Очищена дата неактивности специальности "' . Yii::$app->formatter->asDate($Employee->employee_dateinactive) . '"';

                                                        $Employeelog->employee_fio = Authuser::findOne($Employee->id_person)->auth_user_fullname;
                                                        $Employeelog->dolzh_name = Dolzh::findOne($Employee->id_dolzh)->dolzh_name;
                                                        $Employeelog->podraz_name = Podraz::findOne($Employee->id_podraz)->podraz_name;
                                                        if (!empty($Employee->id_build))
                                                            $Employeelog->build_name = Build::findOne($Employee->id_build)->build_name;
                                                        $Employeelog->save(false);

                                                        $Employee->employee_dateinactive = null;
                                                        self::$logreport_updates++;
                                                    }

                                                    $Employee->employee_forinactive = 1;
                                                    $Employee->save(false);
                                                }
                                            }
                                        } elseif (trim($subject) !== '') {
                                            $Employeelog = new Employeelog;
                                            $Employeelog->id_logreport = self::$logreport_id;
                                            $Employeelog->employeelog_type = 3;
                                            $Employeelog->employeelog_filename = self::$filename;
                                            $Employeelog->employeelog_filelastdate = self::$filelastdate;
                                            $Employeelog->employeelog_rownum = $i;
                                            $Employeelog->employeelog_message = 'Ошибка при добавлении записи: Не пройдено регулярное выражение /^(.*?)\|(Поликлиника №\s?[1,2,3] )?(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|(.*?)\|/ui';
                                            $Employeelog->save(false);
                                            self::$logreport_errors++;
                                        }
                                        $transaction->commit();
                                    } catch (Exception $e) {
                                        $transaction->rollBack();
                                        throw new Exception($e->getMessage() . ' $i = ' . $i . '; $filename = ' . self::$filename);
                                    }
                                }
                                fclose($handle);

                                if (!self::$Debug)
                                    self::InactiveEmployee();
                            }
                            $logreport->logreport_amount += $i;
                            $logreport->logreport_employeelastdate = self::$filelastdate;
                            self::$employee = false;
                        } else {
                            if (self::IsFileType(self::mat) || self::IsFileType(self::gu))
                                Mattraffic::updateAll(['mattraffic_forimport' => NULL], ['mattraffic_forimport' => 1]);

                            $startRow = self::IsFileType(self::os) ? self::$os_start : (self::IsFileType(self::mat) ? self::$mat_start : self::$gu_start);   //начинаем читать с определенной строки
                            $exit = false;   //флаг выхода
                            $empty_value = 0;  //счетчик пустых знаений
                            // Загружаем данные из файла Excel
                            //        $inputFileType = 'Excel5';
                            //       $inputFileName = self::$filename;
                            $chunkSize = 1000;  //размер считываемых строк за раз
                            //
                            $objReader = \PHPExcel_IOFactory::createReaderForFile(self::$filename);
                            //     $objReader = \PHPExcel_IOFactory::createReader($inputFileType);

                            $chunkFilter = new chunkReadFilter();
                            $objReader->setReadFilter($chunkFilter);
                            $objReader->setReadDataOnly(true);


                            while (!$exit) {
                                // Инициализируем переменные
                                //  $row = $sheetData[self::$rownum_xls];

                                $chunkFilter->setRows($startRow, $chunkSize);  //устанавливаем знаечние фильтра
                                $objPHPExcel = $objReader->load(self::$filename);  //открываем файл
                                $objPHPExcel->setActiveSheetIndex(0);  //устанавливаем индекс активной страницы
                                $objWorksheet = $objPHPExcel->getActiveSheet(); //делаем активной нужную страницу
                                // Идем по данных excel
                                for ($i = $startRow; $i < $startRow + $chunkSize; $i++) {  //внутренний цикл по строкам
                                    self::$rownum_xls = $i;
                                    $value = trim(htmlspecialchars($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()));  //получаем первое знаение в строке
                                    if (empty($value))  //проверяем значение на пустоту
                                        $empty_value++;
                                    if ($empty_value == 1) {  //после трех пустых значений, завершаем обработку файла, думая, что это конец
                                        $exit = true;
                                        break;
                                    }
                                    /* Манипуляции с данными каким Вам угодно способом, в PHPExcel их превеликое множество */

                                    $row = $objWorksheet->rangeToArray('A' . $i . ':K' . $i, null, true, true, true);
                                    $row = $row[key($row)];

                                    $material = new Material;
                                    $authuser = new Authuser;
                                    $employee = new Employee;
                                    $mattraffic = new Mattraffic;
                                    $matlog = new Matlog;
                                    $employeelog = new Employeelog;
                                    $traflog = new Traflog;

                                    $MaterialDo = false;
                                    $EmployeeDo = false;
                                    $MattrafficDo = false;

                                    // Начинаем транзакцию
                                    $transaction = Yii::$app->db->beginTransaction();
                                    try {

                                        // Применяем значения атрубутов Материальной ценности
                                        $MaterialDo = self::MaterialDo($material, $matlog, $row);
                                        if ($MaterialDo) {
                                            // Применяем значения атрубутов Сотрудника
                                            $EmployeeDo = self::EmployeeDo($employee, $employeelog, $row);
                                            if ($EmployeeDo) {
                                                // Применяем значения атрубутов "Операции над материальной ценностью"
                                                $MattrafficDo = self::MattrafficDo($mattraffic, $traflog, $row, $material, $employee->employee_id);
                                            }
                                        }


                                        // $matlog->matlog_type !== 5 - Если Запись не изменилась не пишем в лог
                                        if ($matlog->matlog_type !== 5 && ($MaterialDo || (count($material->getErrors()) > 0))) {
                                            $matlog->save(false);
                                            if ($matlog->matlog_type === 2) {
                                                $material->save(false);
                                            }
                                        }

                                        if ($MaterialDo) {
                                            // $employeelog->employeelog_type !== 5 - Если Запись не изменилась не пишем в лог
                                            if ($employeelog->employeelog_type !== 5 && ($EmployeeDo || (count($employee->getErrors()) > 0))) {

                                                $employeelog->save(false);
                                            }

                                            if ($EmployeeDo) {
                                                if ($MattrafficDo || (count($mattraffic->getErrors()) > 0)) {
                                                    if ($matlog->IsNewRecord) {
                                                        $matlog->material_number = $material->material_number; // Иначе пишется предыдущее значение количества материальной ценности
                                                        $matlog->save(false);
                                                    }

                                                    if ($employeelog->IsNewRecord)
                                                        $employeelog->save(false);
                                                    $traflog->id_matlog = $matlog->matlog_id;
                                                    $traflog->id_employeelog = $employeelog->employeelog_id;
                                                    if ($traflog->validate())
                                                        $traflog->save(false);
                                                }

                                                if ($MattrafficDo) {
                                                    $material->save(false);
                                                    $employee->save(false);
                                                    $mattraffic->id_material = $material->material_id;
                                                    $mattraffic->id_mol = $employee->employee_id;
                                                    $mattraffic->save(false);

                                                    // Применяем значения атрубутов, если материальная ценность списна
                                                    self::WriteOffDo($material, $matlog, $mattraffic, $traflog, $row);
                                                }
                                            }
                                        }
                                        //    if ($transaction->isActive)
                                        $transaction->commit();
                                    } catch (Exception $e) {
                                        $transaction->rollBack();
                                        throw new Exception($e->getMessage() . ' $rownum_xls = ' . self::$rownum_xls . '; $filename = ' . self::$filename);
                                    }
                                }
                                $objPHPExcel->disconnectWorksheets();     //чистим
                                unset($objPHPExcel);       //память

                                unset($material);
                                unset($employee);
                                unset($mattraffic);
                                unset($matlog);
                                unset($employeelog);
                                unset($traflog);
                                unset($objWorksheet);

                                echo '<BR>Память использована с ' . $startRow . ' по ' . ($startRow + $chunkSize) . ' : ' . Yii::$app->formatter->asShortSize(memory_get_usage(true));
                                $startRow += $chunkSize;     //переходим на следующий шаг цикла, увеличивая строку, с которой будем читать файл
                            }
                            $logreport->logreport_amount += self::$rownum_xls - (self::IsFileType(self::os) ? self::$os_start : (self::IsFileType(self::mat) ? self::$mat_start : self::$gu_start));
                            if (self::IsFileType(self::os))
                                $logreport->logreport_oslastdate = self::$filelastdate;
                            elseif (self::IsFileType(self::mat))
                                $logreport->logreport_matlastdate = self::$filelastdate;
                            else
                                $logreport->logreport_gulastdate = self::$filelastdate;


                            if (self::IsFileType(self::mat) || self::IsFileType(self::gu))
                                self::MaterialSpisanie();
                        }
                        $logreport->logreport_additions += self::$logreport_additions;
                        $logreport->logreport_updates += self::$logreport_updates;
                        $logreport->logreport_errors += self::$logreport_errors;
                        $logreport->logreport_missed += self::$logreport_missed;

                        $logreport->save();
                    } else {
                        if (self::IsFileType(self::os))
                            $logreport->logreport_oslastdate = self::$filelastdate;
                        elseif (self::IsFileType(self::mat))
                            $logreport->logreport_matlastdate = self::$filelastdate;
                        else
                            $logreport->logreport_gulastdate = self::$filelastdate;

                        if ($filename === $Importconfig['emp_filename'] . '.txt')
                            $logreport->logreport_employeelastdate = self::$filelastdate;
                    }
                }
            }

            if ($doreport) {
                self::MakeReport();
                $endtime = microtime(true);
                $logreport->logreport_executetime = gmdate('H:i:s', $endtime - $starttime);
                $logreport->logreport_memoryused = memory_get_usage(true);
                $logreport->save();
            }
            $endtime = microtime(true);

            echo 'ImportDo success<BR>';
            echo 'Использовано памяти: ' . Yii::$app->formatter->asShortSize(memory_get_usage(true)) . '; Время выполнения: ' . gmdate('H:i:s', $endtime - $starttime);
        } else
            echo 'Импорт отключен';
    }

    private static function ExcelApplyValues($sheet, $rows, $params = [])
    {

        /* Границы таблицы */
        $ramka = array(
            'borders' => array(
                'allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN)
            )
        );
        /* Жирный шрифт для шапки таблицы */
        $font = array(
            'font' => array(
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );

        $Matlog = new Matlog;
        $Employeelog = new Employeelog;
        $Traflog = new Traflog;

        $attributeslabels = array_merge(
            $Matlog->attributeLabels(), $Employeelog->attributeLabels(), $Traflog->attributeLabels()
        );

        if (count($rows) > 0) {
            $col = 0;
            foreach (array_keys($rows[0]) as $attr) {
                if (!in_array($attr, ['idmatlog', 'idemployeelog'])) {
                    $sheet->setCellValueByColumnAndRow($col, 1, $attributeslabels[$attr]);
                    $sheet->getStyleByColumnAndRow($col, 1)->applyFromArray($ramka);
                    $sheet->getStyleByColumnAndRow($col, 1)->applyFromArray($font);
                    $col++;
                }
            }
        }

        if (count($rows) > 0) {
            foreach ($rows as $i => $row) {
                $col = 0;
                foreach ($row as $attr => $value) {
                    if (!in_array($attr, ['idmatlog', 'idemployeelog'])) {
                        if (isset($params['date']) && in_array($attr, $params['date']) && $value !== NULL)
                            $rows[$i][$attr] = date('d.m.Y', strtotime($value));

                        if (isset($params['datetime']) && in_array($attr, $params['datetime']) && $value !== NULL)
                            $rows[$i][$attr] = date('d.m.Y H:i:s', strtotime($value));

                        if (isset($params['case']) && in_array($attr, array_keys($params['case'])))
                            $rows[$i][$attr] = $params['case'][$attr][$value];

                        $sheet->getStyleByColumnAndRow($col, $i + 2)->applyFromArray($ramka);

                        if (isset($params['string']) && in_array($attr, $params['string'])) {
                            $sheet->getStyleByColumnAndRow($col, $i + 2)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                            $sheet->setCellValueExplicitByColumnAndRow($col, $i + 2, $rows[$i][$attr], \PHPExcel_Cell_DataType::TYPE_STRING);
                        } else
                            $sheet->setCellValueByColumnAndRow($col, $i + 2, $rows[$i][$attr]);
                        $col++;
                    }
                }
            }
        }

        if (count($rows) > 0) {
            $c = count(array_keys($rows[0])); // ??????????????????

            /* Авторазмер колонок Excel */
            \PHPExcel_Shared_Font::setAutoSizeMethod(\PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
            foreach (range(0, $c) as $col) {
                $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
                $sheet->calculateColumnWidths();
                if ($sheet->getColumnDimensionByColumn($col)->getWidth() > 70) {
                    $sheet->getColumnDimensionByColumn($col)->setAutoSize(false);
                    $sheet->getColumnDimensionByColumn($col)->setWidth(70);
                }
            }
        }
    }

    private static function MakeReport()
    {
        $Importconfig = Importconfig::findOne(1);

        /*   ini_set('max_execution_time', $Importconfig->max_execution_time);  // 1000 seconds
          ini_set('memory_limit', $Importconfig->memory_limit); // 1Gbyte Max Memory */

        /* Загружаем PHPExcel */
        $objPHPExcel = new \PHPExcel();

        /* Границы таблицы */
        $ramka = array(
            'borders' => array(
                'allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN)
            )
        );
        /* Жирный шрифт для шапки таблицы */
        $font = array(
            'font' => array(
                'bold' => true
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );

        $logreport = Logreport::find()
            ->orderBy('logreport_id desc')
            ->asArray()
            ->one();

        $itog = $objPHPExcel->getActiveSheet();
        $itog->setTitle('Итоги');
        /*    $matsheet = $objPHPExcel->createSheet(1);
          $matsheet->setTitle('Материальные ценности');
          $empsheet = $objPHPExcel->createSheet(2);
          $empsheet->setTitle('Сотрудники');
          $trafsheet = $objPHPExcel->createSheet(3);
          $trafsheet->setTitle('Операции над мат. ценностями'); */

        $itog->setCellValueByColumnAndRow(0, 1, 'Отчет импорта №' . $logreport['logreport_id']);
        $itog->setCellValueByColumnAndRow(0, 2, 'Дата: ' . date('d.m.Y', strtotime($logreport['logreport_date'])));
        $itog->getStyle('A2')->applyFromArray(array(
            'font' => array(
                'italic' => true,
                'size' => 12
            )
        ));

        $LogreportAR = new Logreport;
        $itog->setCellValueByColumnAndRow(0, 4, $LogreportAR->getAttributeLabel('logreport_amount'));
        $itog->setCellValueByColumnAndRow(1, 4, $LogreportAR->getAttributeLabel('logreport_additions'));
        $itog->setCellValueByColumnAndRow(2, 4, $LogreportAR->getAttributeLabel('logreport_updates'));
        $itog->setCellValueByColumnAndRow(3, 4, $LogreportAR->getAttributeLabel('logreport_errors'));
        $itog->setCellValueByColumnAndRow(4, 4, $LogreportAR->getAttributeLabel('logreport_missed'));

        $itog->setCellValueByColumnAndRow(0, 5, $logreport['logreport_amount']);
        $itog->setCellValueByColumnAndRow(1, 5, $logreport['logreport_additions']);
        $itog->setCellValueByColumnAndRow(2, 5, $logreport['logreport_updates']);
        $itog->setCellValueByColumnAndRow(3, 5, $logreport['logreport_errors']);
        $itog->setCellValueByColumnAndRow(4, 5, $logreport['logreport_missed']);

        $itog->getStyle('A4:E5')->applyFromArray($ramka);
        $itog->getStyle('A4:E4')->applyFromArray($font);

        $itog->getStyle('A1')->applyFromArray(array(
            'font' => array(
                'bold' => true,
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        ));
        $itog->mergeCells('A1:E1');

        $c = count($logreport);

        /* Авторазмер колонок Excel */
        foreach (range(0, $c) as $col)
            $itog->getColumnDimensionByColumn($col)->setAutoSize(true);

        //      ----------------  Материальные ценности ------------------------------------------
        $rows = Matlog::find()
            ->select(['matlog_filename', 'matlog_filelastdate', 'matlog_rownum', 'matlog_message', 'material_name1c', 'material_1c', 'material_inv', 'material_serial', 'material_release', 'material_number', 'material_price', 'material_tip', 'izmer_name', 'matvid_name'])
            ->where(['id_logreport' => $logreport['logreport_id']])
            ->asArray()
            ->all();

        if (count($rows) > 0) {
            $matsheet = $objPHPExcel->createSheet(1);
            $matsheet->setTitle('Материальные ценности');

            self::ExcelApplyValues($matsheet, $rows, [
                'date' => ['material_release'],
                'datetime' => ['matlog_filelastdate'],
                'string' => ['material_1c', 'material_inv', 'material_serial'],
                'case' => ['material_tip' => [
                    1 => 'Основное средство',
                    2 => 'Материал',
                    3 => 'Групповой учет',
                ]]
            ]);
        }

        // ----------------------- Сотрудники -------------------------------

        $rows = Employeelog::find()
            ->select(['employeelog_filename', 'employeelog_filelastdate', 'employeelog_rownum', 'employeelog_message', 'employee_fio', 'dolzh_name', 'podraz_name', 'build_name'])
            ->where(['id_logreport' => $logreport['logreport_id']])
            ->asArray()
            ->all();

        if (count($rows) > 0) {
            $empsheet = $objPHPExcel->createSheet(2);
            $empsheet->setTitle('Сотрудники');
            self::ExcelApplyValues($empsheet, $rows, [
                'datetime' => ['employeelog_filelastdate'],
            ]);
        }

        // ---------------------- Операции над материальными ценностями -------------------------------------------
        $rows = Traflog::find()
            ->select(['traflog_filename', 'traflog_rownum', 'traflog_message', 'mattraffic_number', 'material_name1c', 'material_1c', 'material_inv', 'material_number', 'employee_fio', 'dolzh_name', 'podraz_name', 'build_name'])
            ->joinWith(['idmatlog', 'idemployeelog'])
            ->where(['traflog.id_logreport' => $logreport['logreport_id']])
            ->createCommand()
            ->queryAll();

        if (count($rows) > 0) {
            $trafsheet = $objPHPExcel->createSheet(3);
            $trafsheet->setTitle('Операции над мат. ценностями');
            self::ExcelApplyValues($trafsheet, $rows, [
                    'string' => ['material_1c', 'material_inv']]
            );
        }

        /* присваиваем имя файла от имени модели */
        $FileName = 'Отчет импорта в систему Фрегат N' . $logreport['logreport_id'];
        // Устанавливаем имя листа
        //  $itog->setTitle($FileName);
        // Выбираем первый лист
        $objPHPExcel->setActiveSheetIndex(0);
        /* Формируем файл Excel */
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        /* Proc::SaveFileIfExists() - Функция выводит подходящее имя файла, которое еще не существует. mb_convert_encoding() - Изменяем кодировку на кодировку Windows */
        $FileName = DIRECTORY_SEPARATOR === '/' ? $FileName : mb_convert_encoding($FileName, 'Windows-1251', 'UTF-8');
        $fileroot = Proc::SaveFileIfExists('importreports/' . $FileName . '.xlsx');
        /* Сохраняем файл в папку "files" */
        $objWriter->save('importreports/' . $fileroot);
        /* Возвращаем имя файла Excel */
        if (DIRECTORY_SEPARATOR === '/')
            echo '<BR>' . $fileroot . '<BR>';
        else
            echo '<BR>' . mb_convert_encoding($fileroot, 'UTF-8', 'Windows-1251') . '<BR>';
    }

    // Функция удаляет последние отчеты
    static private function DeleteOldReports()
    {
        $countreports = Importconfig::findOne(1);
        if (!empty($countreports)) {
            $files = glob('importreports/*.xlsx');

            if (count($files) > $countreports->logreport_reportcount)
                $ToDelete = Logreport::find()
                    ->select(['logreport_id'])
                    ->orderBy(['logreport_id' => SORT_ASC])
                    ->limit(count($files) - $countreports->logreport_reportcount)
                    ->asArray()
                    ->all();

            if (!empty($ToDelete)) {
                foreach ($ToDelete as $row) {
                    Traflog::deleteAll(['id_logreport' => $row['logreport_id']]);
                    Matlog::deleteAll(['id_logreport' => $row['logreport_id']]);
                    Employeelog::deleteAll(['id_logreport' => $row['logreport_id']]);
                    Logreport::findOne($row['logreport_id'])->delete();

                    $fileroot = 'importreports/Отчет импорта в систему Фрегат N' . $row['logreport_id'] . '.xlsx';

                    if (DIRECTORY_SEPARATOR !== '/')
                        $fileroot = mb_convert_encoding($fileroot, 'Windows-1251', 'UTF-8');

                    unlink($fileroot);
                }
            }
        }
    }

}
