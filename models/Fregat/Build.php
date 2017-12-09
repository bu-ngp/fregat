<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "build".
 *
 * @property integer $build_id
 * @property string $build_name
 *
 * @property Cabinet[] $cabinets
 * @property Employee[] $employees
 * @property Importemployee[] $importemployees
 */
class Build extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'build';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['build_name'], 'required'],
            [['build_name'], 'string', 'max' => 100],
            [['build_name'], 'unique', 'message' => '{attribute} = {value} уже существует'],
            [['build_name'], 'match', 'pattern' => '/^null$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "NULL"'],
            [['build_name'], 'filter', 'filter' => function ($value) {
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
            'build_id' => 'Build ID',
            'build_name' => 'Здание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCabinets()
    {
        return $this->hasMany(Cabinet::className(), ['id_build' => 'build_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['id_build' => 'build_id'])->from(['employees' => Employee::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImportemployees()
    {
        return $this->hasMany(Importemployee::className(), ['id_build' => 'build_id'])->from(['importemployees' => Importemployee::tableName()]);
    }

    public static function getBuildByID($ID)
    {
        return $query = self::findOne($ID)->build_name;
    }

}
