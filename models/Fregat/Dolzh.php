<?php

namespace app\models\Fregat;

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
            [['dolzh_name'], 'string', 'max' => 100],
            [['dolzh_name'], 'unique', 'message' => '{attribute} = {value} уже существует'],
            [['dolzh_name'], 'match', 'pattern' => '/^null$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "NULL"'],
            [['dolzh_name'], 'filter', 'filter' => function ($value) {
                return mb_strtoupper($value, 'UTF-8');
            }],
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
        return $this->hasMany(Employee::className(), ['id_dolzh' => 'dolzh_id'])->from(['employees' => Employee::tableName()]);
    }

    public static function getDolzhByID($ID)
    {
        return $query = self::findOne($ID)->dolzh_name;
    }

}
