<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once Yii::app()->basePath . '/extensions/PHPExcel/Classes/PHPExcel/Reader/IReadFilter.php';

// Класс для чтения Excel файла по частям для экономии памяти
class chunkReadFilter implements PHPExcel_Reader_IReadFilter {

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

class FregatImport {

    private static $filename = ''; // Имя файла 'imp/os.xls' - (Основные средства); 'imp/mat.xls' = (Материалы);
    private static $os = false; // true -  'imp/os.xls' (Основные средства); false - 'imp/mat.xls' (Материалы);
    private static $logreport_id = 0; // ID отчета
    private static $employeewords = []; // Грузим правила импорта для сотрудников
    private static $logreport_errors = 0; // Не загружено записей из-за ошибок
    private static $logreport_updates = 0; // Записей изменено
    private static $logreport_additions = 0; // Записей добавлено
    private static $logreport_missed = 0; // Записей пропущено (исключены из обработки)
    private static $logreport_amount = 0; // Всего записей
    private static $os_start = 6; // Ряд с которого необходимо начать прохождение файла Excel Основных средств
    private static $mat_start = 9; // Ряд с которого необходимо начать прохождение файла Excel Материалов
    private static $material_number_xls; // Пишется количество материальной ценности из файла Excel
    private static $material_price_xls; // Пишется цена материальной ценности из файла Excel
    private static $rownum_xls; // Номер строки в файле Excel
    private static $mattraffic_exist; // Проверка, найден ли у материальнной ценности сотрудник

    // Массив с координатами колонок в Excel

    private static function xls($field) {
        $xls = [
            'mattraffic_date' => 'A',
            'material_1c' => self::$os ? 'B' : 'F',
            'material_inv' => self::$os ? 'C' : 'G',
            'material_name1c' => self::$os ? 'D' : 'H',
            'material_number' => self::$os ? '' : 'K',
            'material_price' => self::$os ? 'E' : 'L',
            'izmer_name' => self::$os ? '' : 'J',
            'employee_fio' => self::$os ? 'H' : 'C',
            'dolzh_name' => self::$os ? 'I' : 'D',
            'podraz_name' => self::$os ? 'J' : 'I',
            'material_serial' => self::$os ? 'M' : '',
            'material_release' => self::$os ? 'N' : '',
            'material_status' => self::$os ? 'L' : '',
            'material_tip_nomenklaturi' => self::$os ? '' : 'B', // Колонка "ТипНоменклатуры" в файле Материалов
        ];
        if (isset($xls[$field]))
            return $xls[$field];
        else
            throw new CException('Не существует FregatImport::xls("' . $field . '")');
    }

    // Определяем вид материальной ценности
    // Разбиваем наименование на слова
    // Ищем в базе словосочетаний с первого слова, прибавляя по одному слову
    // Если не нашли, то ставится ИД = 1, т. е. "Не определен"
    private static function AssignMatvid($material_name1c) {
        // Определяем Вид материальной ценности
        Proc::mb_preg_match_all('/[а-яА-Яa-zA-Z\d]+/u', $material_name1c, $match_arr, PREG_OFFSET_CAPTURE, 0, 'UTF-8');

        $i = 0;
        $tmpmin = [1, ''];
        while ($i < count($match_arr[0])) {
            $word = $match_arr[0][$i];
            $str = mb_substr($material_name1c, 0, $word[1], 'UTF-8') . $word[0];
            $rows = Importmaterial::model()->FindAll([
                'condition' => "importmaterial_combination LIKE :importmaterial_combination",
                'params' => [':importmaterial_combination' => $str . '%'],
                'order' => 'CHAR_LENGTH(importmaterial_combination)'
            ]);

            if (count((array) $rows) === 1 && mb_stripos($material_name1c, $rows[0]['importmaterial_combination'], 0, 'UTF-8') === 0 || count((array) $rows) > 1)
                $tmpmin = [$rows[0]['id_matvid'], $rows[0]['importmaterial_combination']];
            else
                break;
            
            $i++;
        }
        
        // Если Вид материальной ценности не определен, то ставится ключ 1 со значением "Не определен"
        return $tmpmin[0];
    }

    // Определяем Единицу измерения
    // Если единица измерения не найдена в справочнике, она добавляется.
    private static function AssignIzmer($value) {
        $izmer_id = Izmer::model()->Find('izmer_name like :izmer_name', [':izmer_name' => $value]);

        if ($izmer_id === null) {
            $Izmer = new Izmer;
            $Izmer->izmer_name = $value;
            if ($Izmer->Save())
                $izmer_id = $Izmer->izmer_id;
        } else
            $izmer_id = $izmer_id->izmer_id;
        return $izmer_id;
    }

