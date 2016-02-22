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
 *
 * @property Employeelog[] $employeelogs
 * @property Matlog[] $matlogs
 * @property Traflog[] $traflogs
 */
class Logreport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logreport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['logreport_date'], 'required'],
            [['logreport_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['logreport_errors', 'logreport_updates', 'logreport_additions', 'logreport_amount', 'logreport_missed'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'logreport_id' => 'Logreport ID',
            'logreport_date' => 'Дата импорта',
            'logreport_errors' => 'Количество ошибок',
            'logreport_updates' => 'Записей изменено',
            'logreport_additions' => 'Записей добавлено',
            'logreport_amount' => 'Количество записей',
            'logreport_missed' => 'Записей пропущено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeelogs()
    {
        return $this->hasMany(Employeelog::className(), ['id_logreport' => 'logreport_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatlogs()
    {
        return $this->hasMany(Matlog::className(), ['id_logreport' => 'logreport_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTraflogs()
    {
        return $this->hasMany(Traflog::className(), ['id_logreport' => 'logreport_id']);
    }
}
