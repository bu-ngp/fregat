<?php

namespace app\models\Base;

use Yii;

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
            [['preparat_id', 'preparat_name'], 'required'],
            [['preparat_id'], 'integer'],
            [['preparat_name'], 'string', 'max' => 255],
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