    // Определяем должность сотрудника
    // Если должность не найдена в справочнике, она добавляется
    private static function AssignDolzh($value) {
        $dolzh_id = NULL;
        if (trim($value) !== '') {
            $dolzh_id = Dolzh::model()->Find('dolzh_name like :dolzh_name', [':dolzh_name' => $value]);

            if ($dolzh_id === null) {
                $Dolzh = new Dolzh;
                $Dolzh->dolzh_name = $value;
                if ($Dolzh->Save())
                    $dolzh_id = $Dolzh->dolzh_id;
            } else
                $dolzh_id = $dolzh_id->dolzh_id;
        }

        return $dolzh_id;
    }

    // Определяем местонахождение сотрудника (Подразделение и Здание)
    // Местонахождение определяется по следующему алгоритму:
    // 1) Наименование Подразделения изется в таблице "importemployee", если не найдено создается новое подразделение, Здание ставится NULL
    // 2) Если найдено:
    // - Если в таблице "importemployee" стоит значение "id_employee", то подразделение и здание берется в соответствии с ИД сотрудника (id_employee)
    // - Если в таблице "importemployee" стоят значения "id_podraz", "id_build", то подразделение и здание берется в соответствии с ИД Подразделения (id_podraz) и ИД Здания (id_build)
    private static function AssignLocation($podraz_name, $employeewords, $employee_fio) {
        $result = (object) [
                    'id_podraz' => NULL,
                    'id_build' => NULL
        ];

        $importemployee = Importemployee::model()->Find('importemployee_combination like :importemployee_combination', [':importemployee_combination' => $podraz_name]);
        if ($importemployee === null) {

            $currentpodraz = Podraz::model()->Find('podraz_name like :podraz_name', [':podraz_name' => $podraz_name]);

            if ($currentpodraz === null) {
                $Podraz = new Podraz;
                $Podraz->podraz_name = $podraz_name;
                if ($Podraz->Save())
                    $result->id_podraz = $Podraz->podraz_id;
            } else {
                $result->id_podraz = $currentpodraz->podraz_id;
            }
        } else {
          //  if ($importemployee->id_employee === null) {
                $result->id_podraz = $importemployee->id_podraz;
                $result->id_build = $importemployee->id_build;
          /*  } else {
                $currentemployee = Employee::model()->Find('employee_id = :employee_id', [':employee_id' => $importemployee->id_employee]);
                if ($currentemployee !== null) {
                    $result->id_podraz = $currentemployee->id_podraz;
                    $result->id_build = $currentemployee->id_build;
                }*/
            }
        

        return $result;
    }

    // Определяем ИД Подразделения и Здания по их именам, и добавляем новые, если их нет
    private static function AssignLocationForEmployeeImport($podraz_name, $build_name) {
        $result = (object) [
                    'id_podraz' => NULL,
                    'id_build' => NULL
        ];

        if (trim($podraz_name) !== '') {
            $currentpodraz = Podraz::model()->Find('podraz_name like :podraz_name', [':podraz_name' => $podraz_name]);

            if ($currentpodraz === null) {
                $Podraz = new Podraz;
                $Podraz->podraz_name = $podraz_name;
                if ($Podraz->Save())
                    $result->id_podraz = $Podraz->podraz_id;
            } else
                $result->id_podraz = $currentpodraz->podraz_id;

            if (trim($build_name) !== '') {
                $currentbuild = Build::model()->Find('build_name like :build_name', [':build_name' => $build_name]);

                if ($currentbuild === null) {
                    $Build = new Build;
                    $Build->build_name = $build_name;
                    if ($Build->Save())
                        $result->id_build = $Build->build_id;
                } else
                    $result->id_build = $currentbuild->build_id;
            }
        }

        return $result;
    }

    // Читаем дату в Excel и переводим в формат PHP
    // Допустимые значения в Excel - Строка содержащая подстроку формата "ДД.ММ.ГГГГ" или число формата даты Excel (Количество дней с 01.01.1900 года)
    private static function GetDateFromExcel($value) {
        $preg = '/(\d{2})\.(\d{2})\.(\d{4})(.*)/i';
        if (preg_match($preg, $value))
            return preg_replace($preg, '$3-$2-$1', $value);
        else
            return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($value));

        //   return PHPExcel_Shared_Date::isDateTime($objPHPExcel->getActiveSheet()->getcell('N' . $r)) ? date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getcell('N' . $r)->getValue())) : preg_replace($preg, '$3-$2-$1', $value);
    }

