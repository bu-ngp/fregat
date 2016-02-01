<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dolzh".
 *
 * @property integer $dolzh_id
 * @property string $dolzh_name
 *
 * @property Employee[] $employees
 */
class Dolzh extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dolzh';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dolzh_name'], 'required'],
            [['dolzh_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dolzh_id' => 'Dolzh ID',
            'dolzh_name' => 'Должность',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['id_dolzh' => 'dolzh_id']);
    }
}
