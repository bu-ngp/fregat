<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "podraz".
 *
 * @property integer $podraz_id
 * @property string $podraz_name
 *
 * @property Employee[] $employees
 * @property Importemployee[] $importemployees
 */
class Podraz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podraz';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['podraz_name'], 'required'],
            [['podraz_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'podraz_id' => 'Podraz ID',
            'podraz_name' => 'Подразделение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['id_podraz' => 'podraz_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImportemployees()
    {
        return $this->hasMany(Importemployee::className(), ['id_podraz' => 'podraz_id']);
    }
}