    // Читаем значения колонок соответствующие Материальной ценности в файле Excel
    private static function xls_attributes_material($row) {
        // Определяем количество материальной ценности
        if (self::$os)
            $material_price = trim($row[self::xls('material_price')]);
        else  // Если материалы, то цену извлекаем из суммы (сумма / количество)  
        if (trim($row[self::xls('material_number')]) != '0')
            $material_price = (is_numeric(trim($row[self::xls('material_price')])) && is_numeric(trim($row[self::xls('material_number')]))) ? round(trim($row[self::xls('material_price')]) / trim($row[self::xls('material_number')]), 2) : 'Ошибка при делении числа "' . trim($row[self::xls('material_price')]) . '" на число "' . trim($row[self::xls('material_number')]) . '"';
        else
            $material_price = 0;

        // Присваиваем значения в массив атрибутов
        return [
            'material_name1c' => trim($row[self::xls('material_name1c')]),
            'material_1c' => trim($row[self::xls('material_1c')]),
            'material_inv' => mb_strtolower(trim($row[self::xls('material_inv')]), 'UTF-8') === 'null' ? trim($row[self::xls('material_1c')]) : trim($row[self::xls('material_inv')]),
            'material_serial' => (self::$os && !in_array(mb_strtolower(trim($row[self::xls('material_serial')]), 'UTF-8'), ['null', 'б/н', 'б\н', 'б/н.', 'б\н.', '-'])) ? trim($row[self::xls('material_serial')]) : NULL,
            'material_release' => self::$os ? self::GetDateFromExcel(trim($row[self::xls('material_release')])) : NULL, // Определяем дату выпуска материальной ценности и переводим в формат PHP из формата Excel
            'material_number' => self::$os ? '1' : trim($row[self::xls('material_number')]), // Определяем количество материальной ценности
            'material_price' => $material_price,
            'material_tip' => self::$os ? 1 : 2, // Определяем тип материальной ценности (1 - Основное средство, 2 - Материал)
            'id_matvid' => self::AssignMatvid(trim($row[self::xls('material_name1c')])), // Определяем Вид материальной ценности согласно таблицы соответствий importmaterial, если Вид материальной ценности не определен, то ставится ключ 1 со значением "Не определен"
            'id_izmer' => self::$os ? 1 : self::AssignIzmer(trim($row[self::xls('izmer_name')])), // Определяем Единицу измерения
            'material_tip_nomenklaturi' => self::$os ? '' : trim($row[self::xls('material_tip_nomenklaturi')]), // Колонка "ТипНоменклатуры" в файле Материалов
        ];
    }

    // Читаем значения колонок соответствующие Сотруднику в файле Excel
    private static function xls_attributes_employee($row) {
        $employee_fio = trim($row[self::xls('employee_fio')]);

        $location = self::AssignLocation(trim($row[self::xls('podraz_name')]), self::$employeewords, $employee_fio);

        return [
            'employee_fio' => $employee_fio, // ФИО сотрудника
            'id_dolzh' => self::AssignDolzh(trim($row[self::xls('dolzh_name')])), // Определяем ID должности сотрудника
            'id_podraz' => $location->id_podraz, // Определяем ID подразделение сотрудника
            'id_build' => $location->id_build // Определяем ID строения, в котором находится сотрудник
        ];
    }

    // Читаем значения колонок соответствующие Операции над материальной ценностью в файле Excel
    private static function xls_attributes_mattraffic($row) {
        return [
            'mattraffic_date' => self::GetDateFromExcel(trim($row[self::xls('mattraffic_date')])), // Определяем дату операции c материальной ценностью и переводим в формат PHP из формата Excel
            'mattraffic_number' => self::$os ? 1 : trim($row[self::xls('material_number')]), // Количество материала, задействованное в операции
        ];
    }

    // Добавляем в лог не измененные значения ActiveRecord
    private static function JustAddToLog($ar_Model, &$ar_LogModel) {
        $prop = mb_strtolower(get_class($ar_LogModel), 'UTF-8') . '_';
        $ar_LogModel->id_logreport = self::$logreport_id;
        $ar_LogModel[$prop . 'type'] = 5;
        $ar_LogModel[$prop . 'filename'] = self::$filename;
        $ar_LogModel[$prop . 'rownum'] = self::$rownum_xls;
        if ($ar_LogModel[$prop . 'message'] === '' || $ar_LogModel[$prop . 'message'] === NULL)
            $ar_LogModel[$prop . 'message'] = 'Запись не изменялась. ';
        return true;
    }

