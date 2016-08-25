<?php

namespace app\models\Fregat\Import;

use Yii;

/**
 * This is the model class for table "employeelog".
 *
 * @property string $employeelog_id
 * @property string $id_logreport
 * @property string $employeelog_filename
 * @property string $employeelog_filelastdate
 * @property integer $employeelog_rownum
 * @property integer $employeelog_type
 * @property string $employeelog_message
 * @property string $employee_fio
 * @property string $dolzh_name
 * @property string $podraz_name
 * @property string $build_name
 *
 * @property Logreport $idLogreport
 * @property Traflog[] $traflogs
 */
class Employeelog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'employeelog';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_logreport', 'employeelog_filename', 'employeelog_rownum', 'employeelog_type', 'employeelog_message', 'employeelog_filelastdate'], 'required'],
            [['id_logreport', 'employeelog_rownum', 'employeelog_type'], 'integer'],
            [['employeelog_filename', 'employee_fio', 'dolzh_name', 'podraz_name', 'build_name'], 'string', 'max' => 255],
            [['employeelog_message'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'employeelog_id' => 'Employeelog ID',
            'id_logreport' => 'Id Logreport',
            'employeelog_filename' => 'Имя файла',
            'employeelog_filelastdate' => 'Дата изменения файла',
            'employeelog_rownum' => 'Номер строки',
            'employeelog_type' => 'Тип сообщения',
            'employeelog_message' => 'Сообщение',
            'employee_fio' => 'ФИО сотрудника',
            'dolzh_name' => 'Должность',
            'podraz_name' => 'Подразделение',
            'build_name' => 'Здание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdLogreport() {
        return $this->hasOne(Logreport::className(), ['logreport_id' => 'id_logreport'])->from(['idLogreport' => Logreport::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTraflogs() {
        return $this->hasMany(Traflog::className(), ['id_employeelog' => 'employeelog_id'])->from(['traflogs' => Traflog::tableName()]);
    }

}
