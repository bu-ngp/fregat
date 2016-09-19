<?php

namespace app\models\Fregat\Import;

use Yii;

/**
 * This is the model class for table "importconfig".
 *
 * @property integer $importconfig_id
 * @property string $os_filename
 * @property integer $os_startrow
 * @property string $os_material_1c
 * @property string $os_material_inv
 * @property string $os_material_name1c
 * @property string $os_material_price
 * @property string $os_employee_fio
 * @property string $os_dolzh_name
 * @property string $os_podraz_name
 * @property string $os_material_serial
 * @property string $os_material_release
 * @property string $os_material_status
 * @property string $mat_filename
 * @property integer $mat_startrow
 * @property string $mat_material_1c
 * @property string $mat_material_inv
 * @property string $mat_material_name1c
 * @property string $mat_material_number
 * @property string $mat_izmer_name
 * @property string $mat_material_price
 * @property string $mat_employee_fio
 * @property string $mat_dolzh_name
 * @property string $mat_podraz_name
 * @property string $mat_material_tip_nomenklaturi
 * @property integer $logreport_reportcount
 * @property string $emp_filname
 * @property integer $max_execution_time
 * @property string $memory_limit
 * @property string $gu_material_1c
 * @property string $gu_material_inv
 * @property string $gu_material_name1c
 * @property string $gu_material_serial
 * @property string $gu_material_release
 * @property string $gu_material_number
 * @property string $gu_material_price
 * @property string $gu_podraz_name
 * @property string $gu_employee_fio
 * @property string $gu_dolzh_name
 * @property string $gu_filename
 * @property integer $gu_startrow
 */
