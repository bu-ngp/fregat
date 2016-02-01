<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "build".
 *
 * @property integer $build_id
 * @property string $build_name
 *
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
            [['build_name'], 'string', 'max' => 100]
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
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['id_build' => 'build_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImportemployees()
    {
        return $this->hasMany(Importemployee::className(), ['id_build' => 'build_id']);
    }
}
