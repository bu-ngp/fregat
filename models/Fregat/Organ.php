<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "organ".
 *
 * @property integer $organ_id
 * @property string $organ_name
 *
 * @property Recoverysendakt[] $recoverysendakts
 */
class Organ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organ_name'], 'required'],
            [['organ_name'], 'filter', 'filter' => function ($value) {
                return mb_strtoupper($value, 'UTF-8');
            }],
            [['organ_name'], 'unique', 'message' => '{attribute} = {value} уже существует'],
            [['organ_name', 'organ_phones'], 'string', 'max' => 255],
            [['organ_email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'organ_id' => 'Организация',
            'organ_name' => 'Организация',
            'organ_email' => 'Электронная почта организации',
            'organ_phones' => 'Телефоны организации',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecoverysendakts()
    {
        return $this->hasMany(Recoverysendakt::className(), ['id_organ' => 'organ_id'])->from(['recoverysendakts' => Recoverysendakt::tableName()])->inverseOf('idOrgan');
    }
}