class Importconfig extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'importconfig';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['os_startrow', 'mat_startrow', 'os_filename', 'os_mattraffic_date', 'os_material_1c', 'os_material_inv', 'os_material_name1c', 'os_material_price', 'os_employee_fio', 'os_dolzh_name', 'os_podraz_name', 'os_material_serial', 'os_material_release', 'os_material_status', 'mat_filename', 'mat_material_1c', 'mat_material_inv', 'mat_material_name1c', 'mat_material_number', 'mat_izmer_name', 'mat_material_price', 'mat_employee_fio', 'mat_dolzh_name', 'mat_podraz_name', 'mat_material_tip_nomenklaturi', 'logreport_reportcount', 'emp_filename', 'max_execution_time', 'memory_limit', 'gu_material_1c', 'gu_material_inv', 'gu_material_name1c', 'gu_material_serial', 'gu_material_release', 'gu_material_number', 'gu_material_price', 'gu_podraz_name', 'gu_employee_fio', 'gu_dolzh_name', 'gu_filename', 'gu_startrow'], 'required'],
            [['os_startrow', 'mat_startrow', 'logreport_reportcount', 'max_execution_time', 'memory_limit', 'gu_startrow'], 'integer'],
            [['os_filename', 'mat_filename', 'emp_filename', 'gu_filename'], 'string', 'max' => 255],
            [['os_material_1c', 'os_mattraffic_date', 'os_material_inv', 'os_material_name1c', 'os_material_price', 'os_employee_fio', 'os_dolzh_name', 'os_podraz_name', 'os_material_serial', 'os_material_release', 'os_material_status', 'mat_material_1c', 'mat_material_inv', 'mat_material_name1c', 'mat_material_number', 'mat_izmer_name', 'mat_material_price', 'mat_employee_fio', 'mat_dolzh_name', 'mat_podraz_name', 'mat_material_tip_nomenklaturi', 'gu_material_1c', 'gu_material_inv', 'gu_material_name1c', 'gu_material_serial', 'gu_material_release', 'gu_material_number', 'gu_material_price', 'gu_podraz_name', 'gu_employee_fio', 'gu_dolzh_name'], 'string', 'max' => 5],
            [['os_material_1c', 'os_mattraffic_date', 'os_material_inv', 'os_material_name1c', 'os_material_price', 'os_employee_fio', 'os_dolzh_name', 'os_podraz_name', 'os_material_serial', 'os_material_release', 'os_material_status', 'mat_material_1c', 'mat_material_inv', 'mat_material_name1c', 'mat_material_number', 'mat_izmer_name', 'mat_material_price', 'mat_employee_fio', 'mat_dolzh_name', 'mat_podraz_name', 'mat_material_tip_nomenklaturi', 'gu_material_1c', 'gu_material_inv', 'gu_material_name1c', 'gu_material_serial', 'gu_material_release', 'gu_material_number', 'gu_material_price', 'gu_podraz_name', 'gu_employee_fio', 'gu_dolzh_name'], 'match', 'pattern' => '/^[a-z]$/iu', 'message' => '"{attribute}" Может состоять только из латинских букв'],
            [['os_material_1c', 'os_mattraffic_date', 'os_material_inv', 'os_material_name1c', 'os_material_price', 'os_employee_fio', 'os_dolzh_name', 'os_podraz_name', 'os_material_serial', 'os_material_release', 'os_material_status', 'mat_material_1c', 'mat_material_inv', 'mat_material_name1c', 'mat_material_number', 'mat_izmer_name', 'mat_material_price', 'mat_employee_fio', 'mat_dolzh_name', 'mat_podraz_name', 'mat_material_tip_nomenklaturi', 'gu_material_1c', 'gu_material_inv', 'gu_material_name1c', 'gu_material_serial', 'gu_material_release', 'gu_material_number', 'gu_material_price', 'gu_podraz_name', 'gu_employee_fio', 'gu_dolzh_name'], 'filter', 'filter' => function ($value) {
                return trim(strtoupper($value));
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'importconfig_id' => 'Importconfig ID',
            'os_filename' => 'Имя файла основных средств (.xlsx в директории "imp")',
            'os_startrow' => 'Номер строки файла Excel, с которой считываются основные средства',
            'os_mattraffic_date' => 'Позиция колонки "Период" основных средств',
            'os_material_1c' => 'Позиция колонки "Код 1С" основных средств',
            'os_material_inv' => 'Позиция колонки "Инвентарный номер" основных средств',
            'os_material_name1c' => 'Позиция колонки "Наименование" основных средств',
            'os_material_price' => 'Позиция колонки "Цена" основных средств',
            'os_employee_fio' => 'Позиция колонки "ФИО Материально-ответственного лица" основных средств',
            'os_dolzh_name' => 'Позиция колонки "Должность Материально-ответственного лица" основных средств',
            'os_podraz_name' => 'Позиция колонки "Подразделение Материально-ответственного лица" основных средств',
            'os_material_serial' => 'Позиция колонки "Серийный номер" основных средств',
            'os_material_release' => 'Позиция колонки "Дата выпуска" основных средств',
            'os_material_status' => 'Позиция колонки "Состояние" основных средств',
            'mat_filename' => 'Имя файла материалов (.xlsx в директории "imp")',
            'mat_startrow' => 'Номер строки файла Excel, с которой считываются материалы',
            'mat_material_1c' => 'Позиция колонки "Код 1С" материалов',
            'mat_material_inv' => 'Позиция колонки "Инвентарный номер" материалов',
            'mat_material_name1c' => 'Позиция колонки "Наименование" материалов',
            'mat_material_number' => 'Позиция колонки "Количество" материалов',
            'mat_izmer_name' => 'Позиция колонки "Единица измерения" материалов',
            'mat_material_price' => 'Позиция колонки "Стоимость" материалов',
            'mat_employee_fio' => 'Позиция колонки "ФИО Материально-ответственного лица" материалов',
            'mat_dolzh_name' => 'Позиция колонки "Должность Материально-ответственного лица" материалов',
            'mat_podraz_name' => 'Позиция колонки "Подразделение Материально-ответственного лица" материалов',
            'mat_material_tip_nomenklaturi' => 'Позиция колонки "Тип номенклатуры" материалов',
            'logreport_reportcount' => 'Количество хранящихся отчетов импорта',
            'emp_filename' => 'Имя файла сотрудников (.txt в директории "imp")',
            'max_execution_time' => 'Максимальное время выполнения загрузки файлов импорта (в секундах)',
            'memory_limit' => 'Максимальное потребление оперативной памяти при импорте (в Байтах)',
            'gu_material_1c' => 'Позиция колонки "Код 1С" группового учета основных средств',
            'gu_material_inv' => 'Позиция колонки "Инвентарный номер" группового учета основных средств',
            'gu_material_name1c' => 'Позиция колонки "Наименование" группового учета основных средств',
            'gu_material_serial' => 'Позиция колонки "Серийный номер" группового учета основных средств',
            'gu_material_release' => 'Позиция колонки "Дата выпуска" группового учета основных средств',
            'gu_material_number' => 'Позиция колонки "Количество" группового учета основных средств',
            'gu_material_price' => 'Позиция колонки "Цена" группового учета основных средств',
            'gu_podraz_name' => 'Позиция колонки "Подразделение Материально-ответственного лица" группового учета основных средств',
            'gu_employee_fio' => 'Позиция колонки "ФИО Материально-ответственного лица" группового учета основных средств',
            'gu_dolzh_name' => 'Позиция колонки "Должность Материально-ответственного лица" группового учета основных средств',
            'gu_filename' => 'Имя файла группового учета основных средств (.xlsx в директории "imp")',
            'gu_startrow' => 'Номер строки файла Excel, с которой считывается групповой учет основных средств',
        ];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (!$this->getIsNewRecord())
            return $this->update($runValidation, $attributeNames) !== false;
        else
            return false;
    }

}