    // Валидируем значения модели и пишем в лог
    private static function ImportValidate($ar_Model, &$ar_LogModel) {
        $result = false;
        $prop = mb_strtolower(get_class($ar_LogModel), 'UTF-8') . '_';
        $ar_LogModel->id_logreport = self::$logreport_id;
        $ar_LogModel[$prop . 'type'] = $ar_Model->isNewRecord ? 1 : 2;
        $ar_LogModel[$prop . 'filename'] = self::$filename;
        $ar_LogModel[$prop . 'rownum'] = self::$rownum_xls;
        if ($ar_LogModel[$prop . 'message'] === '' || $ar_LogModel[$prop . 'message'] === NULL)
            $ar_LogModel[$prop . 'message'] = $ar_Model->isNewRecord ? 'Запись добавлена.' : 'Запись изменена: ';

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
    private static function GetCountMaterialByID($MaterialID) {
        $sql = 'select sum(mattraffic_number) as material_number from (select * from (select * from mattraffic m1 order by m1.mattraffic_date desc) temp group by id_material, id_mol) temp2 where id_material = :materialID group by id_material';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":materialID", $MaterialID);
        $dataReader = $command->queryRow();
        if ($dataReader === false)
            return '-1';
        else
            return $dataReader['material_number'];
    }

