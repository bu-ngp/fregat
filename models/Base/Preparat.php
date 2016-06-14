<?php

namespace app\models\Base;

use Yii;
use app\models\Glauk\Glprep;

/**
 * This is the model class for table "preparat".
 *
 * @property integer $preparat_id
 * @property string $preparat_name
 *
 * @property Glprep[] $glpreps
 */
class Preparat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preparat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['preparat_name'], 'required'],
            [['preparat_name'], 'string', 'max' => 255],
            [['preparat_name'], 'unique', 'message' => '{attribute} = {value} уже существует'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'preparat_id' => 'Preparat ID',
            'preparat_name' => 'Наименование препарата',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlpreps()
    {
        return $this->hasMany(Glprep::className(), ['id_preparat' => 'preparat_id']);
    }
}
