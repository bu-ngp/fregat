<?php

namespace app\models\Fregat\Import;

use Yii;

/**
 * This is the model class for table "logreport".
 *
 * @property string $logreport_id
 * @property string $logreport_date
 * @property integer $logreport_errors
 * @property integer $logreport_updates
 * @property integer $logreport_additions
 * @property string $logreport_amount
 * @property integer $logreport_missed
 * @property string $logreport_executetime
 * @property string $logreport_employeelastdate
 * @property string $logreport_oslastdate
 * @property string $logreport_matlastdate
 * @property string $logreport_gulastdate
 * @property string $logreport_memoryused
 *
 * @property Employeelog[] $employeelogs
 * @property Matlog[] $matlogs
 * @property Traflog[] $traflogs
 */
class Logreport extends \yii\db\ActiveRecord {

    public $maxfilelastdate;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'logreport';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['logreport_date'], 'required'],
            [['logreport_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['logreport_errors', 'logreport_updates', 'logreport_additions', 'logreport_amount', 'logreport_missed', 'logreport_memoryused'], 'integer'],
            [['logreport_employeelastdate', 'logreport_oslastdate', 'logreport_matlastdate', 'logreport_gulastdate'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['logreport_executetime'], 'date', 'format' => 'php:H:i:s'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'logreport_id' => 'Номер',
            'logreport_date' => 'Дата импорта',
            'logreport_errors' => 'Количество ошибок',
            'logreport_updates' => 'Записей изменено',
            'logreport_additions' => 'Записей добавлено',
            'logreport_amount' => 'Количество записей',
            'logreport_missed' => 'Записей пропущено',
            'logreport_executetime' => 'Время выполнения импорта',
            'logreport_memoryused' => 'Выделено памяти',
            'logreport_employeelastdate' => 'Дата последнего изменения файла для импорта сотрудников',
            'logreport_oslastdate' => 'Дата последнего изменения файла для импорта основных средств',
            'logreport_matlastdate' => 'Дата последнего изменения файла для импорта материалов',
            'logreport_gulastdate' => 'Дата последнего изменения файла для импорта группового учета основных средств',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeelogs() {
        return $this->hasMany(Employeelog::className(), ['id_logreport' => 'logreport_id'])->from(['employeelogs' => Employeelog::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatlogs() {
        return $this->hasMany(Matlog::className(), ['id_logreport' => 'logreport_id'])->from(['matlogs' => Matlog::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTraflogs() {
        return $this->hasMany(Traflog::className(), ['id_logreport' => 'logreport_id'])->from(['traflogs' => Traflog::tableName()]);
    }

}