    // Применяем изменения в атрибутах материальной ценности или создаем новую
    // Пишем в лог
    private static function MaterialDo(&$Material, &$Matlog, $row) {
        $result = false;
        // Присваиваем значения свойств материальной ценности из Excel в массив атрибутов
        $xls_attributes_material = self::xls_attributes_material($row);
        // Проверяем, что ТипНоменклатуры Материалов принадлежат к "Продукты питания" или "Прочие материальные запасы"
        $material_assigned = (self::$os || (!self::$os && in_array($xls_attributes_material['material_tip_nomenklaturi'], ['Продукты питания', 'Прочие материальные запасы']))) ? true : false;

        if ($material_assigned) {
            // Находим материальную ценность в базе по коду 1С, если не находим создаем новую запись
            $find_ar = Material::model()->Find('material_1c = :material_1c', [':material_1c' => $xls_attributes_material['material_1c']]);

            if ($find_ar !== null)
                $Material = $find_ar;

            self::$material_number_xls = $xls_attributes_material['material_number'];
            self::$material_price_xls = $xls_attributes_material['material_price'];

            // Если материальная ценность найдена
            if (!$Material->isNewRecord) {
                $Material->material_price = floatval($Material->material_price); // т.к. $Material->material_price = "~.00"
                $Material->material_number = floatval($Material->material_number); // т.к. $Material->material_number = "1.000"
                // Убераем атрибуты, чтобы он не попали в $diff_attr
                unset($xls_attributes_material['material_tip_nomenklaturi']);
                unset($xls_attributes_material['material_number']);
                unset($xls_attributes_material['material_price']);

                // Массив заполняется измененными значениями атрибутов материальной ценности
                $diff_attr = array_diff_assoc($xls_attributes_material, $Material->attributes);
            }

            // Если новая запись или произошли изменения в текущей
            if ($Material->isNewRecord || count((array) $diff_attr) > 0) {
                $Material->attributes = $xls_attributes_material;

                // material_name1с - Наименование из Excel файла. material_name - Изменяемое наименование пользователем в БД
                if ($Material->material_name === '' || $Material->material_name === NULL)
                    $Material->material_name = $Material->material_name1c;

                $Matlog->attributes = $xls_attributes_material;
                $Matlog->material_number = self::$material_number_xls;
                $Matlog->material_price = self::$material_price_xls;

                $Matlog->matvid_name = Matvid::model()->findbyPk($Material->id_matvid)->matvid_name;
                $Matlog->izmer_name = Izmer::model()->findbyPk($Material->id_izmer)->izmer_name;
                $Matlog->material_writeoff = 'Нет';

                $Matlog->matlog_message = $Material->isNewRecord ? 'Запись добавлена' : 'Запись изменена: ';
                if (!$Material->isNewRecord)
                    foreach ($diff_attr as $attr => $value)
                        $Matlog->matlog_message .= '[' . Material::model()->getAttributeLabel($attr) . '] = "' . $value . '", ';

                // Валидируем значения модели и пишем в лог
                $result = self::ImportValidate($Material, $Matlog);
            } else { // Если изменения не внесены пишем в лог
                $Matlog->attributes = $Material->attributes;
                $Matlog->matvid_name = Matvid::model()->findbyPk($Material->id_matvid)->matvid_name;
                $Matlog->izmer_name = Izmer::model()->findbyPk($Material->id_izmer)->izmer_name;
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
    private static function EmployeeDo(&$Employee, &$Employeelog, $row) {
        $result = false;
        $xls_attributes_employee = self::xls_attributes_employee($row);

        // На случай если Здание у сотрудника не определено
        $buildsql = $xls_attributes_employee['id_build'] === null ? 'id_build IS NULL' : 'id_build = :id_build';

        // Находим сотрудника в базе, если не находим создаем новую запись
        $find_ar = Employee::model()->Find('employee_fio LIKE :employee_fio and id_dolzh = :id_dolzh and id_podraz = :id_podraz and ' . $buildsql, array_merge([
            ':employee_fio' => $xls_attributes_employee['employee_fio'],
            ':id_dolzh' => $xls_attributes_employee['id_dolzh'],
            ':id_podraz' => $xls_attributes_employee['id_podraz']
                        ], $xls_attributes_employee['id_build'] === null ? [] : [':id_build' => $xls_attributes_employee['id_build']]));

        if ($find_ar !== null)
            $Employee = $find_ar;

        if ($Employee->isNewRecord) { //Если новая запись (Нет соответствия по ФИО, Должности, Подразделению, Зданию)
            $Employee->attributes = $xls_attributes_employee;

            $Employeelog->employee_fio = $Employee->employee_fio;
            $Employeelog->dolzh_name = Dolzh::model()->findbyPk($Employee->id_dolzh)->dolzh_name;
            $Employeelog->podraz_name = Podraz::model()->findbyPk($Employee->id_podraz)->podraz_name;
            if ($Employee->id_build !== null)
                $Employeelog->build_name = Build::model()->findbyPk($Employee->id_build)->build_name;

            // Валидируем значения модели и пишем в лог
            $result = self::ImportValidate($Employee, $Employeelog);
        } else { // Если изменения не внесены пишем в лог
            $Employeelog->attributes = $Employee->attributes;
            $Employeelog->dolzh_name = Dolzh::model()->findbyPk($Employee->id_dolzh)->dolzh_name;
            $Employeelog->podraz_name = Podraz::model()->findbyPk($Employee->id_podraz)->podraz_name;
            if ($Employee->id_build !== null)
                $Employeelog->build_name = Build::model()->findbyPk($Employee->id_build)->build_name;

            // Добавляем в лог не измененные значения ActiveRecord
            $result = self::JustAddToLog($Employee, $Employeelog);
        }

        return $result;
    }

    // Определяем количество материальной ценности с учетом изменения
    private static function MatNumberChanging(&$Material, &$Traflog, $Number, $Diff) {
        if (!self::$os && $Number != 0) {
            $Material->material_number = $Diff ? $Material->material_number - $Number : $Number;

            if (floatval($Material->material_number) < 0)
                $Traflog->traflog_message = 'Ошибка при изменении количества [Количество материальной ценности](' . $Material->material_number . ') плюс [Количество задействованное в операции](' . $Number . ') меньше 0. ';
        }
    }

    // Применяем изменения в атрибутах "Операции над материальной ценностью" или создаем нового
    // Пишем в лог
    private static function MattrafficDo(&$Mattraffic, &$Traflog, $row, $Material, $employee_id) {
        $result = false;

        $material_id = ($Material->material_id === NULL || $Material->material_id === '') ? -1 : $Material->material_id;
        $employee_id = ($employee_id === NULL || $employee_id === '') ? -1 : $employee_id;

        $xls_attributes_mattraffic = array_merge(self::xls_attributes_mattraffic($row), [
            'id_material' => $material_id,
            'id_mol' => $employee_id,
        ]);

        // Ищем Материальную ценность закрепленную за сотрудником
        // recordapply - Проверка актуальности даты операции над материальной ценностью с датой из Excel (1 - Дата актуальна, 0 - Дата не актуальна)
        // diff_number - Определяет текущее актуальное количество материальной ценности
        $find_ar = Mattraffic::model()->Find([
            'select' => '*, case when DATE(mattraffic_date) < :date_xls then true else false end as recordapply, mattraffic_number - :mattraffic_number AS diff_number',
            'condition' => 'id_material = :id_material and id_mol = :id_mol',
            'params' => [
                ':id_material' => $xls_attributes_mattraffic['id_material'],
                ':id_mol' => $xls_attributes_mattraffic['id_mol'],
                ':date_xls' => $xls_attributes_mattraffic['mattraffic_date'],
                ':mattraffic_number' => $xls_attributes_mattraffic['mattraffic_number']],
            'order' => 'mattraffic_date desc'
        ]);

        if ($find_ar !== null)
            $Mattraffic = $find_ar;

        $Traflog->attributes = $xls_attributes_mattraffic;

        if (!$Mattraffic->isNewRecord && $Mattraffic->recordapply) { // Если у материальной ценности найден сотрудник и запись актуальна
            // Разница в количестве (Количество из Excel - количество из БД)
            $diff_number = $Mattraffic->diff_number;
            self::$mattraffic_exist = true;

            // Если материал уже списан, но изменилась дата, просто меняем дату на актуальную
            if (self::$os && $Material->material_writeoff === '1') {
                $Mattraffic->mattraffic_date = $xls_attributes_mattraffic['mattraffic_date'];
                $Traflog->traflog_message .= 'Запись изменена: Дата изменена на "' . $Mattraffic->mattraffic_date . '"';
            } else {
                $Mattraffic = new Mattraffic;
                $Mattraffic->attributes = $xls_attributes_mattraffic;
                $Mattraffic->mattraffic_date = $xls_attributes_mattraffic['mattraffic_date'];
                $Mattraffic->mattraffic_number = self::$material_number_xls;
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
        }
        return $result;
    }

    // Применяем, изменился ли статус материальной ценности на списанную, вносим изменения в БД и создаем новый акт списания
    // Пишем в лог
    private static function WriteOffDo($Material, $Matlog, $Mattraffic, $Traflog, $row) {
        // Если Материал списан (Сумма = 0)
        if ((!self::$os && $material->material_writeoff == 0 && self::$material_price_xls == 0 && (!self::$mattraffic_exist || self::$mattraffic_exist && $Material->material_price != 0))
                // Или Основное средство списано (Статус = "Списан")
                || (self::$os && $Material->material_writeoff === '0' && trim($row[self::xls('material_status')]) === 'Списан')) {

            $Material->material_writeoff = 1;
            if (!self::$os)
                $Material->material_price = 0;

            $Material->save(false);

            $Matlog->material_writeoff = 'Да';
            $Matlog->save(false);

            $writeoffakt = new Writeoffakt();
            $writeoffakt->id_mattraffic = $Mattraffic->mattraffic_id;
            $writeoffakt->save(false);

            $Traflog->traflog_message.=' Добавлен акт списания с номером "' . $writeoffakt->writeoffakt_id . '" на дату "' . $Mattraffic->mattraffic_date . '".';
            $Traflog->save(false);
        }
    }

    // Производим импорт материальных ценностей
    static function ImportDo() {
        // Делаем запись в таблицу отчетов импорта
        $logreport = new Logreport;
        $logreport->logreport_date = date('Y-m-d');
        $logreport->save();
        self::$logreport_id = $logreport->logreport_id;

        // Идем по файлам импорта из 1С (os.xls - Основные средства, mat.xls - Материалы)
        foreach (['os.xls', 'mat.xls'] as $filename) {
            self::$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/imp/' . $filename;
            self::$os = self::$filename === dirname($_SERVER['SCRIPT_FILENAME']) . '/imp/os.xls' ? true : false;
            var_dump(self::$filename);
            var_dump(file_exists(self::$filename));
            if (file_exists(self::$filename)) {
                ini_set('max_execution_time', 1000);  // 1000 seconds
                ini_set('memory_limit', 1073741824); // 1Gbyte Max Memory

                $chunkSize = 1000;  //размер считываемых строк за раз
                $startRow = self::$os ? self::$os_start : self::$mat_start;   //начинаем читать с определенной строки
                $exit = false;   //флаг выхода
                $empty_value = 0;  //счетчик пустых знаений
                // Загружаем данные из файла Excel   
                require_once Yii::app()->basePath . '/extensions/PHPExcel/Classes/PHPExcel/IOFactory.php';
                $objReader = PHPExcel_IOFactory::createReaderForFile(self::$filename);
                $objReader->setReadDataOnly(true);
                $chunkFilter = new chunkReadFilter();
                $objReader->setReadFilter($chunkFilter);

                // Грузим правила импорта для сотрудников
                self::$employeewords = ARData::Data(Importemployee::model())->GetDataObject();

                // Определяем показатели импорта
                self::$logreport_errors = 0; // Не загружено записей из-за ошибок
                self::$logreport_updates = 0; // Записей изменено
                self::$logreport_additions = 0; // Записей добавлено     
                self::$logreport_missed = 0; // Записей пропущено (исключены из обработки)
                self::$logreport_amount = 0; // Всего записей

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

                        $row = $objWorksheet->rangeToArray('A' . $i . ':Q' . $i, null, true, true, true);
                        $row = $row[key($row)];

                        $material = new Material;
                        $employee = new Employee;
                        $mattraffic = new Mattraffic;
                        $matlog = new Matlog;
                        $employeelog = new Employeelog;
                        $traflog = new Traflog;

                        $MaterialDo = false;
                        $EmployeeDo = false;
                        $MattrafficDo = false;

                        // Применяем значения атрубутов Материальной ценности
                        $MaterialDo = self::MaterialDo($material, $matlog, $row);
                        if ($MaterialDo) {
                            // Применяем значения атрубутов Сотрудника
                            $EmployeeDo = self::EmployeeDo($employee, $employeelog, $row);

                            if ($EmployeeDo)
                            // Применяем значения атрубутов "Операции над материальной ценностью"
                                $MattrafficDo = self::MattrafficDo($mattraffic, $traflog, $row, $material, $employee->employee_id);
                        }

                        // Начинаем транзакцию
                        $transaction = Yii::app()->db->beginTransaction();
                        try {
                            // $matlog->matlog_type !== 5 - Если Запись не изменилась не пишем в лог
                            if ($matlog->matlog_type !== 5 && ($MaterialDo || (count($material->getErrors()) > 0)))
                                $matlog->save(false);

                            if ($MaterialDo) {
                                // $employeelog->employeelog_type !== 5 - Если Запись не изменилась не пишем в лог
                                if ($employeelog->employeelog_type !== 5 && ($EmployeeDo || (count($employee->getErrors()) > 0)))
                                    $employeelog->save(false);

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

                            $transaction->commit();
                        } catch (Exception $e) {
                            $transaction->rollback();
                            throw new CException($e->getMessage() . ' $rownum_xls = ' . self::$rownum_xls . '; $filename = ' . self::$filename);
                        }
                    }
                    $objPHPExcel->disconnectWorksheets();     //чистим 
                    unset($objPHPExcel);       //память
                    $startRow += $chunkSize;     //переходим на следующий шаг цикла, увеличивая строку, с которой будем читать файл
                }
                $logreport->logreport_additions += self::$logreport_additions;
                $logreport->logreport_updates += self::$logreport_updates;
                $logreport->logreport_errors += self::$logreport_errors;
                $logreport->logreport_missed += self::$logreport_missed;
                $logreport->logreport_amount += self::$rownum_xls - (self::$os ? self::$os_start : self::$mat_start);
                $logreport->save();
            }
        }
        ARData::Data(Traflog::model(), Traflog::model()->Test())->PrintData();
        echo '<BR>';
        ARData::Data(Matlog::model(), Matlog::model()->Test())->PrintData();
        echo '<BR>';
        ARData::Data(Employeelog::model(), Employeelog::model()->Test())->PrintData();
        echo '<BR>';
        ARData::Data(Material::model(), Material::model()->Test())->PrintData();
        echo '<BR> Акты списания';
        ARData::Data(Writeoffakt::model(), Writeoffakt::model()->Test())->PrintData();
    }

    // Импорт сотрудников из файла сотрудники.txt
    static function ImportEmployee() {
        // Делаем запись в таблицу отчетов импорта
        $logreport = new Logreport;
        $logreport->logreport_date = date('Y-m-d');
        $logreport->save();
        self::$logreport_id = $logreport->logreport_id;

        // Идем по файлам импорта из 1С (сотрудники.txt - Сотрудники)
        foreach (['сотрудники.txt'] as $filename) {
            self::$filename = mb_convert_encoding('imp/' . $filename, 'Windows-1251', 'UTF-8');
            if (file_exists(self::$filename)) {
                ini_set('max_execution_time', 1000);  // 1000 seconds
                ini_set('memory_limit', 1073741824); // 1Gbyte Max Memory
                // Определяем показатели импорта
                self::$logreport_errors = 0; // Не загружено записей из-за ошибок
                self::$logreport_additions = 0; // Записей добавлено     
                self::$logreport_amount = 0; // Всего записей
                $i = 0;

                $handle = @fopen(self::$filename, "r");

                if ($handle) {
                    while (($subject = fgets($handle, 4096)) !== false) {
                        $i++;
                        $pattern = '/^(.+?)\|(Поликлиника №\s?[1,2,3] )?(.+?)\|(.+?)\|/ui';
                        preg_match($pattern, $subject, $matches);

                        if ($matches[0] !== NULL) {
                            $pattern = '/(^Поликлиника №)\s?([1,2,3])\s?$/ui';
                            $matches[2] = preg_replace($pattern, 'Взрослая $1$2', mb_strtolower($matches[2], 'UTF-8'));

                            if ($matches[3] === 'Поликлиника профилактических осмотров')
                                $matches[2] = $matches[3];

                            $pattern = '/^(.+) БУ "Нижневартовская городская поликлиника"$/ui';
                            $matches[3] = preg_replace($pattern, '$1', $matches[3]);


                            $employee_fio = $matches[1];
                            $location = self::AssignLocationForEmployeeImport($matches[3], $matches[2]);

                            $id_dolzh = self::AssignDolzh($matches[4]);

                            // На случай если Здание у сотрудника не определено
                            $buildsql = $location->id_build === null ? 'id_build IS NULL' : 'id_build = :id_build';

                            // Находим сотрудника в базе, если не находим создаем новую запись
                            $Employee = Employee::model()->Find('employee_fio LIKE :employee_fio and id_dolzh = :id_dolzh and id_podraz = :id_podraz and ' . $buildsql, array_merge([
                                ':employee_fio' => $employee_fio,
                                ':id_dolzh' => $id_dolzh,
                                ':id_podraz' => $location->id_podraz
                                            ], $location->id_build === null ? [] : [':id_build' => $location->id_build]));

                            if ($Employee === null) {
                                $Employee = new Employee;
                                $Employee->attributes = [
                                    'employee_fio' => $employee_fio,
                                    'id_dolzh' => $id_dolzh,
                                    'id_podraz' => $location->id_podraz,
                                    'id_build' => $location->id_build
                                ];

                                $Employeelog = new Employeelog;
                                $Employeelog->id_logreport = self::$logreport_id;
                                $Employeelog->employeelog_type = 1;
                                $Employeelog->employeelog_filename = mb_convert_encoding(self::$filename, 'UTF-8', 'Windows-1251');
                                $Employeelog->employeelog_rownum = $i;
                                $Employeelog->employeelog_message = 'Запись добавлена.';

                                $Employee->scenario = 'import1c';

                                if ($Employee->validate()) {
                                    self::$logreport_additions++;
                                    $Employee->save(false);
                                } else {
                                    $Employeelog->employeelog_type = 3;
                                    $Employeelog->employeelog_message = 'Ошибка при добавлении записи: ';
                                    foreach ($Employee->getErrors() as $fields)
                                        $Employeelog->employeelog_message .= implode(' ', $fields) . ' ';
                                    self::$logreport_errors++;
                                }

                                $Employeelog->employee_fio = $Employee->employee_fio;
                                $Employeelog->dolzh_name = Dolzh::model()->findbyPk($Employee->id_dolzh)->dolzh_name;
                                $Employeelog->podraz_name = Podraz::model()->findbyPk($Employee->id_podraz)->podraz_name;
                                if ($Employee->id_build !== null)
                                    $Employeelog->build_name = Build::model()->findbyPk($Employee->id_build)->build_name;

                                $Employeelog->save(false);
                            }
                        } elseif (trim($subject) !== '') {
                            $Employeelog = new Employeelog;
                            $Employeelog->id_logreport = self::$logreport_id;
                            $Employeelog->employeelog_type = 3;
                            $Employeelog->employeelog_filename = mb_convert_encoding(self::$filename, 'UTF-8', 'Windows-1251');
                            $Employeelog->employeelog_rownum = $i;
                            $Employeelog->employeelog_message = 'Ошибка при добавлении записи: Не пройдено регулярное выражение /^(.+?)\|(Поликлиника №\s?[1,2,3] )?(.+?)\|(.+?)\|/ui';
                            $Employeelog->save(false);
                            self::$logreport_errors++;
                        }
                    }
                    fclose($handle);
                }
                $logreport->logreport_additions += self::$logreport_additions;
                $logreport->logreport_errors += self::$logreport_errors;
                $logreport->logreport_amount = $i;
                $logreport->save();
            }
        }

        echo '<BR>Подразделения:<BR>';
        ARData::Data(Podraz::model(), Podraz::model()->Test())->PrintData();
        echo '<BR>Должности:<BR>';
        ARData::Data(Dolzh::model(), Dolzh::model()->Test())->PrintData();
        echo '<BR>Сотрудники:<BR>';
        ARData::Data(Employee::model(), Employee::model()->Test())->PrintData();
        echo '<BR>Лог:<BR>';
        ARData::Data(Employeelog::model(), Employeelog::model()->Test())->PrintData();
    }

}
